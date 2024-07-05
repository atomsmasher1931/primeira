<?php

declare(strict_types=1);

namespace App\Notifier\Application\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Notifier\Application\Dto\CreateEmailNotificationDto;
use App\Notifier\Domain\Entity\EmailNotification;
use App\Notifier\Domain\Exception\EmailNotificationCreateException;
use Throwable;

final readonly class CreateEmailNotificationUseCase
{
	public function __construct(
		private EntityIdGeneratorInterface $idGenerator,
		private UnitOfWorkInterface $unitOfWork,
	) {
	}

	public function __invoke(CreateEmailNotificationDto $dto): EmailNotification
	{
		try {
			$emailNotification = EmailNotification::create(
				$this->idGenerator->generate(),
				$dto->personId,
				$dto->personType,
				$dto->email,
				$dto->topic,
				$dto->text,
			);

			$this->unitOfWork->persist($emailNotification);
			$this->unitOfWork->flush();

		} catch (UuidGeneratorException $exception) {
			throw new EmailNotificationCreateException($exception, 'Ошибка генерации GUID email-извещения.');
		} catch (UnitOfWorkException $exception) {
			throw new EmailNotificationCreateException($exception, 'Ошибка сохранения email-извещения.');
		} catch (Throwable $exception) {
			throw new EmailNotificationCreateException($exception);
		}

		return $emailNotification;
	}
}
