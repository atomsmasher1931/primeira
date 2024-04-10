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
}
