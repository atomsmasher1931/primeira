<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\Repository;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method Musician|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musician|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musician[] findAll()
 * @method Musician[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicianRepository extends EntityRepository implements MusicianRepositoryInterface
{
}
