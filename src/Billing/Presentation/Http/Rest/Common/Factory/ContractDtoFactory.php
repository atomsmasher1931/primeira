<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Factory;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Presentation\Http\Rest\Common\Output\ContractDto;

readonly class ContractDtoFactory
{
	public function __construct(
		private TariffDtoFactory $tariffDtoFactory,
		private MusicianDtoFactory $musicianDtoFactory
	) {
	}

	public function createFromContract(Contract $contract): ContractDto
	{
		return new ContractDto(
			$contract->id,
			$contract->number,
			$contract->getStartDate(),
			$contract->getFinishDate(),
			$this->tariffDtoFactory->createFromTariff($contract->getTariff()),
			$this->musicianDtoFactory->createFromMusician($contract->getMusician()),
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
			$contractsDto[] = $this->createFromContract($contract);
		}

		return $contractsDto;
	}
}
