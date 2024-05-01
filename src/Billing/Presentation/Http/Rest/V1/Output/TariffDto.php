<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Output;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use DateTimeImmutable;

class TariffDto
{
	public function __construct(
		public string $id,
		public MusicianDegreeTariffEnum $musicianDegree,
		public TariffTypeEnum $type,
		public int $value,
		public DateTimeImmutable $startDate,
		public DateTimeImmutable $finishDate,
		public TariffStatusEnum $status,
	) {
	}
}
