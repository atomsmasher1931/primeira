<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Common\Factory;

use App\Billing\Domain\Entity\Tariff;
use App\Person\Presentation\Http\Rest\Musician\Output\TariffDto;

/**
 *
 */
class TariffDtoFactory
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
}
