<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Tariff\GetById\V1;

use App\Billing\Application\UseCase\GetTariffUseCase;
use App\Billing\Presentation\Http\Rest\Common\Factory\TariffDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\TariffDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetTariffByIdController extends AbstractController
{
	public function __construct(
		private readonly GetTariffUseCase $getTariffUseCase,
		private readonly TariffDtoFactory $tariffDtoFactory,
	) {
	}

	#[Route(name: 'tariff_get_by_id', path: '/api/billing/v1/tariff/{id}', methods: ['GET'])]
	public function __invoke(string $id): TariffDto
	{
		return $this->tariffDtoFactory->createFromTariff($this->getTariffUseCase->getById($id));
	}
}
