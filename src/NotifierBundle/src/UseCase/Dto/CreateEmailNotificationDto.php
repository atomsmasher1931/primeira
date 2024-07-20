<?php

declare(strict_types=1);

namespace NotifierBundle\UseCase\Dto;

use NotifierBundle\Enum\PersonTypeEnum;

class CreateEmailNotificationDto
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
