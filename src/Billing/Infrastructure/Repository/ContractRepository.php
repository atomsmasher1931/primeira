<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Repository;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Enum\ContractStatusEnum;
use App\Billing\Domain\Exception\ContractNotFoundException;
use App\Billing\Domain\Exception\TooManyActiveContractsByMusicianException;
use App\Billing\Domain\Repository\ContractRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[] findAll()
 * @method Contract[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository implements ContractRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Contract::class);
	}

	/**
	 * @throws ContractNotFoundException
	 */
	public function getById(string $id): Contract
	{
		$contract = $this->find($id);
		if ($contract === null) {
			throw new ContractNotFoundException();
		}

		return $contract;
	}

	/**
	 * @return Contract
	 * @throws ContractNotFoundException
	 */
	public function getActiveByMusicianId(string $musicianId): Contract
	{
		$queryBuilder = $this->getEntityManager()->createQueryBuilder();
		$queryBuilder->select('c')
			->from(Contract::class, 'c')
			->andWhere($queryBuilder->expr()->eq('c.musician', ':musicianId'))
			->andWhere($queryBuilder->expr()->eq('c.status', ':status'))
			->setParameter('musicianId', $musicianId)
			->setParameter('status', ContractStatusEnum::ACTIVE);

		$contracts = $queryBuilder->getQuery()->getResult();

		if ($contracts === []) {
			throw new ContractNotFoundException();
		}

		if (count($contracts) > 1) {
			throw new TooManyActiveContractsByMusicianException($musicianId, count($contracts));
		}

		return current($contracts);
	}

	/**
	 * @return Contract[]
	 * @throws ContractNotFoundException
	 */
	public function getByMusicianId(string $musicianId): array
	{
		$contracts = $this->findByMusicianId($musicianId);
		if ($contracts === []) {
			throw new ContractNotFoundException();
		}

		return $contracts;
	}

	/**
	 * @return Contract[]
	 */
	public function findByMusicianId(string $musicianId): array
	{
		return $this->findBy(['musician' => $musicianId]);
	}

	public function countByYear($year)
	{
		//TODO здесь сделать запрос, который считает договоры по году начала
		// ниже сделать метод, который считает договоры за год по одном пользователю
		// номер договора <ГОД>/<Порядковый номер>-<Количество у пользователя за год>, например 2024/01-01
	}
}
