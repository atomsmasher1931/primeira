<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Controller;

use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Person\Application\UseCase\CreateMusicianUseCase;
use App\Person\Application\UseCase\GetMusicianByIdUseCase;
use App\Person\Application\UseCase\GetMusicianByPhoneUseCase;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use App\Person\Presentation\Http\Rest\V1\Input\MusicianCreateData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

/**
 * Контроллер работы с музыкантами
 */
#[Route(path: '/api/v1/person')]
class PersonController extends AbstractController
{
	private array $errorResponseContent = ['error' => 'Непредвиденная ошибка'];

	public function __construct(
		private readonly CreateMusicianUseCase $createMusicianUseCase,
		private readonly GetMusicianByIdUseCase $getMusicianByIdUseCase,
		private readonly GetMusicianByPhoneUseCase $getMusicianByPhoneUseCase,
	) {
	}

	#[Route(path: '/create', methods: ['POST'])]
	public function create(#[MapRequestPayload] MusicianCreateData $musicianCreateData): Musician
	{
		return  $this->createMusicianUseCase->create(
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
}
