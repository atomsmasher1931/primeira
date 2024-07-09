<?php

declare(strict_types=1);

namespace App\Tests\Integration\Person\Entity;

use App\Core\PasswordHasher\PasswordHasherInterface;
use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;

class EmployeeCest
{

	const UUID = 'ab0cf837-5eb4-48dc-b40a-5c7605ec6112';
	const LAST_NAME = 'Петров';
	const FIRST_NAME = 'Иван';
	const PATRONYMIC = 'Денисович';
	const LOGIN = 'ipetrov';
	const SALT_1 = '4e51bf95cec7174e9e378ee2d071f5d027e78c717f605c1dc3204952209e1234e9dd01068e8da70232635894a1ee786c26a2df584ada140f1b09d2f104281f06';
	const SALT_2 = '6bc8e72772fc5bffc646b50c92cc7988ae7476af3b3b1565e92efa198c6eaab3d8126c689cb32f48207d462ce7d32e5d7150b141ef8ae984cea3627e0a83afc9';
	const PLAIN_PASSWORD = '123456';
	const ROLES = ['ROLE_ADMIN'];
	const RETURN_ROLES = ['ROLE_ADMIN', 'ROLE_USER'];
	const PHONE = '79261234567';
	const EMAIL = 'ipetrov@primeira.ru';
	const STATUS = PersonStatusEnum::ACTIVE;

	#[DataProvider('employeeProvider')]
	public function testCreate(FunctionalTester $I, Example $example): void
	{
		$passwordHasher = $I->grabService(PasswordHasherInterface::class);

		$employeeData = $example['employeeData'];
		$salt = $example['salt'];

		$employee = Employee::create(...$employeeData);
		$employee->changePassword($passwordHasher->hash($employee, self::PLAIN_PASSWORD));

		$I->assertEquals(self::UUID, $employee->getId());
		$I->assertEquals(self::LAST_NAME, $employee->getLastName());
		$I->assertEquals(self::FIRST_NAME, $employee->getFirstName());
		$I->assertEquals(self::PATRONYMIC, $employee->getPatronymic());
		$I->assertEquals(self::LOGIN, $employee->getLogin());
		$I->assertEquals($salt, $employee->getSalt());
		$I->assertEquals(self::RETURN_ROLES, $employee->getRoles());
		$I->assertEquals(self::EMAIL, $employee->getEmail());
		$I->assertEquals(self::STATUS, $employee->getStatus());

		$I->assertTrue($passwordHasher->check($employee, self::PLAIN_PASSWORD));
	}

	protected function employeeProvider(): \Traversable
	{
		yield [
			'employeeData' => [
				'id' => self::UUID,
				'lastName' => self::LAST_NAME,
				'firstName' => self::FIRST_NAME,
				'patronymic' => self::PATRONYMIC,
				'login' => self::LOGIN,
				'salt' => self::SALT_1,
				'roles' => self::ROLES,
				'phone' => self::PHONE,
				'email' => self::EMAIL,
				'status' => self::STATUS,
			],
			'salt' => self::SALT_1,
		];

		yield [
			'employeeData' => [
				'id' => self::UUID,
				'lastName' => self::LAST_NAME,
				'firstName' => self::FIRST_NAME,
				'patronymic' => self::PATRONYMIC,
				'login' => self::LOGIN,
				'salt' => self::SALT_2,
				'roles' => self::ROLES,
				'phone' => self::PHONE,
				'email' => self::EMAIL,
				'status' => self::STATUS,
			],
			'salt' => self::SALT_2,
		];
	}
}
