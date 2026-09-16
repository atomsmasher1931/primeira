<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Common\Factory;

use App\Billing\Domain\Entity\Tariff;
use App\Person\Domain\Enum\MusicianDegreeTariffEnum;
use App\Person\Domain\Enum\TariffStatusEnum;
use App\Person\Domain\Enum\TariffTypeEnum;
use App\Person\Presentation\Http\Rest\Musician\Common\Output\TariffDto;

/**
 * Транслирует Billing\Domain\Entity\Tariff (чужой контекст) в собственное представление Person.
 * Enum'ы Billing (App\Billing\Domain\Enum\*) не импортируются — берётся только ->value (backing value)
 * и по нему конструируется свой enum через Enum::from(). Если Billing уберёт/переномерует значение,
 * от которого зависит Person, from() бросит \ValueError — тихого рассогласования не будет.
 */
readonly class TariffDtoFactory
{
	public function createFromTariff(Tariff $tariff): TariffDto
	{
		return new TariffDto(
			$tariff->id,
			MusicianDegreeTariffEnum::from($tariff->musicianDegree->value),
			TariffTypeEnum::from($tariff->type->value),
			$tariff->value,
			$tariff->getStartDate(),
			$tariff->getFinishDate(),
			TariffStatusEnum::from($tariff->getStatus()->value),
		);
	}
}
