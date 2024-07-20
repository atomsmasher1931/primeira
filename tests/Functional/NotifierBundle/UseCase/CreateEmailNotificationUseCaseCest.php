<?php

declare(strict_types=1);

namespace App\Tests\Functional\NotifierBundle\UseCase;

use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Tests\Support\FunctionalTester;
use NotifierBundle\Entity\EmailNotification;
use NotifierBundle\Enum\EmailNotificationStatusEnum;
use NotifierBundle\Enum\PersonTypeEnum;
use NotifierBundle\UseCase\CreateEmailNotificationUseCase;
use NotifierBundle\UseCase\Dto\CreateEmailNotificationDto;
use Mockery;
use Mockery\MockInterface;

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

	public function test(FunctionalTester $I): void
	{
		/** @var EntityIdGeneratorInterface|MockInterface $generator */
		$generator = Mockery::mock(EntityIdGeneratorInterface::class);
		$generator->shouldReceive('generate')->andReturn(self::ID);

		$unitOfWork = $I->grabService(UnitOfWorkInterface::class);

		$useCase = new CreateEmailNotificationUseCase(
			$generator,
			$unitOfWork,
		);

		$emailNotification = $useCase(
			new CreateEmailNotificationDto(
				self::PERSON_ID,
				self::PERSON_TYPE,
				self::EMAIL,
				self::TOPIC,
				self::TEXT,
			)
		);
		$I->seeNumRecords(1, EmailNotification::class, ['id' => self::ID]);

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
}
