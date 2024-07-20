<?php

declare(strict_types=1);

namespace NotifierBundle\Enum;

enum EmailNotificationStatusEnum: int
{
	case CREATED = 0;
	case SENDED = 1;
	case ERROR = 2;
	case CANCELED = 3;
}
