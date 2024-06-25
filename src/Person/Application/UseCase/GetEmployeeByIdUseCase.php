<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeNotFoundException;
use App\Person\Domain\Repository\EmployeeRepositoryInterface;

final readonly class GetEmployeeByIdUseCase
{
	public function __construct(private EmployeeRepositoryInterface $employeeRepository)
	{
	}

	/**
	 * @throws EmployeeNotFoundException
	 */
	public function __invoke($id): Employee
	{
		return $this->employeeRepository->getById($id);
	}
}
