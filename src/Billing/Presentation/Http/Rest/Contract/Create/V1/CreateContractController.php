<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Contract\Create\V1;

use App\Billing\Application\Dto\CreateContractDto;
use App\Billing\Application\UseCase\CreateContractUseCase;
use App\Billing\Presentation\Http\Rest\Common\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\ContractDto;
use App\Billing\Presentation\Http\Rest\Contract\Create\V1\Input\ContractCreateData;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateContractController extends AbstractController
{
	public function __construct(
		private readonly CreateContractUseCase $createContractUseCase,
		private readonly ContractDtoFactory $contractDtoFactory,
	) {
	}

	#[Route(path: '/api/billing/v1/contract/create', methods: ['POST'])]
	public function __invoke(#[MapRequestPayload] ContractCreateData $contractCreateData): ContractDto
	{
		return $this->contractDtoFactory->createFromContract(
			$this->createContractUseCase->create(
				new CreateContractDto(
					$contractCreateData->musician,
					$contractCreateData->tariff,
					new DateTimeImmutable($contractCreateData->startDate),
					new DateTimeImmutable($contractCreateData->finishDate),
				)
			)
		);
	}
}
