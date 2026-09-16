<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianContractsProviderInterface;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

readonly final class GetMusicianByPhoneUseCase
{
	public function __construct(
		private MusicianRepositoryInterface $musicianRepository,
		private MusicianContractsProviderInterface $musicianContractsProvider,
	) {
	}

	/**
	 * @throws MusicianNotFoundException
	 */
	public function get(string $phone): Musician
	{
		$musician = $this->musicianRepository->getByPhone($phone);
		$musician->addContracts($this->musicianContractsProvider->findByMusicianId($musician->getId()));

		return $musician;
	}
}
