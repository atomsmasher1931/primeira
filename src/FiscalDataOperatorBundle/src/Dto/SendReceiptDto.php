<?php

declare(strict_types=1);

namespace FiscalDataOperatorBundle\Dto;

use DateTimeImmutable;

class SendReceiptDto
{
	public function __construct(
		public DateTimeImmutable $paymentDate,
		public string $payerName,
		public string $paymentOrderMessage,
		public int $value,
	) {
	}
}
