<?php

declare(strict_types=1);

namespace App\Billing\Domain\Exception;

use DomainException;
use Throwable;

class TariffManageException extends DomainException
{
	public function __construct(Throwable $previous = null, ?string $message = null)
	{
		parent::__construct($message ?? 'Ошибка обновления тарифа.', 0, $previous);
	}
}
