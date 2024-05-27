<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\GetById\V1;

use App\Person\Application\UseCase\GetMusicianByIdUseCase;
use App\Person\Domain\Entity\Musician;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetMusicianByIdController extends AbstractController
{
	public function __construct(private readonly GetMusicianByIdUseCase $getMusicianByIdUseCase)
	{
	}

	#[Route(path: '/api/person/v1/musician/{id}', requirements: ['id' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function __invoke(string $id): Musician
	{
		return $this->getMusicianByIdUseCase->get($id);
	}
}
