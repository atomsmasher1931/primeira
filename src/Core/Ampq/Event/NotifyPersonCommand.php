<?php

declare(strict_types=1);

namespace App\Core\Ampq\Event;

use App\Notifier\Domain\Enum\PersonTypeEnum;

final readonly class NotifyPersonCommand
{
	public function __construct(
		public string $personId,
		public PersonTypeEnum $personType,
		public string $email,
		public string $topic,
		public string $text,
	) {
	}
}
