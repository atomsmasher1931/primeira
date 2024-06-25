<?php

declare(strict_types=1);

namespace App\Person\Domain\Repository;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeNotFoundException;

interface EmployeeRepositoryInterface
{
	/**
	 * @throws EmployeeNotFoundException
	 */
	public function getByLogin(string $login): Employee;

	/**
	 * @throws EmployeeNotFoundException
	 */
	public function getById(string $id): Employee;
}
