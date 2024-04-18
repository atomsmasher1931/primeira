<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Factory;

use App\Billing\Application\Dto\CreateContractDto;
use App\Billing\Domain\Entity\Contract;
use App\Billing\Presentation\Http\Rest\V1\Input\ContractCreateData;
use App\Billing\Presentation\Http\Rest\V1\Output\ContractDto;
use DateTimeImmutable;

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
			$contractsDto = $this->createFromContract($contract);
		}

		return $contractsDto;
	}

	/**
	 * @throws \Exception
	 */
	public function createFromCreateData(ContractCreateData $contractCreateData): CreateContractDto
	{
		return new CreateContractDto(
			$contractCreateData->musician,
			$contractCreateData->tariff,
			new DateTimeImmutable($contractCreateData->startDate),
			new DateTimeImmutable($contractCreateData->finishDate),
		);
	}
}
