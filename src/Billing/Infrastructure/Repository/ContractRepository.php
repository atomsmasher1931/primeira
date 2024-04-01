<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Repository;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Repository\ContractRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[] findAll()
 * @method Contract[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends EntityRepository implements ContractRepositoryInterface
{
}
