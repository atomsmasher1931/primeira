<?php

declare(strict_types=1);

namespace App\Core\UuidGenerator;

/**
 * Генератор ID для сущности
 */
interface EntityIdGeneratorInterface
{
	/**
	 * @throws UuidGeneratorException
	 */
	public function generate(): string;
}
