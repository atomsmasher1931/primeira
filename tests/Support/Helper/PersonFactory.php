<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

use Symfony\Polyfill\Uuid\Uuid;
use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonPreferNotifierEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Enum\RoleEnum;
use Codeception\Module;
use Codeception\Module\DataFactory;
use League\FactoryMuffin\Faker\Facade;

class PersonFactory extends Module
{
	public function _beforeSuite(array $settings = [])
	{
		/** @var DataFactory $factory */
		$factory = $this->getModule('DataFactory');
		$this->setEmployeeMaker($factory);
		$this->setMusicianMaker($factory);
	}

	private function setEmployeeMaker(DataFactory $factory): void
	{
		$definition = $factory->factoryMuffin->define(Employee::class);
		$definition->setMaker(
			static function (): Employee {
				$employee = Employee::create(
					(string)Uuid::uuid_create(),
					Facade::lastName(250)(),
					Facade::firstName(250)(),
					Facade::name(250)() . 's',
					Facade::name(255)(),
					bin2hex(random_bytes(64)),
					[RoleEnum::getRoleByIntCode(Facade::numberBetween(0, 5)())],
					'79' . Facade::randomNumber(9, true)(),
					Facade::email()(),
					PersonStatusEnum::tryFrom(Facade::numberBetween(-1, 1)()),
				);
				$employee->changePassword('hashed test password');

				return $employee;
			}
		);
	}

	private function setMusicianMaker(DataFactory $factory): void
	{
		$definition = $factory->factoryMuffin->define(Musician::class);
		$definition->setMaker(
			static fn():Musician => Musician::create(
				(string)Uuid::uuid_create(),
				Facade::lastName(250)(),
				Facade::firstName(250)(),
				Facade::name(250)() . 's',
				PersonStatusEnum::tryFrom(Facade::numberBetween(-1, 0)()),
				PersonDegreeEnum::tryFrom(Facade::numberBetween(1, 2)()),
				PersonPreferNotifierEnum::EMAIL,
				'79' . Facade::randomNumber(9, true)(),
				Facade::email()(),
				'https://t.me/' . Facade::text(10)(),
				'https://www.instagram.com/' . Facade::text(10)(),
				'https://www.facebook.com/' . Facade::text(10)(),
				'https://vk.com/' . Facade::text(10)(),
			)
		);
	}
}
