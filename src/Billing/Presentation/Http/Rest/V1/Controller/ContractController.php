<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Controller;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use App\Core\Exception\NotFoundException;
use App\Core\Identity\EntityIdGeneratorInterface;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;
use DateTimeImmutable;

/**
 * Контроллер договоров
 */
#[Route(path: '/api/v1/billing/contract')]
class ContractController extends AbstractController
{
	private array $errorResponseContent = ['error' => 'Непредвиденная ошибка'];

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly EntityIdGeneratorInterface $idGenerator,
		private readonly ContractRepositoryInterface $contractRepository,
		private readonly MusicianRepositoryInterface $musicianRepository,
		private readonly TariffRepositoryInterface $tariffRepository,
	) {
	}

	#[Route(path: '/create', methods: ['POST'])]
	public function create(Request $request): Response
	{
		$startDate = new DateTimeImmutable($request->request->get('startDate'));
		$finishDate = new DateTimeImmutable($request->request->get('finishDate'));

		try {
			$contract = Contract::create(
				$this->idGenerator->generate(),
				Contract::generateNumber((int)$startDate->format('Y'), 1, 2),
				$startDate,
				$finishDate,
				$this->tariffRepository->getById($request->request->get('tariff')),
				$this->musicianRepository->getById($request->request->get('musician')),
			);
			$this->entityManager->persist($contract);
			$this->entityManager->flush();

			$responseContent = $contract->toArray();
			$httpCode = Response::HTTP_OK;

		} catch (NotFoundException $exception) {
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

	#[Route(path: '/{id}', requirements: ['id' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function getById(string $id): Response
	{
		try {
			$contract = $this->contractRepository->getById($id);
			$responseContent = $contract->toArray();
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

	#[Route(path: '/musician/{musicianId}', requirements: ['musicianId' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function getByMusician(Request $request, string $musicianId): Response
	{
		$onlyActive = (bool)$request->query->get('active');

		try {
			if ($onlyActive) {
				$contracts = [$this->contractRepository->getActiveByMusicianId($musicianId)];
			} else {
				$contracts = $this->contractRepository->getByMusicianId($musicianId);
			}

			$responseContent =  array_map(static fn(Contract $contract) => $contract->toArray(), $contracts);
			$httpCode = Response::HTTP_OK;

		} catch (MusicianNotFoundException $exception) {
			$this->errorResponseContent['error'] = $exception->getMessage();
			$responseContent = $this->errorResponseContent;
			$httpCode = Response::HTTP_NOT_FOUND;

		}
		catch (Throwable $exception) {
			$this->errorResponseContent['error'] = $exception->getMessage();
			$responseContent = $this->errorResponseContent;
			$httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($responseContent, $httpCode);
	}
}
