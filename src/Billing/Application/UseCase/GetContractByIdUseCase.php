<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Exception\ContractNotFoundException;
use App\Billing\Domain\Repository\ContractRepositoryInterface;

final readonly class GetContractByIdUseCase
{
	public function __construct(private ContractRepositoryInterface $contractRepository)
	{
	}

	/**
	 * @throws ContractNotFoundException
	 */
	public function get(string $contractId): Contract
	{
		return $this->contractRepository->getById($contractId);
	}
}
