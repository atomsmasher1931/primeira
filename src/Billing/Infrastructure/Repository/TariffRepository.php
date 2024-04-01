<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Repository;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method Tariff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tariff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tariff[] findAll()
 * @method Tariff[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariffRepository extends EntityRepository implements TariffRepositoryInterface
{
}
