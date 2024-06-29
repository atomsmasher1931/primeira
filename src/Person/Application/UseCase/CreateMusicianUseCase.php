<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Person\Application\Dto\CreateMusicianDto;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Exception\MusicianCreateException;
use Throwable;

readonly final class CreateMusicianUseCase
{
	public function __construct(
		private EntityIdGeneratorInterface $idGenerator,
		private UnitOfWorkInterface $unitOfWork,
	) {
	}

	/**
	 * @throws MusicianCreateException
	 */
	public function create(CreateMusicianDto $createMusicianDto): Musician {
		try {
			$musician = Musician::create(
				$this->idGenerator->generate(),
				$createMusicianDto->lastName,
				$createMusicianDto->firstName,
				$createMusicianDto->patronymic,
				$createMusicianDto->status,
				$createMusicianDto->degree,
				$createMusicianDto->notifier,
				$createMusicianDto->phone,
				$createMusicianDto->email,
				$createMusicianDto->telegram,
				$createMusicianDto->instagram,
				$createMusicianDto->facebook,
				$createMusicianDto->VK,
			);

			$this->unitOfWork->persist($musician);
			$this->unitOfWork->flush();

		} catch (UnitOfWorkException $exception) {
			throw new MusicianCreateException($exception, 'Ошибка при сохранении музыканта.');

		} catch (UuidGeneratorException $exception) {
			throw new MusicianCreateException($exception, 'Ошибка генерации ID музыканта.');

		} catch (Throwable $exception) {
			throw new MusicianCreateException($exception);
		}

		return $musician;
	}
}
