<?php

declare(strict_types=1);

namespace App\Tests\Unit\Person;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Tests\Support\UnitTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Traversable;
use Throwable;

class EmployeeCest
{

    private const UUID = 'ab0cf837-5eb4-48dc-b40a-5c7605ec6112';
    private const LAST_NAME = 'Петров';
    private const FIRST_NAME = 'Иван';
    private const PATRONYMIC = 'Денисович';
    private const LOGIN = 'ipetrov';
    private const PASSWORD = 'password';
    private const SALT = '4e51bf95cec7174e9e378ee2d071f5d027e78c717f605c1dc3204952209e1234e9dd01068e8da70232635894a1ee786c26a2df584ada140f1b09d2f104281f06';
    private const USER_ROLE = 'ROLE_USER';
    private const PHONE = '79261234567';
    private const EMAIL = 'ipetrov@primeira.ru';
    private const STATUS = PersonStatusEnum::ACTIVE;

    #[DataProvider('employeesProvider')]
    public function testCreate(UnitTester $I, Example $example): void
    {
        $employee = Employee::create(...$example['employeeData']);
        $I->assertEquals(self::UUID, $employee->getId());
        $I->assertEquals(self::LAST_NAME, $employee->getLastName());
        $I->assertEquals(self::FIRST_NAME, $employee->getFirstName());
        $I->assertEquals(self::PATRONYMIC, $employee->getPatronymic());
        $I->assertEquals(self::LOGIN, $employee->getLogin());
        $I->assertEquals(self::SALT, $employee->getSalt());
        $I->assertEquals($example['roles'], $employee->getRoles());
        $I->assertEquals(self::EMAIL, $employee->getEmail());
        $I->assertEquals(self::STATUS, $employee->getStatus());

        $I->expectThrowable(
            Throwable::class,
            static fn(Employee $employee) => $employee->getPassword()
        );

        $employee->changePassword(self::PASSWORD);
        $I->assertEquals(self::PASSWORD, $employee->getPassword());
    }

    protected function employeesProvider(): Traversable
    {
        yield [
            'employeeData' => [
                'id' => self::UUID,
                'lastName' => self::LAST_NAME,
                'firstName' => self::FIRST_NAME,
                'patronymic' => self::PATRONYMIC,
                'login' => self::LOGIN,
                'salt' => self::SALT,
                'roles' => ['ROLE_ONE'],
                'phone' => self::PHONE,
                'email' => self::EMAIL,
                'status' => self::STATUS,
            ],
            'roles' => ['ROLE_ONE', self::USER_ROLE],
        ];

        yield [
            'employeeData' => [
                'id' => self::UUID,
                'lastName' => self::LAST_NAME,
                'firstName' => self::FIRST_NAME,
                'patronymic' => self::PATRONYMIC,
                'login' => self::LOGIN,
                'salt' => self::SALT,
                'roles' => ['ROLE_OTHER'],
                'phone' => self::PHONE,
                'email' => self::EMAIL,
                'status' => self::STATUS,
            ],
            'roles' => ['ROLE_OTHER', self::USER_ROLE],
        ];
    }
}
