<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Factory;

use App\Person\Domain\Entity\Musician;
use App\Person\Presentation\Http\Rest\V1\Output\MusicianDto;

class MusicianDtoFactory
{
	public function __construct(private readonly ContractDtoFactory $contractDtoFactory)
	{
	}

	public function createFromMusician(Musician $musician): MusicianDto
	{
		return new MusicianDto(
			$musician->id,
			$musician->lastName,
			$musician->firstName,
			$musician->patronymic,
			$musician->getStatus(),
			$musician->getDegree(),
			$musician->getPhone(),
			$musician->getEmail(),
			$musician->getTelegram(),
			$musician->getInstagram(),
			$musician->getFacebook(),
			$musician->getVK(),
			$this->contractDtoFactory->createFromContracts($musician->getContracts())
		);
	}

	/**
	 * @param Musician[] $musicians
	 *
	 * @return MusicianDto[]
	 */
	public function createFromMusicians(array $musicians): array
	{
		$musiciansDto = [];
		foreach ($musicians as $musician) {
			$musiciansDto[] = $this->createFromMusician($musician);
		}

		return $musiciansDto;
	}
}
