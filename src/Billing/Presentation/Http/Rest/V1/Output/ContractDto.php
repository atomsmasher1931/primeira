<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Output;

use DateTimeImmutable;

readonly class ContractDto
{
	public function __construct(
		public string $id,
		public string $number,
		public DateTimeImmutable $startDate,
		public DateTimeImmutable $finishDate,
		public TariffDto $tariff,
		public MusicianDto $musician
	) {
	}
}
