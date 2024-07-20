<?php

declare(strict_types=1);

namespace NotifierBundle\Domain\Enum;

enum EmailNotificationStatusEnum: int
{
	case CREATED = 0;
	case SENDED = 1;
	case ERROR = 2;
	case CANCELED = 3;
}
