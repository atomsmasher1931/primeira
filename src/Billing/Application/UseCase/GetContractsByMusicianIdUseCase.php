<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Exception\ContractNotFoundException;
use App\Billing\Domain\Repository\ContractRepositoryInterface;

class GetContractsByMusicianIdUseCase
{
	public function __construct(private ContractRepositoryInterface $contractRepository)
	{
	}

	/**
	 * @return Contract[]
	 * @throws ContractNotFoundException
	 */
	public function get(string $musicianId): array
	{
		return $this->contractRepository->getByMusicianId($musicianId);
	}
}
