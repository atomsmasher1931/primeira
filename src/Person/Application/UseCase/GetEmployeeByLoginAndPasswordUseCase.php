<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeNotFoundException;
use App\Person\Domain\Exception\EmployeeWrongPasswordException;
use App\Person\Domain\Repository\EmployeeRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class GetEmployeeByLoginAndPasswordUseCase
{
	public function __construct(
		private UserPasswordHasherInterface $passwordHasher,
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
		if (!$this->passwordHasher->isPasswordValid($employee, $password)) {
			throw new EmployeeWrongPasswordException();
		}

		return $employee;
	}
}
