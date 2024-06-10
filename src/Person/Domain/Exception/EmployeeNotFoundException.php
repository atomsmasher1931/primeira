<?php

declare(strict_types=1);

namespace App\Person\Domain\Exception;

use App\Core\Exception\NotFoundException;

class EmployeeNotFoundException extends NotFoundException
{
	public function __construct()
	{
		parent::__construct('Пользователь не найден');
	}
}
