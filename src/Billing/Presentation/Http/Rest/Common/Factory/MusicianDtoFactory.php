<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Factory;

use App\Billing\Domain\Enum\MusicianDegreeEnum;
use App\Billing\Domain\Enum\MusicianStatusEnum;
use App\Billing\Presentation\Http\Rest\Common\Output\MusicianDto;
use App\Person\Domain\Entity\Musician;

/**
 * Транслирует Person\Domain\Entity\Musician (чужой контекст) в собственное представление Billing.
 * Enum'ы Person (App\Person\Domain\Enum\*) не импортируются — берётся только ->value (backing value)
 * и по нему конструируется свой enum через Enum::from(). Если Person уберёт/переномерует значение,
 * от которого зависит Billing, from() бросит \ValueError — тихого рассогласования не будет.
 */
readonly class MusicianDtoFactory
{
	public function createFromMusician(Musician $musician): MusicianDto
	{
		return new MusicianDto(
			$musician->getId(),
			$musician->getLastName(),
			$musician->getFirstName(),
			$musician->getPatronymic(),
			MusicianStatusEnum::from($musician->getStatus()->value),
			MusicianDegreeEnum::from($musician->getDegree()->value),
		);
	}
}
