<?php

declare(strict_types=1);

namespace App\Core\Client\FiscalDataOperator;

use DateTimeImmutable;

final readonly class ReceiptDto
{
	public function __construct(
		public DateTimeImmutable $paymentDate,
		public string $payerName,
		public string $paymentOrderMessage,
		public int $value,
	) {
	}
}
