<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Factory;

use App\Billing\Domain\Entity\Contract;
use App\Person\Presentation\Http\Rest\V1\Output\ContractDto;

class ContractDtoFactory
{
	public function __construct(private readonly TariffDtoFactory $tariffDtoFactory)
	{
	}

	public function createFromContract(Contract $contract): ContractDto
	{
		return new ContractDto(
			$contract->id,
			$contract->number,
			$contract->getStartDate(),
			$contract->getFinishDate(),
			$this->tariffDtoFactory->createFromTariff($contract->getTariff()),
		);
	}

	/**
	 * @param Contract[] $contracts
	 *
	 * @return ContractDto[]
	 */
	public function createFromContracts(array $contracts): array
	{
		$contractsDto = [];
		foreach ($contracts as $contract) {
			$contractsDto = $this->createFromContract($contract);
		}

		return $contractsDto;
	}
}
