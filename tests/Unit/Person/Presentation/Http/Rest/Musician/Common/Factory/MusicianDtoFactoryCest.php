<?php

declare(strict_types=1);

namespace App\Tests\Unit\Person\Presentation\Http\Rest\Musician\Common\Factory;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonPreferNotifierEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Enum\TariffTypeEnum as PersonTariffTypeEnum;
use App\Person\Presentation\Http\Rest\Musician\Common\Factory\ContractDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\Common\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\Common\Factory\TariffDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\Common\Output\ContractDto;
use App\Tests\Support\UnitTester;
use DateTimeImmutable;

/**
 * Регрессия: у музыканта с договором GetMusicianById/GetMusicianByPhone падали с TypeError,
 * т.к. ContractDtoFactory::createFromContracts() перезаписывал $contractsDto вместо накопления
 * в массив, а App\Person\...\Output\ContractDto не имел промотированных свойств.
 *
 * Заодно проверяет anti-corruption layer: DTO контекста Person собран из его собственных
 * enum'ов (App\Person\Domain\Enum\*), а не типов Billing, даже если исходные данные — из Billing.
 *
 * @covers \App\Person\Presentation\Http\Rest\Musician\Common\Factory\MusicianDtoFactory
 * @covers \App\Person\Presentation\Http\Rest\Musician\Common\Factory\ContractDtoFactory
 * @covers \App\Person\Presentation\Http\Rest\Musician\Common\Factory\TariffDtoFactory
 */
class MusicianDtoFactoryCest
{
	public function testMusicianWithContractsIsSerializedWithoutError(UnitTester $I): void
	{
		$tariff = Tariff::create(
			'6d2e6f0e-2f2a-4a4b-8a3b-3f4b9a2c0001',
			MusicianDegreeTariffEnum::FOR_NEWBIE,
			TariffTypeEnum::MONTHLY,
			1000,
			new DateTimeImmutable('2026-01-01'),
			new DateTimeImmutable('2026-12-31'),
			TariffStatusEnum::ACTIVE,
		);

		$musician = Musician::create(
			'6d2e6f0e-2f2a-4a4b-8a3b-3f4b9a2c0002',
			'Петров',
			'Иван',
			'Денисович',
			PersonStatusEnum::ACTIVE,
			PersonDegreeEnum::NEWBIE,
			PersonPreferNotifierEnum::EMAIL,
			'79261234567',
			'ipetrov@primeira.ru',
			'@ipetrov',
		);

		$contractOne = Contract::create(
			'6d2e6f0e-2f2a-4a4b-8a3b-3f4b9a2c0003',
			'2026/01-01',
			new DateTimeImmutable('2026-01-01'),
			new DateTimeImmutable('2026-12-31'),
			$tariff,
			$musician,
		);
		$contractTwo = Contract::create(
			'6d2e6f0e-2f2a-4a4b-8a3b-3f4b9a2c0004',
			'2026/01-02',
			new DateTimeImmutable('2026-02-01'),
			new DateTimeImmutable('2026-12-31'),
			$tariff,
			$musician,
		);
		$musician->addContracts([$contractOne, $contractTwo]);

		$musicianDtoFactory = new MusicianDtoFactory(
			new ContractDtoFactory(new TariffDtoFactory())
		);

		$musicianDto = $musicianDtoFactory->createFromMusician($musician);

		$I->assertIsArray($musicianDto->contracts);
		$I->assertCount(2, $musicianDto->contracts);
		$I->assertContainsOnlyInstancesOf(ContractDto::class, $musicianDto->contracts);
		$I->assertSame(
			[$contractOne->id, $contractTwo->id],
			array_map(static fn(ContractDto $dto) => $dto->id, $musicianDto->contracts)
		);
		$I->assertSame($tariff->id, $musicianDto->contracts[0]->tariff->id);
		// ACL: DTO собран из Person-собственного enum'а, а не App\Billing\Domain\Enum\TariffTypeEnum
		$I->assertInstanceOf(PersonTariffTypeEnum::class, $musicianDto->contracts[0]->tariff->type);
		$I->assertSame(PersonTariffTypeEnum::MONTHLY, $musicianDto->contracts[0]->tariff->type);
	}
}
