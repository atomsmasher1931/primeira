<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Output;

use App\Billing\Domain\Enum\MusicianDegreeEnum;
use App\Billing\Domain\Enum\MusicianStatusEnum;

class MusicianDto
{
	public function __construct(
		public string $id,
		public string $lastName,
		public string $firstName,
		public string $patronymic,
		public MusicianStatusEnum $status,
		public MusicianDegreeEnum $degree,
	) {
	}
}
