<?php

declare(strict_types=1);

namespace App\Core\Client\Acquiring;

use AcquiringBundle\Dto\SendInvoiceDto;
use AcquiringBundle\Facade\AcquiringFacade;

/**
 * Клиент взаимодействия с эквайрингом
 */
final readonly class AcquiringClient
{
	public function __construct(private AcquiringFacade $acquiring)
	{
	}

	public function sendInvoice(InvoiceDto $invoice): string
	{
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
	}

	public function checkPayment(string $acquireInvoiceId)
	{
		return $this->acquiring->checkPayment($acquireInvoiceId);
	}
}
