<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

readonly final class DeleteMusicianUseCase
{
	public function __construct(
		private MusicianRepositoryInterface $musicianRepository,
		private UnitOfWorkInterface $unitOfWork
	) {
	}

	/**
	 * @throws UnitOfWorkException
	 */
	public function delete(string $id): void
	{
		$this->unitOfWork->remove($this->musicianRepository->getById($id));
		$this->unitOfWork->flush();
	}
}
