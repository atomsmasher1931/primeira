<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Tariff\Update\V1\Input;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class PutTariffData
{
	public function __construct(
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [MusicianDegreeTariffEnum::class, 'values'])]
		public int $musicianDegree,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [TariffTypeEnum::class, 'values'])]
		public int $type,
		#[Assert\NotBlank()]
		#[Assert\Type(type: 'numeric')]
		public int $value,
		#[Assert\NotBlank()]
		#[Assert\DateTime()]
		public string $startDate,
		#[Assert\NotBlank()]
		#[Assert\DateTime()]
		public string $finishDate,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [TariffStatusEnum::class, 'values'])]
		public int $status,
	) {
	}
}
