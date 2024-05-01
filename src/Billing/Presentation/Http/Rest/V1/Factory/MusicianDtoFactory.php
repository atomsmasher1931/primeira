<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Factory;

use App\Person\Domain\Entity\Musician;
use App\Billing\Presentation\Http\Rest\V1\Output\MusicianDto;

readonly class MusicianDtoFactory
{
	public function createFromMusician(Musician $musician): MusicianDto
	{
		return new MusicianDto(
			$musician->getId(),
			$musician->getLastName(),
			$musician->getFirstName(),
			$musician->getPatronymic(),
			$musician->getStatus(),
			$musician->getDegree(),
		);
	}
}
