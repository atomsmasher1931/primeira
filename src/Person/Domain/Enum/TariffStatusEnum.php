<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Статус тарифа — собственное представление контекста Person.
 *
 * Не переиспользует App\Billing\Domain\Enum\TariffStatusEnum напрямую (anti-corruption layer):
 * Person не должен зависеть от словаря Billing. Трансляция — в App\Person\Presentation\Http\Rest\Musician\Common\Gateway\BillingTariffGateway,
 * этот enum о существовании Billing не знает вообще.
 */
enum TariffStatusEnum: int
{
	case ACTIVE = 1;
	case INACTIVE = 0;

	public static function values(): array
	{
		return array_map(static fn(TariffStatusEnum $value): int => $value->value, self::cases());
	}

	public static function getName(TariffStatusEnum $value)
	{
		switch ($value) {
			case TariffStatusEnum::ACTIVE:
				return 'активен';
			case TariffStatusEnum::INACTIVE:
				return 'отключён';
		}
	}
}
