<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Factory;

use App\Person\Application\Dto\CreateMusicianDto;
use App\Person\Application\Dto\PatchMusicianDto;
use App\Person\Application\Dto\UpdateMusicianDto;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Presentation\Http\Rest\Musician\Create\V1\Input\MusicianCreateData;
use App\Person\Presentation\Http\Rest\Musician\V1\Input\MusicianPatchData;
use App\Person\Presentation\Http\Rest\Musician\V1\Input\MusicianPutData;
use App\Person\Presentation\Http\Rest\Musician\V1\Output\MusicianDto;

class MusicianDtoFactory
{
	public function __construct(private readonly ContractDtoFactory $contractDtoFactory)
	{
	}

	public function createFromMusician(Musician $musician): MusicianDto
	{
		return new MusicianDto(
			$musician->getId(),
			$musician->getLastName(),
			$musician->getFirstName(),
			$musician->getPatronymic(),
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

	public function createFromCreateData(MusicianCreateData $musicianCreateData): CreateMusicianDto
	{
		return new CreateMusicianDto(
			$musicianCreateData->lastName,
			$musicianCreateData->firstName,
			$musicianCreateData->patronymic,
			PersonStatusEnum::tryFrom($musicianCreateData->status),
			PersonDegreeEnum::tryFrom($musicianCreateData->degree),
			$musicianCreateData->phone,
			$musicianCreateData->email,
			$musicianCreateData->telegram,
			$musicianCreateData->instagram,
			$musicianCreateData->facebook,
			$musicianCreateData->VK,
		);
	}

	public function createFromPatchData(MusicianPatchData $musicianPatchData): PatchMusicianDto
	{
		return new PatchMusicianDto(
			$musicianPatchData->lastName,
			$musicianPatchData->firstName,
			$musicianPatchData->patronymic,
			$musicianPatchData->status !== null ? PersonStatusEnum::tryFrom($musicianPatchData->status) : null,
			$musicianPatchData->degree !== null ? PersonDegreeEnum::tryFrom($musicianPatchData->degree) : null,
			$musicianPatchData->email,
			$musicianPatchData->phone,
			$musicianPatchData->telegram,
			$musicianPatchData->instagram,
			$musicianPatchData->facebook,
			$musicianPatchData->VK,
		);
	}

	public function createFromPutData(MusicianPutData $musicianPutData): UpdateMusicianDto
	{
		return new UpdateMusicianDto(
			$musicianPutData->lastName,
			$musicianPutData->firstName,
			$musicianPutData->patronymic,
			PersonStatusEnum::tryFrom($musicianPutData->status),
			PersonDegreeEnum::tryFrom($musicianPutData->degree),
			$musicianPutData->phone,
			$musicianPutData->email,
			$musicianPutData->telegram,
			$musicianPutData->instagram,
			$musicianPutData->facebook,
			$musicianPutData->VK,
		);
	}
}
