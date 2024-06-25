<?php

declare(strict_types=1);

namespace App\Core\Security\TokenGenerator;

use App\Person\Domain\Entity\Employee;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

final readonly class JwtTokenGenerator
{
	public function __construct(
		private JWTEncoderInterface $jwtEncoder,
		private int $tokenTTL,
	) {
	}

	public function __invoke(Employee $employee): string
	{
		$tokenData = [
			'username' => $employee->getUserIdentifier(),
			'roles' => $employee->getRoles(),
			'exp' => time() + $this->tokenTTL,
		];

		return $this->jwtEncoder->encode($tokenData);
	}
}
