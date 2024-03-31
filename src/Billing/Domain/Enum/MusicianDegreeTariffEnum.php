<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

/**
 * Уровень музыканта: новичок, опытный
 */
enum MusicianDegreeTariffEnum: int
{
	case FOR_NEWBIE = 1;
	case FOR_EXPERIENCED = 2;
}
