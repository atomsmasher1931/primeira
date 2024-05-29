<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Tariff\GetActive\V1;

use App\Billing\Application\UseCase\GetActiveTariffUseCase;
use App\Billing\Presentation\Http\Rest\Common\Factory\TariffDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\TariffDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetActiveTariffsController extends AbstractController
{
	public function __construct(
		private readonly GetActiveTariffUseCase $activeTariffUseCase,
		private readonly TariffDtoFactory $tariffDtoFactory,
	) {
	}

	/**
	 * @return TariffDto[]
	 */
	#[Route(path: '/api/billing/v1/tariff/active', methods: ['GET'])]
	public function __invoke(): array
	{
		return $this->tariffDtoFactory->createFromTariffs($this->activeTariffUseCase->getActive());
	}
}
