<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Controller;

use App\Billing\Application\UseCase\CreateContractUseCase;
use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use App\Billing\Presentation\Http\Rest\V1\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\V1\Input\ContractCreateData;
use App\Core\Exception\NotFoundException;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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
		private readonly ContractDtoFactory $contractDtoFactory,
		private readonly CreateContractUseCase $createContractUseCase,
		private readonly ContractRepositoryInterface $contractRepository,
	) {
	}

	#[Route(path: '/create', methods: ['POST'])]
	public function create(#[MapRequestPayload] ContractCreateData $contractCreateData): Contract
	{
		return $this->createContractUseCase->create(
			$this->contractDtoFactory->createFromCreateData($contractCreateData)
		);
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
