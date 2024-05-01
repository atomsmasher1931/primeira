<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Controller;

use App\Billing\Application\UseCase\CreateContractUseCase;
use App\Billing\Application\UseCase\GetContractByIdUseCase;
use App\Billing\Application\UseCase\GetContractsByMusicianIdUseCase;
use App\Billing\Domain\Entity\Contract;
use App\Billing\Presentation\Http\Rest\V1\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\V1\Input\ContractCreateData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Контроллер договоров
 */
#[Route(path: '/api/v1/billing/contract')]
class ContractController extends AbstractController
{
	public function __construct(
		private readonly ContractDtoFactory $contractDtoFactory,
		private readonly CreateContractUseCase $createContractUseCase,
		private readonly GetContractByIdUseCase $getContractUseCase,
		private readonly GetContractsByMusicianIdUseCase $getContractsByMusicianIdUseCase,
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
	public function getById(string $id): Contract
	{
		return $this->getContractUseCase->get($id);
	}

	/**
	 * @return Contract[]
	 */
	#[Route(path: '/musician/{musicianId}', requirements: ['musicianId' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function getByMusician(string $musicianId): array
	{
		return $this->getContractsByMusicianIdUseCase->get($musicianId);
	}
}
