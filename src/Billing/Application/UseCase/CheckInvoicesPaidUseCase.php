<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Exception\InvoiceManageException;
use App\Billing\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\Client\Acquiring\AcquiringClient;
use App\Core\Client\FiscalDataOperator\FiscalDataOperatorClient;
use App\Core\Client\FiscalDataOperator\ReceiptDto;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use Throwable;

final readonly class CheckInvoicesPaidUseCase
{
	public function __construct(
		private InvoiceRepositoryInterface $invoiceRepository,
		private UnitOfWorkInterface $unitOfWork,
		private AcquiringClient $acquiringClient,
		private FiscalDataOperatorClient $fiscalDataOperatorClient,
	) {
	}

	public function __invoke(): int
	{
		$invoices = $this->invoiceRepository->getReceived();
		$i = 0;
		foreach ($invoices as $invoice) {
			try {
				//TODO Может быть сетевой ошибка да и просто ошибка бандла
				$paidData = $this->acquiringClient->checkPayment($invoice->getAcquiringNumber());
				$invoice->paid($paidData);

				$this->unitOfWork->persist($invoice);
				$this->unitOfWork->flush();

				//TODO возможны ошибки, требуется повторная отправка
				$receipt = $this->fiscalDataOperatorClient->sendReceipt(
					new ReceiptDto(
						$invoice->getPaidDate(),
						$invoice->getPayerFullName(),
						$invoice->getInvoiceMessage(),
						$invoice->getSum(),
					)
				);

				$i++;
			} catch (Throwable $exception) {
				throw new InvoiceManageException(
					$exception,
					'Ошибка проверки статуса оплаты счёта.' . $exception->getMessage()
				);
			}
		}

		return $i;
	}
}
