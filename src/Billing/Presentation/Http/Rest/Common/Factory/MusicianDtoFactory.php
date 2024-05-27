<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Factory;

use App\Billing\Presentation\Http\Rest\Common\Output\MusicianDto;
use App\Person\Domain\Entity\Musician;

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
