<?php

declare(strict_types=1);

namespace App\Person\Application\Dto;

use App\Person\Domain\Enum\PersonStatusEnum;

final readonly class CreateEmployeeDto
{
	public function __construct(
		public string $lastName,
		public string $firstName,
		public string $patronymic,
		public string $login,
		public string $password,
		public array $roles,
		public string $phone,
		public string $email,
		public PersonStatusEnum $status,
	) {
	}
}
