<?php

declare(strict_types=1);

namespace App\Billing\Domain\Exception;

use DomainException;
use Throwable;

class ContractManageException extends DomainException
{
	public function __construct(Throwable $previous = null, ?string $message = null)
	{
		parent::__construct($message ?? 'Ошибка создания контракта.', 0, $previous);
	}
}
