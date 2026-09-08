<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Тип тарифа — собственное представление контекста Person.
 *
 * Не переиспользует App\Billing\Domain\Enum\TariffTypeEnum напрямую (anti-corruption layer):
 * Person не должен зависеть от словаря Billing. Трансляция — в App\Person\Presentation\Http\Rest\Musician\Common\Gateway\BillingTariffGateway,
 * этот enum о существовании Billing не знает вообще.
 */
enum TariffTypeEnum: int
{
	case FREE = 0;
	case CHILDISH = 1;
	case STUDENT = 2;
	case ADULT = 3;
	case SINGLE = 4;
	case WEEKLY = 5;
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
				return 'Тариф Элитный, для опытных, оплата за месяц безлимитных посещений';
			case TariffTypeEnum::SINGLE:
				return 'Тариф Однократный, для новичков, оплата за каждое занятие по факту посещения';
			case TariffTypeEnum::WEEKLY:
				return 'Тариф Неделя, для новичков, оплата за месяц, занятие раз в неделю, 4 раза за месяц';
			case TariffTypeEnum::MONTHLY:
				return 'Тариф Месяц, для новичков, оплата за месяц, занятие 2 раза в неделю 8-9 в месяц';
		}
	}
}
