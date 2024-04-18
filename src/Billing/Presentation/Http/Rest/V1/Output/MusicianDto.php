<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Output;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;

class MusicianDto
{
	public function __construct(
		public string $id,
		public string $lastName,
		public string $firstName,
		public string $patronymic,
		public PersonStatusEnum $status,
		public PersonDegreeEnum $degree,
	) {
	}
}
