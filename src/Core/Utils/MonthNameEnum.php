<?php

declare(strict_types=1);

namespace App\Core\Utils;

enum MonthNameEnum: string
{
	case JANUARY = 'январь';
	case FEBRUARY = 'февраль';
	case MARCH = 'март';
	case APRIL = 'апрель';
	case MAY = 'май';
	case JUNE = 'июнь';
	case JULY = 'июль';
	case AUGUST = 'август';
	case SEPTEMBER = 'сентябрь';
	case OCTOBER = 'октябрь';
	case NOVEMBER = 'ноябрь';
	case DECEMBER = 'декабрь';

	private const NUMBER_MAPPING = [
		1 => self::JANUARY,
		2 => self::FEBRUARY,
		3 => self::MARCH,
		4 => self::APRIL,
		5 => self::MAY,
		6 => self::JUNE,
		7 => self::JULY,
		8 => self::AUGUST,
		9 => self::SEPTEMBER,
		10 => self::OCTOBER,
		11 => self::NOVEMBER,
		12 => self::DECEMBER,
	];

	public static function values(): array
	{
		return array_map(static fn(MonthNameEnum $value): string => $value->value, self::cases());
	}

	public static function getByNumber(int $number): string
	{
		return self::NUMBER_MAPPING[$number]->value;
	}
}
