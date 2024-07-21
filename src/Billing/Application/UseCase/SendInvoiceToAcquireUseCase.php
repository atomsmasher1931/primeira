<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Invoice;
use App\Billing\Domain\Exception\InvoiceManageException;
use App\Billing\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use Throwable;

final readonly class SendInvoiceToAcquireUseCase
{
	public function __construct(
		private InvoiceRepositoryInterface $invoiceRepository,
		private UnitOfWorkInterface $unitOfWork,
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
		$count = +count($issuedInvoices);
		$sent = $this->process($issuedInvoices);

		$sentInvoices = $this->invoiceRepository->getSentToAcquire();
		$count = +count($sentInvoices);
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
				//TODO Здесь бандл отправляется счёт в эквайринг и получает ID счёта в эквайре
				// если эквайер ответил ошибкой, то мы ловим её и не переводим в получено эквайером
				// научиться логировать ошибку эквайера
				$invoiceNumberInAcquire = '123';
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
