<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Repository;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Exception\TariffNotFoundException;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tariff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tariff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tariff[] findAll()
 * @method Tariff[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariffRepository extends ServiceEntityRepository implements TariffRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Tariff::class);
	}

	/**
	 * @inheritDoc
	 */
	public function getById(string $id): Tariff
	{
		$tariff = $this->find($id);
		if ($tariff === null) {
			throw new TariffNotFoundException();
		}

		return $tariff;
	}
}
