<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

/**
 * Статус музыканта — собственное представление контекста Billing.
 *
 * Не переиспользует App\Person\Domain\Enum\PersonStatusEnum напрямую (anti-corruption layer):
 * Billing не должен зависеть от словаря Person. Трансляция — в App\Billing\Presentation\Http\Rest\Common\Gateway\PersonMusicianGateway,
 * этот enum о существовании Person не знает вообще.
 */
enum MusicianStatusEnum: int
{
	case FIRED = -1;
	case ON_PAUSE = 0;
	case ACTIVE = 1;
}
