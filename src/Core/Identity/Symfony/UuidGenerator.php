<?php

declare(strict_types=1);

namespace App\Core\Identity\Symfony;

use App\Core\Identity\EntityIdGeneratorInterface;
use Symfony\Polyfill\Uuid\Uuid;

/**
 * Генератор GUUID
 */
class UuidGenerator implements EntityIdGeneratorInterface
{
	public function generate(): string
	{
		return (string)Uuid::uuid_create();
	}
}
