<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Exception\InvoiceManageException;
use App\Billing\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use Throwable;

final readonly class CheckInvoicesPaidUseCase
{
	public function __construct(
		private InvoiceRepositoryInterface $invoiceRepository,
		private UnitOfWorkInterface $unitOfWork,
	) {
	}

	public function __invoke(): int
	{
		$invoices = $this->invoiceRepository->getReceived();
		$i = 0;
		foreach ($invoices as $invoice) {
			try {
				//TODO Номер счёта у эквайера $invoice->getAcquiringNumber() передаётся в бандл и получаем статус
				// Может быть сетевай ошибка да и просто ошибка бандла
				$isPaid = true;
				$paidData = new \DateTimeImmutable();
				if ($isPaid) {
					$invoice->paid($paidData);
				}

				$this->unitOfWork->persist($invoice);
				$this->unitOfWork->flush();

				//TODO После удачной отправки надо дёрнуть бандл отправки чеков и отправить чек,
				// Считаем, что сохранение чека в бандле
				// лучше сделать это отдельной командой и записать инфу по чеку в отдельную сущность (успеем ли?)
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
