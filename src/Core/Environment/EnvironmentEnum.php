<?php

declare(strict_types=1);

namespace App\Core\Environment;

enum EnvironmentEnum: string
{
	case PROD = 'prod';
	case DEV = 'dev';
	case TEST = 'test';

	public function isProduction(): bool
	{
		return $this->value === self::PROD->value;
	}

	public function isDevelopment(): bool
	{
		return $this->value === self::DEV->value;
	}

	public function isTest(): bool
	{
		return $this->value === self::TEST->value;
	}
}
