<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Output;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;

readonly class MusicianDto
{

	/**
	 * @param ContractDto[]    $contracts
	 */
	public function __construct(
		public string $id,
		public string $lastName,
		public string $firstName,
		public string $patronymic,
		public PersonStatusEnum $status,
		public PersonDegreeEnum $degree,
		public string $phone,
		public string $email,
		public string $telegram,
		public ?string $instagram = null,
		public ?string $facebook = null,
		public ?string $VK = null,
		public array $contracts,
	) {
	}
}
