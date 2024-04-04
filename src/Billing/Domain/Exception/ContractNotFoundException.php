<?php

declare(strict_types=1);

namespace App\Billing\Domain\Exception;

use App\Core\Exception\NotFoundException;
use Throwable;

class ContractNotFoundException extends NotFoundException
{
	public function __construct()
	{
		parent::__construct('Договор не найден');
	}
}
