<?php

declare(strict_types=1);

namespace App\Person\Domain\Exception;

use DomainException;

class EmployeeWrongPasswordException extends DomainException
{
	public function __construct()
	{
		parent::__construct('Неверный пароль');
	}
}
