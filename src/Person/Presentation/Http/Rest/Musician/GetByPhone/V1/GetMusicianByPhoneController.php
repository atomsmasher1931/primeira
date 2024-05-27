<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\GetByPhone\V1;

use App\Person\Application\UseCase\GetMusicianByPhoneUseCase;
use App\Person\Domain\Entity\Musician;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetMusicianByPhoneController extends AbstractController
{
	public function __construct(private readonly GetMusicianByPhoneUseCase $getMusicianByPhoneUseCase)
	{
	}

	#[Route(path: '/api/person/v1/musician/phone/{phone}', methods: ['GET'])]
	public function __invoke(string $phone): Musician
	{
		return $this->getMusicianByPhoneUseCase->get($phone);
	}
}
