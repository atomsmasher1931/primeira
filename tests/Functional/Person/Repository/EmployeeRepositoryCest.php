<?php

declare(strict_types=1);

namespace App\Tests\Integration\Person\Repository;


use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeNotFoundException;
use App\Person\Domain\Repository\EmployeeRepositoryInterface;
use App\Tests\Support\FunctionalTester;

/**
 * @covers \App\Person\Domain\Repository\EmployeeRepositoryInterface
 */
class EmployeeRepositoryCest
{
	/** @covers \App\Person\Domain\Repository\EmployeeRepositoryInterface::getByLogin */
	public function testGetByLoginSuccess(FunctionalTester $I)
	{
		/** @var Employee $employee */
		$employee = $I->have(Employee::class);

		/** @var EmployeeRepositoryInterface $repository */
		$repository = $I->grabService(EmployeeRepositoryInterface::class);
		$employeeExpected = $repository->getByLogin($employee->getLogin());

		$I->assertEquals($employeeExpected, $employee);
	}

	/** @covers \App\Person\Domain\Repository\EmployeeRepositoryInterface::getByLogin */
	public function testGetByLoginFail(FunctionalTester $I)
	{
		/** @var Employee $employee */
		$employee = $I->have(Employee::class);

		/** @var EmployeeRepositoryInterface $repository */
		$repository = $I->grabService(EmployeeRepositoryInterface::class);

		$I->expectThrowable(
			EmployeeNotFoundException::class,
			static fn() => $repository->getByLogin($employee->getLogin() . 'error')
		);
	}

	/** @covers \App\Person\Domain\Repository\EmployeeRepositoryInterface::getById */
	public function testGetByIdSuccess(FunctionalTester $I)
	{
		/** @var Employee $employee */
		$employee = $I->have(Employee::class);

		/** @var EmployeeRepositoryInterface $repository */
		$repository = $I->grabService(EmployeeRepositoryInterface::class);
		$employeeExpected = $repository->getById($employee->getId());

		$I->assertEquals($employeeExpected, $employee);
	}

	/** @covers \App\Person\Domain\Repository\EmployeeRepositoryInterface::getById */
	public function testGetByIdFail(FunctionalTester $I)
	{
		/** @var Employee $employee */
		$employee = $I->have(Employee::class);

		/** @var EmployeeRepositoryInterface $repository */
		$repository = $I->grabService(EmployeeRepositoryInterface::class);

		$I->expectThrowable(
			EmployeeNotFoundException::class,
			static fn() => $repository->getById(str_replace(['a', 'b'], ['c', 'd'], $employee->getId()))
		);
	}
}
