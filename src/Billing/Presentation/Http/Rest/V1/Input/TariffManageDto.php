<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Input;

use Symfony\Component\Validator\Constraints as Assert;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use \DateTimeImmutable;

class TariffManageDto
{
	public function __construct(
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [MusicianDegreeTariffEnum::class, 'values'])]
		public ?int $musicianDegree = null,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [TariffTypeEnum::class, 'values'])]
		public ?int $type = null,
		#[Assert\NotBlank()]
		#[Assert\Type(type: 'numeric')]
		public ?int $value = null,
		#[Assert\NotBlank()]
		#[Assert\Type(type: 'DateTimeImmutable')]
		public ?DateTimeImmutable $startDate = null,
		#[Assert\NotBlank()]
		#[Assert\Type(type: 'DateTimeImmutable')]
		public ?DateTimeImmutable $finishDate = null,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [TariffStatusEnum::class, 'values'])]
		public ?int $status = null,
	) {
	}
}
