<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Статус участника
 */
enum PersonStatusEnum: int
{
	case FIRED = -1;
	case ON_PAUSE = 0;
	case ACTIVE = 1;

	public static function values(): array
	{
		return array_map(static fn(PersonStatusEnum $value): int => $value->value, self::cases());
	}
}
