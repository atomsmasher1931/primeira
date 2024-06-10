<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Common\Output;

final readonly class EmployeeDto
{
	public function __construct(
		public string $id,
		public string $login,
		public array $roles,
		public string $lastName,
		public string $firstName,
		public string $patronymic,
		public string $phone,
		public string $email,
	) {
	}
}
