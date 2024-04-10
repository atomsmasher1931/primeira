<?php

declare(strict_types=1);

namespace App\Billing\Domain\Repository;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Exception\TariffNotFoundException;

interface TariffRepositoryInterface
{
	/**
	 * @throws TariffNotFoundException
	 */
	public function getById(string $id): Tariff;
}
