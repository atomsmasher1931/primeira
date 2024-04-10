<?php

declare(strict_types=1);

namespace App\Core\Identity;

/**
 * Генератор ID для сущности
 */
interface EntityIdGeneratorInterface
{
	public function generate(): string;
}
