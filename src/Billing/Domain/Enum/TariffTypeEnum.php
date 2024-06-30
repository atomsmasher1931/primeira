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

	public static function getName(TariffTypeEnum $value): string
	{
		switch ($value) {
			case TariffTypeEnum::FREE:
				return 'Тариф Бесплатный, для опытных, распространяется на месяц';
			case TariffTypeEnum::CHILDISH:
				return 'Тариф Детский, для опытных, оплата за месяц безлимитных посещений, скидка максимальная';
			case TariffTypeEnum::STUDENT:
				return 'Тариф Студенческий, для опытных, оплата за месяц безлимитных посещений, скидка';
			case TariffTypeEnum::ADULT:
				return 'Тариф Элитный, для опытных, оплата за месяц белимитных посещений';
			case TariffTypeEnum::SINGLE:
				return 'Тариф Однократный, для новичков, оплата за каждое занятие по факту посещения';
			case TariffTypeEnum::WEEKLY:
				return 'Тариф Неделя, для новичков, оплата за месяц, занятие раз в неделю, 4 раза за месяц';
			case TariffTypeEnum::MONTHLY:
				return 'Тариф Месяц, для новичков, оплата за месяц, занятие 2 раза в неделю 8-9 в месяц';
		}
	}
}
