<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Common\Output;

use App\Person\Domain\Enum\MusicianDegreeTariffEnum;
use App\Person\Domain\Enum\TariffStatusEnum;
use App\Person\Domain\Enum\TariffTypeEnum;
use DateTimeImmutable;

readonly class TariffDto
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
