<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Factory;

use App\Billing\Application\Dto\UpdateTariffDto;
use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Billing\Presentation\Http\Rest\Common\Output\TariffDto;
use App\Billing\Presentation\Http\Rest\Tariff\Create\V1\Input\CreateTariffData;

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
}
