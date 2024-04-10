<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Controller;

use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Core\Identity\EntityIdGeneratorInterface;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
		private readonly EntityManagerInterface $entityManager,
		private readonly EntityIdGeneratorInterface $idGenerator,
		private readonly MusicianRepositoryInterface $repository,
		private readonly ContractRepositoryInterface $contractRepository,
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

	#[Route(path: '/{id}', requirements: ['id' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function getById(string $id): Response
	{
		try {
			$musician = $this->repository->getById($id);
			$musician->addContracts($this->contractRepository->findByMusicianId($id));
			$responseContent = $musician->toArray();
			$httpCode = Response::HTTP_OK;

		} catch (MusicianNotFoundException $exception) {
			$this->errorResponseContent['error'] = $exception->getMessage();
			$responseContent = $this->errorResponseContent;
			$httpCode = Response::HTTP_NOT_FOUND;

		} catch (Throwable $exception) {
			$this->errorResponseContent['error'] = $exception->getMessage();
			$responseContent = $this->errorResponseContent;
			$httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($responseContent, $httpCode);
	}

	#[Route(path: '/phone/{phone}', requirements: ['phone' => '\d{11,20}'], methods: ['GET'])]
	public function getByPhone(string $phone): Response
	{
		try {
			$musician = $this->repository->getByPhone($phone);
			$musician->addContracts($this->contractRepository->findByMusicianId($musician->id));
			$responseContent = $musician->toArray();
			$httpCode = Response::HTTP_OK;


		} catch (MusicianNotFoundException $exception) {
			$this->errorResponseContent['error'] = $exception->getMessage();
			$responseContent = $this->errorResponseContent;
			$httpCode = Response::HTTP_NOT_FOUND;

		} catch (Throwable $exception) {
			$this->errorResponseContent['error'] = $exception->getMessage();
			$responseContent = $this->errorResponseContent;
			$httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($responseContent, $httpCode);
	}
}
