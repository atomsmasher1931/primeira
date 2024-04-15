<?php

declare(strict_types=1);

namespace App\Core\Identity\Symfony;

use App\Core\Identity\UnitOfWorkInterface;
use Doctrine\ORM\EntityManagerInterface;

class UnitOfWork implements UnitOfWorkInterface
{

	/**
	 */
	public function __construct(private readonly EntityManagerInterface $entityManager)
	{
	}

	public function persist(object $persistingObject): void
	{
		$this->entityManager->persist($persistingObject);
	}

	public function flush(): void
	{
		$this->entityManager->flush();
	}
}
