<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Repository\TariffRepositoryInterface;

final readonly class GetTariffUseCase
{
	public function __construct(private TariffRepositoryInterface $tariffRepository)
	{
	}

	public function getById(string $tariffId): Tariff
	{
		return $this->tariffRepository->getById($tariffId);
	}

	/**
	 * @return Tariff[]
	 */
	public function getAll(): array
	{
		return $this->tariffRepository->getAll();
	}
}
