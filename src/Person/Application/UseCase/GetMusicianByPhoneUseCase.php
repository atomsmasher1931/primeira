<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

class GetMusicianByPhoneUseCase
{
	public function __construct(
		private readonly MusicianRepositoryInterface $musicianRepository,
		private readonly ContractRepositoryInterface $contractRepository,
	) {
	}

	/**
	 * @throws MusicianNotFoundException
	 */
	public function get(string $phone): Musician
	{
		$musician = $this->musicianRepository->getByPhone($phone);
		$musician->addContracts($this->contractRepository->findByMusicianId($musician->id));

		return $musician;
	}
}
