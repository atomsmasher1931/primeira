<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

/**
 * Тип тарифа
 */
enum TariffTypeEnum: int
{
	/** @const int Бесплатно, для опытных и бесценных, распространяется на месяц */
	case FREE = 0;

	/** @const int Для опытных, тариф ДЕТСКИЙ, оплата за месяц, скидка максимальная, для очень ценных */
	case CHILDISH = 1;

	/** @const int Для опытных, тариф СТУДЕНЧЕСКИЙ, оплата за месяц, берём побольше, но всё равно обнять и плакать */
	case STUDENT = 2;

	/** @cont int Для опытных, тариф ВЗРОСЛЫЙ, оплата за месяц, это элита школы */
	case ADULT = 3;

	/** @cont int Для новичков, тариф ОДНОКРАТНЫЙ, берём после каждого занятия за один раз */
	case SINGLE = 4;

	/** @const int Для новичков, тариф Раз В НЕДЕЛЮ, 4 занятия за месяц, берём сразу */
	case WEEKLY = 5;

	/** @const int Для новичков, тариф ЗА МЕСЯЦ, 8-9 занятий в месяц, берём сразу */
	case MONTHLY = 6;

	public static function values(): array
	{
		return array_map(static fn(TariffTypeEnum $value): int => $value->value, self::cases());
	}
}
