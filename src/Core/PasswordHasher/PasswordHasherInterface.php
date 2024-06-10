<?php

declare(strict_types=1);

namespace App\Core\PasswordHasher;

use App\Person\Domain\Entity\Employee;

interface PasswordHasherInterface
{
	public function hash(Employee $employee, string $password): string;
}
