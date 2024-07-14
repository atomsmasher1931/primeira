<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\Repository;

use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeNotFoundException;
use App\Person\Domain\Repository\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @method Employee|null find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array|null $orderBy = null)
 * @method Employee[] findAll()
 * @method Employee[] findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
	public function __construct(ManagerRegistry  $registry)
	{
		parent::__construct($registry, Employee::class);
	}

	/**
	 * @inheritDoc
	 */
	public function getByLogin(string $login): Employee
	{
		$employees = $this->findBy(['login' => $login]);

		if (count($employees) === 0) {
			throw new EmployeeNotFoundException();
		}

		return current($employees);
	}

	/**
	 * @inheritDoc
	 */
	public function getById(string $id): Employee
	{
		$employee = $this->find($id);

		if ($employee === null) {
			throw new EmployeeNotFoundException();
		}

		return $employee;
	}
}
