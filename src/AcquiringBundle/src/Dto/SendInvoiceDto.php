<?php

declare(strict_types=1);

namespace AcquiringBundle\Dto;

use DateTimeImmutable;

class SendInvoiceDto
{
	public function __construct(
		public DateTimeImmutable $issuedDate,
		public DateTimeImmutable $expiredDate,
		public string $payerPhone,
		public string $payerEmail,
		public string $paymentOrderMessage,
		public int $value,
	) {
	}
}
