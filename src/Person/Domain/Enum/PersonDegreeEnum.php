<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Уровень музыканта: новичок, опытный
 */
enum PersonDegreeEnum: int
{
	case NEWBIE = 1;
	case EXPERIENCED = 2;
}
