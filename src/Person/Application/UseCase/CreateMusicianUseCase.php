<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\Identity\EntityIdGeneratorInterface;
use App\Core\Identity\UnitOfWorkInterface;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Presentation\Http\Rest\V1\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\V1\Output\MusicianDto;

class CreateMusicianUseCase
{
	public function __construct(
		private readonly EntityIdGeneratorInterface $idGenerator,
		private readonly UnitOfWorkInterface $unitOfWork,
	) {
	}

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

		return $musician;
	}
}
