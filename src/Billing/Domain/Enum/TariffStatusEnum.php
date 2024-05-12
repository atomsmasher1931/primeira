<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

/**
 * Статус тарифа: активен, отменён
 */
enum TariffStatusEnum: int
{
	case ACTIVE = 1;

	case INACTIVE = 0;

	public static function values(): array
	{
		return array_map(static fn(TariffStatusEnum $value): int => $value->value, self::cases());
	}
}
