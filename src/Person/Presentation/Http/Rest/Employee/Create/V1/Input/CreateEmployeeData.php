<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Create\V1\Input;

use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Enum\RoleEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateEmployeeData
{
	public function __construct(
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $lastName,
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $firstName,
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $patronymic,
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $login,
		#[Assert\NotBlank()]
		#[Assert\Length(min: 2, max: 250)]
		#[Assert\Type('string')]
		public string $passwords,
		#[Assert\NotBlank()]
		#[Assert\Type('array')]
		#[Assert\Count(min: 1, max: 5)]
		#[Assert\All(
			constraints: [
				new Assert\NotBlank(),
				new Assert\Choice(callback: [RoleEnum::class, 'values'])
			]
		)]
		public array $roles,
		#[Assert\NotBlank()]
		#[Assert\Email]
		public string $email,
		#[Assert\NotBlank()]
		#[Assert\Length(11)]
		public string $phone,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [PersonStatusEnum::class, 'values'])]
		public int $status,
	) {
	}
}
