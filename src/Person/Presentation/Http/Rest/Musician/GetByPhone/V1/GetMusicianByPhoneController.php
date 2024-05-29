<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\GetByPhone\V1;

use App\Person\Application\UseCase\GetMusicianByPhoneUseCase;
use App\Person\Presentation\Http\Rest\Musician\Common\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\Common\Output\MusicianDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetMusicianByPhoneController extends AbstractController
{
	public function __construct(
		private readonly GetMusicianByPhoneUseCase $getMusicianByPhoneUseCase,
		private readonly MusicianDtoFactory $musicianDtoFactory,
	) {
	}

	#[Route(path: '/api/person/v1/musician/phone/{phone}', methods: ['GET'])]
	public function __invoke(string $phone): MusicianDto
	{
		return $this->musicianDtoFactory->createFromMusician($this->getMusicianByPhoneUseCase->get($phone));
	}
}
