<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Create\V1;

use App\Person\Application\Dto\CreateMusicianDto;
use App\Person\Application\UseCase\CreateMusicianUseCase;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Presentation\Http\Rest\Musician\Common\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\Common\Output\MusicianDto;
use App\Person\Presentation\Http\Rest\Musician\Create\V1\Input\MusicianCreateData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateMusicianController extends AbstractController
{
	public function __construct(
		private readonly CreateMusicianUseCase $createMusicianUseCase,
		private readonly MusicianDtoFactory $musicianDtoFactory,
	) {
	}

	#[Route(path: '/api/person/v1/musician/create', name: 'musician_create', methods: ['POST'])]
	public function __invoke(#[MapRequestPayload] MusicianCreateData $musicianCreateData): MusicianDto
	{
		return $this->musicianDtoFactory->createFromMusician(
			$this->createMusicianUseCase->create(
				new CreateMusicianDto(
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
				)
			)
		);
	}
}
