<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Delete\V1;

use App\Person\Application\UseCase\DeleteMusicianUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DeleteMusicianController extends AbstractController
{
	public function __construct(private readonly DeleteMusicianUseCase $deleteMusicianUseCase)
	{
	}

	/**
	 * @throws \App\Core\UnitOfWork\UnitOfWorkException
	 */
	#[Route(path: '/api/person/v1/musician/{id}', methods: ['DELETE'])]
	public function __invoke(string $id): void
	{
		$this->deleteMusicianUseCase->delete($id);
	}
}
