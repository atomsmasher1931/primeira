<?php

declare(strict_types=1);

namespace App\Tests\Functional\Person\Repository;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use App\Tests\Support\FunctionalTester;

/**
 * @covers \App\Person\Domain\Repository\MusicianRepositoryInterface
 */
class MusicianRepositoryCest
{
	/** @covers MusicianRepositoryInterface::getByPhone */
	public function testGetByPhoneSuccess(FunctionalTester $I)
	{
		/** @var Musician $musician */
		$musician = $I->have(Musician::class);

		/** @var MusicianRepositoryInterface $repository */
		$repository = $I->grabService(MusicianRepositoryInterface::class);
		$musicianExpected = $repository->getByPhone($musician->getPhone());

		$I->assertEquals($musicianExpected, $musician);
	}

	/** @covers MusicianRepositoryInterface::getByPhone */
	public function testGetByPhoneFail(FunctionalTester $I)
	{
		/** @var Musician $musician */
		$musician = $I->have(Musician::class);

		/** @var MusicianRepositoryInterface $repository */
		$repository = $I->grabService(MusicianRepositoryInterface::class);

		$I->expectThrowable(
			MusicianNotFoundException::class,
			static fn() => $repository->getByPhone(str_replace('1', '2', $musician->getPhone()))
		);
	}

	/** @covers MusicianRepositoryInterface::getById */
	public function testGetByIdSuccess(FunctionalTester $I)
	{
		/** @var Musician $musician */
		$musician = $I->have(Musician::class);

		/** @var MusicianRepositoryInterface $repository */
		$repository = $I->grabService(MusicianRepositoryInterface::class);
		$musicianExpected = $repository->getById($musician->getId());

		$I->assertEquals($musicianExpected, $musician);
	}

	/** @covers MusicianRepositoryInterface::getById */
	public function testGetByIdFail(FunctionalTester $I)
	{
		/** @var Musician $musician */
		$musician = $I->have(Musician::class);

		/** @var MusicianRepositoryInterface $repository */
		$repository = $I->grabService(MusicianRepositoryInterface::class);

		$I->expectThrowable(
			MusicianNotFoundException::class,
			static fn() => $repository->getById(str_replace(['a', 'b'], ['c', 'd'], $musician->getId()))
		);
	}
}
