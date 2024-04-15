<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Output;

use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;

class MusicianDto
{

	/**
	 * @param string           $id
	 * @param string           $lastName
	 * @param string           $firstName
	 * @param string           $patronymic
	 * @param PersonStatusEnum $status
	 * @param PersonDegreeEnum $degree
	 * @param string           $phone
	 * @param string           $email
	 * @param string           $telegram
	 * @param string|null      $instagram
	 * @param string|null      $facebook
	 * @param string|null      $VK
	 * @param ContractDto[]    $contracts
	 */
	public function __construct(
		public readonly string $id,
		public readonly string $lastName,
		public readonly string $firstName,
		public readonly string $patronymic,
		public readonly PersonStatusEnum $status,
		public readonly PersonDegreeEnum $degree,
		public readonly string $phone,
		public readonly string $email,
		public readonly string $telegram,
		public readonly ?string $instagram = null,
		public readonly ?string $facebook = null,
		public readonly ?string $VK = null,
		public readonly array $contracts,
	) {
	}
}
