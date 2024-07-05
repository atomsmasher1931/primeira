<?php

declare(strict_types=1);

namespace App\Person\Application\Dto;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonPreferNotifierEnum;
use App\Person\Domain\Enum\PersonStatusEnum;

readonly class PatchMusicianDto
{
	public function __construct(
		public ?string $lastName,
		public ?string $firstName,
		public ?string $patronymic,
		public ?PersonStatusEnum $status,
		public ?PersonDegreeEnum $degree,
		public ?PersonPreferNotifierEnum $notifier,
		public ?string $email,
		public ?string $phone,
		public ?string $telegram,
		public ?string $instagram,
		public ?string $facebook,
		public ?string $VK,
	) {
	}
}
