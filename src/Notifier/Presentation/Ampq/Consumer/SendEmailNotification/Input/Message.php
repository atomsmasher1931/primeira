<?php

declare(strict_types=1);

namespace App\Notifier\Presentation\Ampq\Consumer\SendEmailNotification\Input;

use App\Notifier\Domain\Enum\PersonTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Message
{
	public function __construct(
		#[Assert\Type('string')]
		#[Assert\Length(36)]
		public string $personId,
		#[Assert\Choice(callback: [PersonTypeEnum::class, 'values'])]
		public int $personType,
		#[Assert\Email]
		public string $email,
		#[Assert\Type('string')]
		#[Assert\Length(512)]
		public string $topic,
		#[Assert\Type('string')]
		#[Assert\Length(1024)]
		public string $text,
	) {
	}
}
