<?php

declare(strict_types=1);

namespace App\Person\Domain\Exception;

use DomainException;
use Throwable;

class MusicianCreateException extends DomainException
{
	public function __construct(Throwable $previous = null, ?string $message = null)
	{
		parent::__construct($message ?? 'Ошибка создания музыканта', 0, $previous);
	}
}
