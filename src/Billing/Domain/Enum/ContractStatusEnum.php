<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

/**
 * Статус договора: активен, завершён, на паузе, аннулирован
 */
enum ContractStatusEnum: int
{
	/** Неактивен, с таким статусом договор создаётся */
	case INACTIVE = 0;

	/** Активен, договор действует */
	case ACTIVE = 1;
	/** Договор завершён, всё хорошо */
	case FINISHED = 2;
	/** Договор на паузе, счета не выставляем */
	case ON_PAUSE = 3;
	/** Договор отменён, обычно раньше времени */
	case CANCELED = 4;
}
