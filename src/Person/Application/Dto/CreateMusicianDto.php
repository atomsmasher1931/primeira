<?php

declare(strict_types=1);

namespace App\Person\Application\Dto;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;

class CreateMusicianDto
{
	public function __construct(
		public string $lastName,
		public string $firstName,
		public string $patronymic,
		public PersonStatusEnum $status,
		public PersonDegreeEnum $degree,
		public string $phone,
		public string $email,
		public string $telegram,
		public ?string $instagram = null,
		public ?string $facebook = null,
		public ?string $VK = null,
	) {
	}
}
