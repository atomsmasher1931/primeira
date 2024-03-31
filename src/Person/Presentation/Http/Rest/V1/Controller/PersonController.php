<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Controller;

use App\Core\Identity\EntityIdGeneratorInterface;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Polyfill\Uuid\Uuid;

/**
 * Контроллер работы с музыкантами
 */
#[Route(path: '/api/v1/person')]
class PersonController extends AbstractController
{
	private const RESULT_APP = ['result' => 'success'];

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly EntityIdGeneratorInterface $idGenerator
	) {
	}

	#[Route(path: '/create', methods: ['POST'])]
	public function create(Request $request): Response
	{
		$musician = Musician::create(
			$this->idGenerator->generate(),
			$request->request->get('lastName'),
			$request->request->get('firstName'),
			$request->request->get('patronymic'),
			PersonStatusEnum::ACTIVE,
			PersonDegreeEnum::NEWBIE,
			trim($request->request->get('phone')),
			$request->request->get('email'),
			$request->request->get('telegram'),
		);
		$this->entityManager->persist($musician);
		$this->entityManager->flush();

		return $this->json(['musician' => $musician->id]);
	}

	#[Route(path: '/{id}', methods: ['GET'])]
	public function getById(string $id): Response
	{
		$musician = $this->entityManager->find(Musician::class, $id);

		return $this->json(
			[
				'startedAt' => $musician->firstName,
				'finishedAt' => $musician->lastName
			]
		);
	}
}
