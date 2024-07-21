<?php

declare(strict_types=1);

namespace App\Billing\Domain\Repository;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Exception\ContractNotFoundException;

interface ContractRepositoryInterface
{
	/**
	 * @throws ContractNotFoundException
	 */
	public function getById(string $id): Contract;

	/**
	 * @return Contract[]
	 * @throws ContractNotFoundException
	 */
	public function getByMusicianId(string $musicianId): array;

	/**
	 * @throws ContractNotFoundException
	 */
	public function getActiveByMusicianId(string $musicianId): Contract;

	/**
	 * @return Contract[]
	 */
	public function findByMusicianId(string $musicianId): array;

	/**
	 * @return Contract[]
	 */
	public function getActiveContracts(): array;
}
