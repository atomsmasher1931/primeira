<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Controller;

use App\Billing\Application\UseCase\GetTariffUseCase;
use App\Billing\Domain\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Контроллер тарифов
 */
#[Route(path: '/api/v1/billing/tariff')]
class TariffController extends AbstractController
{
	public function __construct(private readonly GetTariffUseCase $getTariffUseCase)
	{
	}

	#[Route(path: '/{id}', methods: ['GET'])]
	public function getById(string $id): Tariff
	{
		return $this->getTariffUseCase->getById($id);
	}

	/**
	 * @return Tariff[]
	 */
	#[Route(path: '/all', methods: ['GET'])]
	public function getAll(): array
	{
		return $this->getTariffUseCase->getAll();
	}
}
