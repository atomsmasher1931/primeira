<?php

declare(strict_types=1);

namespace App\Core\PasswordHasher;

use App\Person\Domain\Entity\Employee;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
	public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
	{
	}

	public function hash(Employee $employee, string $password): string
	{
		return $this->userPasswordHasher->hashPassword($employee, $password);
	}
}
