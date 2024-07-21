<?php

declare(strict_types=1);

namespace App\Billing\Domain\Exception;

use Throwable;
use DomainException;

class InvoiceManageException extends DomainException
{
	public function __construct(Throwable $previous = null, ?string $message = null)
	{
		parent::__construct($message ?? 'Ошибка создания счёта.', 0, $previous);
	}
}
