<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\V1\Input;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

readonly class MusicianPatchData
{
	public function __construct(
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Length(min: 2, max: 250)
		])]
		public ?string $lastName,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Length(min: 2, max: 250)
		])]
		public ?string $firstName,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Length(min: 2, max: 250)
		])]
		public ?string $patronymic,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Choice(callback: [PersonStatusEnum::class, 'values'])
		])]
		public ?int $status,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Choice(callback: [PersonDegreeEnum::class, 'values'])
		])]
		public ?int $degree,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Email(),
		])]
		public ?string $email,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Length(11)
		])]
		public ?string $phone,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url()
		])]
		public ?string $telegram,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url()
		])]
		public ?string $instagram,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url()
		])]
		public ?string $facebook,
		#[Assert\AtLeastOneOf([
			new Assert\Blank(),
			new Assert\Url()
		])]
		public ?string $VK,
	) {
	}
}
