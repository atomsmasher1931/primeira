<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\V1\Controller;

use App\Person\Application\UseCase\CreateMusicianUseCase;
use App\Person\Application\UseCase\DeleteMusicianUseCase;
use App\Person\Application\UseCase\GetMusicianByIdUseCase;
use App\Person\Application\UseCase\GetMusicianByPhoneUseCase;
use App\Person\Application\UseCase\UpdateMusicianUseCase;
use App\Person\Domain\Entity\Musician;
use App\Person\Presentation\Http\Rest\Musician\Create\V1\Input\MusicianCreateData;
use App\Person\Presentation\Http\Rest\Musician\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\V1\Input\MusicianPatchData;
use App\Person\Presentation\Http\Rest\Musician\V1\Input\MusicianPutData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Контроллер работы с музыкантами
 */
#[Route(path: '/api/person/v1/musician')]
class MusicianController extends AbstractController
{
	public function __construct(
		//private readonly CreateMusicianUseCase $createMusicianUseCase,
		private readonly GetMusicianByIdUseCase $getMusicianByIdUseCase,
		private readonly GetMusicianByPhoneUseCase $getMusicianByPhoneUseCase,
		private readonly MusicianDtoFactory $musicianDtoFactory,
		private readonly UpdateMusicianUseCase $updateMusicianUseCase,
		private readonly DeleteMusicianUseCase $deleteMusicianUseCase,
	) {
	}

	//#[Route(path: '/create', methods: ['POST'])]
	//public function create(#[MapRequestPayload] MusicianCreateData $musicianCreateData): Musician
	//{
	//	return $this->createMusicianUseCase->create(
	//		$this->musicianDtoFactory->createFromCreateData($musicianCreateData),
	//	);
	//}

	#[Route(path: '/{id}', requirements: ['id' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function getById(string $id): Musician
	{
		return $this->getMusicianByIdUseCase->get($id);
	}

	#[Route(path: '/phone/{phone}', methods: ['GET'])]
	public function getByPhone(string $phone): Musician
	{
		return $this->getMusicianByPhoneUseCase->get($phone);
	}

	#[Route(path: '/{id}', methods: ['PATCH'])]
	public function patchUserById(#[MapQueryString] MusicianPatchData $musicianUpdateData, string $id): Musician
	{
		return $this->updateMusicianUseCase->patchMusician(
			$id,
			$this->musicianDtoFactory->createFromPatchData($musicianUpdateData)
		);
	}

	#[Route(path: '/{id}', methods: ['PUT'])]
	public function putUserById(#[MapRequestPayload] MusicianPutData $musicianUpdateData, string $id): Musician
	{
		return $this->updateMusicianUseCase->updateMusician(
			$id,
			$this->musicianDtoFactory->createFromPutData($musicianUpdateData)
		);
	}

	#[Route(path: '/{id}', methods: ['DELETE'])]
	public function deleteById(string $id): bool
	{
		$this->deleteMusicianUseCase->delete($id);

		return true;
	}
}
