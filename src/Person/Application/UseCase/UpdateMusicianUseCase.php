<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Person\Application\Dto\PatchMusicianDto;
use App\Person\Application\Dto\UpdateMusicianDto;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

readonly final class UpdateMusicianUseCase
{
	public function __construct(
		private MusicianRepositoryInterface $musicianRepository,
		private UnitOfWorkInterface $unitOfWork)
	{
	}

	/**
	 * @throws UnitOfWorkException
	 */
	public function patchMusician(string $id, PatchMusicianDto $musicianDto): Musician
	{
		$musician = $this->musicianRepository->getById($id);

		$musician->patch(
			$musicianDto->lastName,
			$musicianDto->firstName,
			$musicianDto->patronymic,
			$musicianDto->status,
			$musicianDto->degree,
			$musicianDto->phone,
			$musicianDto->email,
			$musicianDto->telegram,
			$musicianDto->instagram,
			$musicianDto->facebook,
			$musicianDto->VK,
		);

		$this->unitOfWork->persist($musician);
		$this->unitOfWork->flush();

		return $musician;
	}

	/**
	 * @throws UnitOfWorkException
	 */
	public function updateMusician(string $id, UpdateMusicianDto $musicianDto): Musician
	{
		$musician = $this->musicianRepository->getById($id);

		$musician->update(
			$musicianDto->lastName,
			$musicianDto->firstName,
			$musicianDto->patronymic,
			$musicianDto->status,
			$musicianDto->degree,
			$musicianDto->phone,
			$musicianDto->email,
			$musicianDto->telegram,
			$musicianDto->instagram,
			$musicianDto->facebook,
			$musicianDto->VK,
		);

		$this->unitOfWork->persist($musician);
		$this->unitOfWork->flush();

		return $musician;

	}
}
