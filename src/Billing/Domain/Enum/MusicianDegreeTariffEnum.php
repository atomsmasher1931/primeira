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

	public static function getName(MusicianDegreeTariffEnum $value)
	{
		switch ($value) {
			case MusicianDegreeTariffEnum::FOR_NEWBIE:
				return 'для новичка';
			case MusicianDegreeTariffEnum::FOR_EXPERIENCED:
				return 'для опытного';
		}
	}
}
