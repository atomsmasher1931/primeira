<?php

declare(strict_types=1);

namespace App\Core\UnitOfWork;

use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class UnitOfWork implements UnitOfWorkInterface
{

	/**
	 */
	public function __construct(private readonly EntityManagerInterface $entityManager)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function persist(object $persistingObject): void
	{
		try {
			$this->entityManager->persist($persistingObject);
		} catch (Throwable $exception) {
			throw new UnitOfWorkException($exception->getMessage(), $exception->getCode(), $exception);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function flush(): void
	{
		try {
			$this->entityManager->flush();
		} catch (Throwable $exception) {
			throw new UnitOfWorkException($exception->getMessage(), $exception->getCode(), $exception);
		}
	}
}
