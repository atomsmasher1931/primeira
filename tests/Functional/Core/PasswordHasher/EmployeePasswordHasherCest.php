<?php

declare(strict_types=1);

namespace App\Tests\Integration\Core\PasswordHasher;

use App\Core\PasswordHasher\PasswordHasherInterface;
use App\Person\Domain\Entity\Employee;
use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Mockery;

class EmployeePasswordHasherCest
{
    private const SALT_1 = '4e51bf95cec7174e9e378ee2d071f5d027e78c717f605c1dc3204952209e1234e9dd01068e8da70232635894a1ee786c26a2df584ada140f1b09d2f104281f06';
    private const SALT_2 = '6bc8e72772fc5bffc646b50c92cc7988ae7476af3b3b1565e92efa198c6eaab3d8126c689cb32f48207d462ce7d32e5d7150b141ef8ae984cea3627e0a83afc9';
    private const PLAIN_PASSWORD = '123456';

	#[DataProvider('saltProvider')]
	public function testCreate(FunctionalTester $I, Example $example): void
	{
		$passwordHasher = $I->grabService(PasswordHasherInterface::class);

        $password = self::PLAIN_PASSWORD;
        $salt = $example['salt'];

        $employee = Mockery::mock(Employee::class);
        $employee->shouldReceive('getSalt')->andReturn($salt);
        $employee->shouldReceive('getPassword')->andReturn($passwordHasher->hash($employee, $password));

		$I->assertTrue($passwordHasher->check($employee, $password));
	}

	protected function saltProvider(): \Traversable
	{
		yield [
			'salt' => self::SALT_1,
		];

		yield [
			'salt' => self::SALT_2,
		];
	}
}
