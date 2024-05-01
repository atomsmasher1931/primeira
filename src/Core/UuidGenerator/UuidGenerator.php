<?php

declare(strict_types=1);

namespace App\Core\UuidGenerator;

use Symfony\Polyfill\Uuid\Uuid;
use Throwable;

class UuidGenerator implements EntityIdGeneratorInterface
{
	/**
	 * @inheritDoc
	 */
	public function generate(): string
	{
		try {
			return (string)Uuid::uuid_create();
		} catch (Throwable $exception) {
			throw new UuidGeneratorException($exception->getMessage(), $exception->getCode(), $exception);
		}
	}
}
