<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Exception\MusicianCreateException;
use Throwable;

class CreateMusicianUseCase
{
	public function __construct(
		private readonly EntityIdGeneratorInterface $idGenerator,
		private readonly UnitOfWorkInterface $unitOfWork,
	) {
	}

	/**
	 * @throws MusicianCreateException
	 */
	public function create(
		string $lastName,
		string $firstName,
		string $patronymic,
		PersonStatusEnum $status,
		PersonDegreeEnum $degree,
		string $phone,
		string $email,
		string $telegram,
		?string $instagram = null,
		?string $facebook = null,
		?string $VK = null,
	): Musician {
		try {
			$musician = Musician::create(
				$this->idGenerator->generate(),
				$lastName,
				$firstName,
				$patronymic,
				$status,
				$degree,
				$phone,
				$email,
				$telegram,
				$instagram,
				$facebook,
				$VK,
			);

			$this->unitOfWork->persist($musician);
			$this->unitOfWork->flush();

		} catch (UnitOfWorkException $exception) {
			throw new MusicianCreateException($exception, 'Ошибка при сохранении музыканта.');

		} catch (UuidGeneratorException $exception) {
			throw new MusicianCreateException($exception, 'Ошибка генерации ID музыканта.');

		} catch (Throwable $exception) {
			throw new MusicianCreateException($exception, 'Ошибка при создании музыканта.');
		}

		return $musician;
	}
}
