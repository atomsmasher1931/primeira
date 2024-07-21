<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Repository;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Entity\Invoice;
use App\Billing\Domain\Enum\ContractStatusEnum;
use App\Billing\Domain\Enum\InvoiceStatusEnum;
use App\Billing\Domain\Repository\InvoiceRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[] findAll()
 * @method Invoice[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository implements InvoiceRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Invoice::class);
	}

	/**
	 * @inheritDoc
	 */
	public function getIssued(): array
	{
		return $this->findBy(['status' => InvoiceStatusEnum::ISSUED]);
	}

	/**
	 * @inheritDoc
	 */
	public function getSentToAcquire(): array
	{
		return $this->findBy(['status' => InvoiceStatusEnum::SENT_TO_ACQUIRE]);
	}

	/**
	 * @inheritDoc
	 */
	public function getReceived(): array
	{
		return $this->findBy(['status' => InvoiceStatusEnum::RECEIVED_BY_ACQUIRE]);
	}
}
