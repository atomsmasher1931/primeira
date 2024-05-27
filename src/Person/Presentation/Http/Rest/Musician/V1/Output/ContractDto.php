<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\V1\Output;

use DateTimeImmutable;

readonly class ContractDto
{
	public function __construct(
		string $id,
		string $number,
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		TariffDto $tariff,
	) {
	}
}
