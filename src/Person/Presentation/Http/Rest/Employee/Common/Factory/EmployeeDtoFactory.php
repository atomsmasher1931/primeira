<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Common\Factory;

use App\Person\Application\Dto\CreateEmployeeDto;
use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Presentation\Http\Rest\Employee\Common\Output\EmployeeDto;
use App\Person\Presentation\Http\Rest\Employee\Create\V1\Input\CreateEmployeeData;

final readonly class EmployeeDtoFactory
{
	public function createFromUser(Employee $user): EmployeeDto
	{
		return new EmployeeDto(
			$user->getId(),
			$user->getLogin(),
			$user->getRoles(),
			$user->getLastName(),
			$user->getFirstName(),
			$user->getPatronymic(),
			$user->getPhone(),
			$user->getEmail(),
		);
	}

	public function createFromCreatUserDto(CreateEmployeeData $dto): CreateEmployeeDto
	{
		return new CreateEmployeeDto(
			$dto->lastName,
			$dto->firstName,
			$dto->patronymic,
			$dto->login,
			$dto->passwords,
			$dto->roles,
			$dto->phone,
			$dto->email,
			PersonStatusEnum::tryFrom($dto->status),
		);
	}
}
