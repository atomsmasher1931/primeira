<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Common\Output;

use DateTimeImmutable;

readonly class ContractDto
{
	public function __construct(
		public string $id,
		public string $number,
		public DateTimeImmutable $startDate,
		public DateTimeImmutable $finishDate,
		public TariffDto $tariff,
	) {
	}
}
