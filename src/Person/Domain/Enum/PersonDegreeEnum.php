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

	public static function values(): array
	{
		return array_map(static fn(PersonDegreeEnum $value): int => $value->value, self::cases());
	}
}
