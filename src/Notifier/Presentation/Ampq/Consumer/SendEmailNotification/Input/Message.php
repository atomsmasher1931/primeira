<?php

declare(strict_types=1);

namespace App\Notifier\Presentation\Ampq\Consumer\SendEmailNotification\Input;

use App\Core\Ampq\Event\MessageInterface;
use App\Notifier\Domain\Enum\PersonTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Message implements MessageInterface
{
	public function __construct(
		#[Assert\Uuid]
		public string $personId,
		#[Assert\Choice(callback: [PersonTypeEnum::class, 'values'])]
		public int $personType,
		#[Assert\Email]
		public string $email,
		#[Assert\Type('string')]
		#[Assert\Length(min: 7, max: 1024)]
		public string $topic,
		#[Assert\Type('string')]
		#[Assert\Length(min: 10, max: 4000)]
		public string $text,
	) {
	}
}
