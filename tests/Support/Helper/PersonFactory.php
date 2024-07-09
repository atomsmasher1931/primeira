<?php

declare(strict_types=1);

namespace Support\Helper;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Enum\PersonStatusEnum;
use Codeception\Module;
use League\FactoryMuffin\Faker\Facade;

class PersonFactory extends Module
{
	public function _beforeSuite(array $settings = [])
	{
		/** @var Module\DataFactory $factory */
		$factory = $this->getModule('DataFactory');

		$factory->_define(
			Employee::class,
			[
				'id' => Facade::text(36)(),
				'lastName' => Facade::name(250)(),
				'firstName' => Facade::name(250)(),
				'patronymic' => Facade::name(250)(),
				'login' => Facade::text(255)(),
				'password' => Facade::text(255)(),
				'salt' => bin2hex(random_bytes(64)),
				'roles' => Facade::text(20)(),
				'phone' => '7'.Facade::randomNumber(10, true)(),
				'email' => Facade::email()(),
				'status' => PersonStatusEnum::tryFrom(Facade::randomNumber())

			]
		);
	}
}
