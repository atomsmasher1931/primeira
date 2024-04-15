<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Output;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use DateTimeImmutable;

class TariffDto
{
	public function __construct(
		public readonly string $id,
		public readonly MusicianDegreeTariffEnum $musicianDegree,
		public readonly TariffTypeEnum $type,
		public readonly int $value,
		public readonly DateTimeImmutable $startDate,
		public readonly DateTimeImmutable $finishDate,
		public readonly TariffStatusEnum $status,
	) {
	}
}
