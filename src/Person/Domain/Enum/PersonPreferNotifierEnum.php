<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Предпочитаемый нотификатор пользователя
 */
enum PersonPreferNotifierEnum: string
{
	case EMAIL = 'email';

	public static function values(): array
	{
		return array_map(static fn(PersonPreferNotifierEnum $value): string => $value->value, self::cases());
	}
}
