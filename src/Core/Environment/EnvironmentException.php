<?php

declare(strict_types=1);

namespace App\Core\Environment;

use Exception;
use Throwable;

class EnvironmentException extends Exception implements Throwable
{
	public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
