<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Repository\TariffRepositoryInterface;

/**
 *
 */
final readonly class GetActiveTariffUseCase
{
	public function __construct(private TariffRepositoryInterface $tariffRepository)
	{
	}

	/**
	 * @return Tariff[]
	 */
	public function getActive(): array
	{
		return $this->tariffRepository->getByStatus(TariffStatusEnum::ACTIVE->value);
	}
}
