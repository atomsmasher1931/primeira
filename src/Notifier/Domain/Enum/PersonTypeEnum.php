<?php

declare(strict_types=1);

namespace App\Notifier\Domain\Enum;

enum PersonTypeEnum: int
{
	case MUSICIAN = 1;
	case EMPLOYEE = 2;

	public static function values(): array
	{
		return array_map(static fn(PersonTypeEnum $value): int => $value->value, self::cases());
	}
}
