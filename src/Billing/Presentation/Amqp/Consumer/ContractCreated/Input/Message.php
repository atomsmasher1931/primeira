<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Amqp\Consumer\ContractCreated\Input;

use App\Billing\Domain\Enum\ContractStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Message
{
	public function __construct(
		#[Assert\NotBlank()]
		#[Assert\Uuid]
		public string $id,
		#[Assert\NotBlank()]
		#[Assert\Type('string')]
		public string $number,
		#[Assert\NotBlank()]
		#[Assert\DateTime()]
		public string $startDate,
		#[Assert\NotBlank()]
		#[Assert\DateTime()]
		public string $finishDate,
		#[Assert\NotBlank()]
		#[Assert\Choice(callback: [ContractStatusEnum::class, 'values'])]
		public int $status,
		#[Assert\NotBlank()]
		#[Assert\Uuid]
		public string $tariffId,
		#[Assert\NotBlank()]
		#[Assert\Type('int')]
		public int $tariffValue,
		#[Assert\NotBlank()]
		#[Assert\Type('string')]
		public string $tariffType,
		#[Assert\NotBlank()]
		#[Assert\Type('string')]
		public string $tariffDegree,
		#[Assert\NotBlank()]
		#[Assert\Type('string')]
		public string $tariffStatus,
		#[Assert\NotBlank()]
		#[Assert\Uuid]
		public string $musicianId,
		#[Assert\NotBlank()]
		#[Assert\Email]
		public string $musicianEmail,
		#[Assert\NotBlank()]
		#[Assert\Type('string')]
		#[Assert\Length(11)]
		public string $musicianPhone,
	) {
	}
}
