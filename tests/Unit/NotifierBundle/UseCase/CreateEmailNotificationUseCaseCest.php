<?php

declare(strict_types=1);

namespace App\Tests\Unit\NotifierBundle\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Tests\Support\UnitTester;
use Codeception\Attribute\DataProvider;
use Mockery;
use Mockery\MockInterface;
use NotifierBundle\Enum\EmailNotificationStatusEnum;
use NotifierBundle\Enum\PersonTypeEnum;
use NotifierBundle\Exception\EmailNotificationCreateException;
use NotifierBundle\UseCase\CreateEmailNotificationUseCase;
use NotifierBundle\UseCase\Dto\CreateEmailNotificationDto;

/**
 * @covers \NotifierBundle\UseCase\CreateEmailNotificationUseCase
 */
class CreateEmailNotificationUseCaseCest
{
	private const ID = '47a7dfef-44b0-46a3-8aa1-1bc119559954';
	private const PERSON_ID = '8ff80ec7-357b-4441-a2a9-afa4231b3df9';
	private const PERSON_TYPE = PersonTypeEnum::MUSICIAN;
	private const EMAIL = 'vsidorov@primeira.ru';
	private const TOPIC = 'Тема письма';
	private const TEXT = 'Текст письма';
	private const STATUS = EmailNotificationStatusEnum::CREATED;

	public function testSuccess(UnitTester $I): void
	{
		$generator = Mockery::mock(EntityIdGeneratorInterface::class);
		$generator->shouldReceive('generate')->once()->andReturn(self::ID);
		$unitOfWork = Mockery::mock(UnitOfWorkInterface::class);
		$unitOfWork->shouldReceive('persist')->once();
		$unitOfWork->shouldReceive('flush')->once();

		$emailNotification = (new CreateEmailNotificationUseCase($generator, $unitOfWork))($this->getDto());

		$I->assertSame(
			[
				'id' => self::ID,
				'person_id' => self::PERSON_ID,
				'person_type' => self::PERSON_TYPE,
				'email' => self::EMAIL,
				'topic' => self::TOPIC,
				'text' => self::TEXT,
				'status' => self::STATUS,
			],
			[
				'id' => $emailNotification->getId(),
				'person_id' => $emailNotification->getPersonId(),
				'person_type' => $emailNotification->getPersonType(),
				'email' => $emailNotification->getEmail(),
				'topic' => $emailNotification->getTopic(),
				'text' => $emailNotification->getText(),
				'status' => $emailNotification->getStatus(),
			]
		);
	}

	public function testIdGeneratorFail(UnitTester $I): void
	{
		$generator = Mockery::mock(EntityIdGeneratorInterface::class);
		$generator->shouldReceive('generate')->andThrow(UuidGeneratorException::class);
		$unitOfWork = Mockery::mock(UnitOfWorkInterface::class);
		$unitOfWork->shouldNotReceive('persist');
		$unitOfWork->shouldNotReceive('flush');
		$dto = $this->getDto();

		$I->expectThrowable(
			EmailNotificationCreateException::class,
			static fn() => (new CreateEmailNotificationUseCase($generator, $unitOfWork))($dto));
	}

	public function testUnitOfWorkFail(UnitTester $I): void
	{
		$generator = Mockery::mock(EntityIdGeneratorInterface::class);
		$generator->shouldReceive('generate')->once()->andReturn(self::ID);
		$unitOfWork = Mockery::mock(UnitOfWorkInterface::class);
		$unitOfWork->shouldReceive('persist')->once();
		$unitOfWork->shouldNotReceive('flush')->andThrow(UnitOfWorkException::class);
		$dto = $this->getDto();

		$I->expectThrowable(
			EmailNotificationCreateException::class,
			static fn() => (new CreateEmailNotificationUseCase($generator, $unitOfWork))($dto));
	}

	private function getDto(): CreateEmailNotificationDto
	{
		return new CreateEmailNotificationDto(
			self::PERSON_ID,
			self::PERSON_TYPE,
			self::EMAIL,
			self::TOPIC,
			self::TEXT,
		);
	}
}
