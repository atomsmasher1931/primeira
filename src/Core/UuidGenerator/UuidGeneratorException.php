<?php

declare(strict_types=1);

namespace App\Core\UuidGenerator;

use Exception;
use Throwable;

class UuidGeneratorException extends Exception implements Throwable
{
	public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
