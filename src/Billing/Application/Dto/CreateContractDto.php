<?php

declare(strict_types=1);

namespace App\Billing\Application\Dto;

use DateTimeImmutable;

readonly class CreateContractDto
{
	public function __construct(
		public string $musician,
		public string $tariff,
		public DateTimeImmutable $startDate,
		public DateTimeImmutable $finishDate,
	) {
	}
}
