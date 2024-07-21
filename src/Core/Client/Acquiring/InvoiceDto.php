<?php

declare(strict_types=1);

namespace App\Core\Client\Acquiring;

use DateTimeImmutable;

final readonly class InvoiceDto
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
