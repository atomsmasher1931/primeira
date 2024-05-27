<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Update\V1;

use App\Person\Application\Dto\PatchMusicianDto;
use App\Person\Application\UseCase\UpdateMusicianUseCase;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Presentation\Http\Rest\Musician\Update\V1\Input\MusicianPatchData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class PatchMusicianController extends AbstractController
{
	public function __construct(private readonly UpdateMusicianUseCase $updateMusicianUseCase)
	{
	}

	/**
	 * @throws \App\Core\UnitOfWork\UnitOfWorkException
	 */
	#[Route(path: '/api/person/v1/musician/{id}', methods: ['PATCH'])]
	public function __invoke(#[MapQueryString] MusicianPatchData $musicianUpdateData, string $id): Musician
	{
		return $this->updateMusicianUseCase->patchMusician(
			$id,
			new PatchMusicianDto(
				$musicianUpdateData->lastName,
				$musicianUpdateData->firstName,
				$musicianUpdateData->patronymic,
				$musicianUpdateData->status !== null ? PersonStatusEnum::tryFrom($musicianUpdateData->status) : null,
				$musicianUpdateData->degree !== null ? PersonDegreeEnum::tryFrom($musicianUpdateData->degree) : null,
				$musicianUpdateData->email,
				$musicianUpdateData->phone,
				$musicianUpdateData->telegram,
				$musicianUpdateData->instagram,
				$musicianUpdateData->facebook,
				$musicianUpdateData->VK,
			)
		);
	}
}
