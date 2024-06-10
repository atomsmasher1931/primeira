<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Update\V1;

use App\Person\Application\Dto\UpdateMusicianDto;
use App\Person\Application\UseCase\UpdateMusicianUseCase;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Presentation\Http\Rest\Musician\Update\V1\Input\MusicianPutData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PutMusicianController extends AbstractController
{
	public function __construct(private readonly UpdateMusicianUseCase $updateMusicianUseCase)
	{
	}

	/**
	 * @throws \App\Core\UnitOfWork\UnitOfWorkException
	 */
	#[Route(path: '/api/person/v1/musician/{id}', name: 'musician_put', methods: ['PUT'])]
	public function __invoke(#[MapRequestPayload] MusicianPutData $musicianUpdateData, string $id): Musician
	{
		return $this->updateMusicianUseCase->updateMusician(
			$id,
			new UpdateMusicianDto(
				$musicianUpdateData->lastName,
				$musicianUpdateData->firstName,
				$musicianUpdateData->patronymic,
				PersonStatusEnum::tryFrom($musicianUpdateData->status),
				PersonDegreeEnum::tryFrom($musicianUpdateData->degree),
				$musicianUpdateData->phone,
				$musicianUpdateData->email,
				$musicianUpdateData->telegram,
				$musicianUpdateData->instagram,
				$musicianUpdateData->facebook,
				$musicianUpdateData->VK,
			)
		);
	}
}
