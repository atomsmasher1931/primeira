<?php

declare(strict_types=1);

namespace App\Billing\Domain\Enum;

enum InvoiceStatusEnum: int
{
	case ISSUED = 1;
	case SENT_TO_ACQUIRE = 2;
	case RECEIVED_BY_ACQUIRE = 3;
	case PAID = 4;
	case EXPIRED = 5;

	public static function values(): array
	{
		return array_map(static fn(InvoiceStatusEnum $value): int => $value->value, self::cases());
	}
}
