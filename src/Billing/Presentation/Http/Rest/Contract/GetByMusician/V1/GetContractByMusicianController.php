<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Contract\GetByMusician\V1;

use App\Billing\Application\UseCase\GetContractsByMusicianIdUseCase;
use App\Billing\Presentation\Http\Rest\Common\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\ContractDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class GetContractByMusicianController extends AbstractController
{
	public function __construct(
		private readonly GetContractsByMusicianIdUseCase $getContractsByMusicianIdUseCase,
		private readonly ContractDtoFactory $contractDtoFactory,
	) {
	}

	/**
	 * @return ContractDto[]
	 */
	#[Route(path: '/api/billing/v1/contract/musician/{musicianId}', requirements: ['musicianId' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function __invoke(string $musicianId): array
	{
		return $this->contractDtoFactory->createFromContracts($this->getContractsByMusicianIdUseCase->get($musicianId));
	}
}
