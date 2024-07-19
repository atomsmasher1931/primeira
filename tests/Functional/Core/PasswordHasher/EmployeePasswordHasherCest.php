<?php

declare(strict_types=1);

namespace App\Tests\Functional\Core\PasswordHasher;

use App\Core\PasswordHasher\PasswordHasherInterface;
use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;

/**
 * Тест проверяет работу используемого проектом хэшера пароля.
 * Из теста становится ясно, что хэшер не использует соль, а всё зачем ему нужен пользователь - это сам пароль и определение логики хэширования
 * Этот тест проверяет кейс, что для двух разных солей получается один и тот же хэш.
 * 1. Создаём работника
 * 2. Устанавливаем ему через метод changePassword хэш пароля (HASHED_PASSWORD) для PLAIN_PASSWORD
 * 3. Проверяем с PLAIN_PASSWORD, что HASHED_PASSWORD правильный и одинаковый независимо от соли
 */
class EmployeePasswordHasherCest
{
	private const UUID = 'ab0cf837-5eb4-48dc-b40a-5c7605ec6112';
	private const LAST_NAME = 'Петров';
	private const FIRST_NAME = 'Иван';
	private const PATRONYMIC = 'Денисович';
	private const LOGIN = 'ipetrov';
	private const PLAIN_PASSWORD = '123456';
	/** @var string Это хэшированный пароль для пароля PLAIN_PASSWORD и SALT_1 */
	private const HASHED_PASSWORD = '$2y$13$.8uIDeMXUzIX72eDQYg2yebTD.MimCcuxDwuOgfU1/o4YnIu0qEeu';
	private const SALT_1 = '4e51bf95cec7174e9e378ee2d071f5d027e78c717f605c1dc3204952209e1234e9dd01068e8da70232635894a1ee786c26a2df584ada140f1b09d2f104281f06';
	private const SALT_2 = '6bc8e72772fc5bffc646b50c92cc7988ae7476af3b3b1565e92efa198c6eaab3d8126c689cb32f48207d462ce7d32e5d7150b141ef8ae984cea3627e0a83afc9';
	private const USER_ROLE = 'ROLE_USER';
	private const PHONE = '79261234567';
	private const EMAIL = 'ipetrov@primeira.ru';
	private const STATUS = PersonStatusEnum::ACTIVE;

	#[DataProvider('saltProvider')]
	public function testCreate(FunctionalTester $I, Example $example): void
	{
		/** @var PasswordHasherInterface $passwordHasher */
		$passwordHasher = $I->grabService(PasswordHasherInterface::class);

		$salt = $example['salt'];
		$employee = Employee::create(
			self::UUID,
			self::LAST_NAME,
			self::FIRST_NAME,
			self::PATRONYMIC,
			self::LOGIN,
			$salt,
			[self::USER_ROLE],
			self::PHONE,
			self::EMAIL,
			self::STATUS,
		);
		$employee->changePassword(self::HASHED_PASSWORD);

		$I->assertTrue($passwordHasher->check($employee, self::PLAIN_PASSWORD));
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
