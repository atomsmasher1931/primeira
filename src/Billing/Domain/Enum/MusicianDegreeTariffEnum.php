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

	public static function values(): array
	{
		return array_map(static fn(MusicianDegreeTariffEnum $value): int => $value->value, self::cases());
	}
}
