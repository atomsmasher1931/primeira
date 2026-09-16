<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianContractsProviderInterface;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

readonly final class GetMusicianByIdUseCase
{
	public function __construct(
		private MusicianRepositoryInterface $musicianRepository,
		private MusicianContractsProviderInterface $musicianContractsProvider,
	) {
	}

	/**
	 * @throws MusicianNotFoundException
	 */
	public function get(string $musicianId): Musician
	{
		$musician = $this->musicianRepository->getById($musicianId);
		$musician->addContracts($this->musicianContractsProvider->findByMusicianId($musicianId));

		return $musician;
	}
}
