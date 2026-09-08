<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Invoice;
use App\Billing\Domain\Exception\InvoiceManageException;
use App\Billing\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\Client\Acquiring\AcquiringClient;
use App\Core\Client\Acquiring\InvoiceDto;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use Throwable;

final readonly class SendInvoiceToAcquireUseCase
{
	public function __construct(
		private InvoiceRepositoryInterface $invoiceRepository,
		private UnitOfWorkInterface $unitOfWork,
		private AcquiringClient $acquiringClient,
	) {
	}

	/**
	 * @return int[]
	 * @throws InvoiceManageException
	 */
	public function __invoke(): array
	{
		$count = 0;
		$issuedInvoices = $this->invoiceRepository->getIssued();
		$count += count($issuedInvoices);
		$sent = $this->process($issuedInvoices);

		$sentInvoices = $this->invoiceRepository->getSentToAcquire();
		$count += count($sentInvoices);
		$resent = $this->process($sentInvoices);

		return [
			'sent' => $sent,
			'resent' => $resent,
			'didnt_sent' => $count - $sent - $resent,
		];
	}

	/**
	 * @param Invoice[] $invoices
	 * @throws InvoiceManageException
	 */
	private function process(array $invoices): int
	{
		$i = 0;
		foreach ($invoices as $invoice) {
			try {

				$invoice->sentToAcquire();

				$invoiceNumberInAcquire = $this->acquiringClient->sendInvoice(
					new InvoiceDto(
						$invoice->getIssueDate(),
						$invoice->getExpiredDate(),
						$invoice->getPayerPhone(),
						$invoice->getPayerEmail(),
						$invoice->getInvoiceMessage(),
						$invoice->getSum(),
					)
				);
				$invoice->receivedByAcquire($invoiceNumberInAcquire);

				$this->unitOfWork->persist($invoice);
				$this->unitOfWork->flush();

				$i++;
			} catch (UnitOfWorkException $exception) {
				//TODO Заглушить и логировать
				throw new InvoiceManageException($exception, 'Ошибка сохранения счёта.' . $exception->getMessage());

			} catch (Throwable $exception) {
				//TODO Заглушить и логировать
				throw new InvoiceManageException($exception);
			}
		}

		return $i;
	}
}
