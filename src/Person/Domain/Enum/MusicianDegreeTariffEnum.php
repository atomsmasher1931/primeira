<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Уровень музыканта, для которого действует тариф — собственное представление контекста Person.
 *
 * Не переиспользует App\Billing\Domain\Enum\MusicianDegreeTariffEnum напрямую (anti-corruption layer):
 * Person не должен зависеть от словаря Billing. Трансляция — в App\Person\Presentation\Http\Rest\Musician\Common\Gateway\BillingTariffGateway,
 * этот enum о существовании Billing не знает вообще.
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
