<?php

declare(strict_types=1);

namespace App\Person\Domain\Enum;

/**
 * Роли пользователя
 */
enum RoleEnum: string
{
	/* управляет пользователями */
	case ADMIN = 'ROLE_ADMIN';

	/* создаёт тарифы */
	case TARIFF_MAKER = 'ROLE_TARIFF_MAKER';

	/* выставляет счета и следит за оплатами */
	case ACCOUNTER = 'ROLE_ACCOUNTER';

	/* может смотреть за активностями, без возможности исправления */
	case VIEWER = 'ROLE_VIEWER';

	/* Пустая роль, для аутентификатора симфони, не использовать, возможно выпилю */
	case USER = 'ROLE_USER';

	public static function values(): array
	{
		return array_map(static fn(RoleEnum $value): string => $value->value, self::cases());
	}
}
