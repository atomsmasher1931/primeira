<?php
/**
 *
 * User: Sergey Efimov
 * Date: 31.03.2024
 * Time: 10:52
 */

namespace App\Person\Infrastructure\Repository;

use App\Person\Domain\Entity\Musician;
use Doctrine\ORM\EntityRepository;

/**
 * @method Musician|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musician|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musician[] findAll()
 * @method Musician[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicianRepository extends EntityRepository
{
}
