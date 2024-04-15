<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

class GetMusicianByIdUseCase
{
	public function __construct(
		private readonly MusicianRepositoryInterface $musicianRepository,
		private readonly ContractRepositoryInterface $contractRepository,
	) {
	}

	/**
	 * @throws MusicianNotFoundException
	 */
	public function get(string $musicianId): Musician
	{
		$musician = $this->musicianRepository->getById($musicianId);
		$musician->addContracts($this->contractRepository->findByMusicianId($musicianId));

		return $musician;
	}
}
