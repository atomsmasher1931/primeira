<?php

declare(strict_types=1);

namespace NotifierBundle\src\Domain\Exception;

use DomainException;
use Throwable;

class EmailNotificationCreateException extends DomainException
{
	public function __construct(Throwable $previous = null, ?string $message = null)
	{
		parent::__construct($message ?? 'Ошибка создания email-извещения ', 0, $previous);
	}
}
