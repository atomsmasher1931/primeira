<?php

declare(strict_types=1);

namespace App\Billing\Application\Dto;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use \DateTimeImmutable;

readonly final class CreateTariffDto
{
	public function __construct(
		public MusicianDegreeTariffEnum $musicianDegree,
		public TariffTypeEnum $type,
		public int $value,
		public DateTimeImmutable $startDate,
		public DateTimeImmutable $finishDate,
		public TariffStatusEnum $status,
	) {
	}
}
