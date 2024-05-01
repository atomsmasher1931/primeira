<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Input;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

readonly class MusicianCreateData
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
		#[Assert\Choice(callback: [PersonStatusEnum::class, 'values'])]
		public int $status,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [PersonDegreeEnum::class, 'values'])]
		public int $degree,
		#[Assert\NotBlank()]
		#[Assert\Email]
		public string $email,
		#[Assert\NotBlank()]
		#[Assert\Length(11)]
		public string $phone,
		#[Assert\NotBlank()]
		#[Assert\Url()]
		public string $telegram,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url(),
		])]
		public ?string $instagram,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url(),
		])]
		public ?string $facebook,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url(),
		])]
		public ?string $VK = null,
	) {
	}
}
