<?php

declare(strict_types=1);

namespace App\Core\Exception;

use DomainException;
use Throwable;

class NotFoundException extends DomainException implements Throwable
{
	public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
