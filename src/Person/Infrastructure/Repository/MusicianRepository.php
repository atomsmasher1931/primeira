<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\Repository;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Musician|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musician|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musician[] findAll()
 * @method Musician[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicianRepository extends ServiceEntityRepository implements MusicianRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Musician::class);
	}


	public function getById(string $id): Musician
	{
		$musician = $this->find($id);

		if ($musician === null) {
			throw new MusicianNotFoundException();
		}

		return $musician;
	}

	/**
	 * @inheritDoc
	 */
	public function getByPhone(string $phone): Musician
	{
		$criteria = Criteria::create();
		$criteria->andWhere(Criteria::expr()?->eq('phone', $phone));

		$musician = $this->matching($criteria)->first();
		if ($musician === false) {
			throw new MusicianNotFoundException();
		}

		return $musician;
	}
}
