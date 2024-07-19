<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeNotFoundException;
use App\Person\Domain\Exception\EmployeeWrongPasswordException;
use App\Person\Domain\Repository\EmployeeRepositoryInterface;
use App\Core\PasswordHasher\PasswordHasherInterface;

final readonly class GetEmployeeByLoginAndPasswordUseCase
{
	public function __construct(
		private PasswordHasherInterface $passwordHasher,
		private EmployeeRepositoryInterface $employeeRepository,
	) {
	}

	/**
	 * @throws EmployeeNotFoundException
	 * @throws EmployeeWrongPasswordException
	 */
	public function __invoke(string $login, string $password): Employee
	{
		$employee = $this->employeeRepository->getByLogin($login);
		if (!$this->passwordHasher->check($employee, $password)) {
			throw new EmployeeWrongPasswordException();
		}

		return $employee;
	}
}
