<?php

declare(strict_types=1);

namespace App\Core\Identity;

/**
 *
 */
interface UnitOfWorkInterface
{
	public function persist(object $persistingObject): void;

	public function flush(): void;
}
