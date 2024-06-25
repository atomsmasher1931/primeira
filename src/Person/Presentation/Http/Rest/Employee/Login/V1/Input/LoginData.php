<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Login\V1\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginData
{
	public function __construct(
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $login,
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $password,
	) {
	}
}
