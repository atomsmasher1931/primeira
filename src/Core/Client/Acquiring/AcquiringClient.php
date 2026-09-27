<?php

declare(strict_types=1);

namespace App\Core\Client\Acquiring;

use AcquiringBundle\Dto\SendInvoiceDto;
use AcquiringBundle\Facade\AcquiringFacade;
use DateTimeImmutable;
use Throwable;

/**
 * Клиент взаимодействия с эквайрингом
 */
final readonly class AcquiringClient
{
	public function __construct(private AcquiringFacade $acquiring)
	{
	}

	/**
	 * @throws AcquiringClientException
	 */
	public function sendInvoice(InvoiceDto $invoice): string
	{
		try {
			return $this->acquiring->sendInvoice(
				new SendInvoiceDto(
					$invoice->issuedDate,
					$invoice->expiredDate,
					$invoice->payerPhone,
					$invoice->payerEmail,
					$invoice->paymentOrderMessage,
					$invoice->value,
				)
			);
		} catch (Throwable $exception) {
			throw new AcquiringClientException($exception->getMessage(), $exception);
		}
	}

	/**
	 * @throws AcquiringClientException
	 */
	public function checkPayment(string $acquireInvoiceId): DateTimeImmutable
	{
		try {
			return $this->acquiring->checkPayment($acquireInvoiceId);
		} catch (Throwable $exception) {
			throw new AcquiringClientException($exception->getMessage(), $exception);
		}
	}
}
