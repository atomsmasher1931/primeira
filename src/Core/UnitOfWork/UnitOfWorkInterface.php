<?php

declare(strict_types=1);

namespace App\Core\UnitOfWork;

/**
 *
 */
interface UnitOfWorkInterface
{
	/**
	 * @throws UnitOfWorkException
	 */
	public function persist(object $persistingObject): void;

	/**
	 * @throws UnitOfWorkException
	 */
	public function flush(): void;
}
