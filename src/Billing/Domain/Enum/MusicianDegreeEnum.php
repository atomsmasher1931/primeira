<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

/**
 * Уровень музыканта — собственное представление контекста Billing.
 *
 * Не переиспользует App\Person\Domain\Enum\PersonDegreeEnum напрямую (anti-corruption layer):
 * Billing не должен зависеть от словаря Person. Трансляция — в App\Billing\Presentation\Http\Rest\Common\Gateway\PersonMusicianGateway,
 * этот enum о существовании Person не знает вообще.
 */
enum MusicianDegreeEnum: int
{
	case NEWBIE = 1;
	case EXPERIENCED = 2;
}
