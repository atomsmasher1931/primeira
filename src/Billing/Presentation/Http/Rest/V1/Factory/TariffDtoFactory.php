<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Factory;

use App\Billing\Application\Dto\UpdateTariffDto;
use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Billing\Presentation\Http\Rest\V1\Input\TariffManageDto;
use App\Billing\Presentation\Http\Rest\V1\Output\TariffDto;

/**
 *
 */
readonly class TariffDtoFactory
{
	public function createFromTariff(Tariff $tariff): TariffDto
	{
		return new TariffDto(
			$tariff->id,
			$tariff->musicianDegree,
			$tariff->type,
			$tariff->value,
			$tariff->getStartDate(),
			$tariff->getFinishDate(),
			$tariff->getStatus(),
		);
	}

	/**
	 * @param Tariff[] $tariffs
	 *
	 * @return TariffDto[]
	 */
	public function createFromTariffs(array $tariffs): array
	{
		$tariffsDto = [];
		foreach ($tariffs as $tariff) {
			$tariffsDto[] = $this->createFromTariff($tariff);
		}

		return $tariffsDto;
	}

	public function createFromTariffToForm(Tariff $tariff): TariffManageDto
	{
		return new TariffManageDto(
			$tariff->musicianDegree->value,
			$tariff->type->value,
			$tariff->value,
			$tariff->getStartDate(),
			$tariff->getFinishDate(),
			$tariff->getStatus()->value,
		);
	}

	public function createForUpdateTariff(TariffManageDto $tariffDto): UpdateTariffDto
	{
		return new UpdateTariffDto(
			MusicianDegreeTariffEnum::tryFrom($tariffDto->musicianDegree),
			TariffTypeEnum::tryFrom($tariffDto->type),
			$tariffDto->value,
			$tariffDto->startDate,
			$tariffDto->finishDate,
			TariffStatusEnum::tryFrom($tariffDto->status),
		);
	}
}
