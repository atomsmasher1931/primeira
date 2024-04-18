<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Handler;

use App\Core\Http\Rest\Response\SuccessResponse;

readonly abstract class AbstractHandler
{
	protected const CONTROLLER_NAME = 'ПЕРЕОПРЕДЕЛИ КОНТРОЛЛЕР В ХЕНДЛЕРЕ';

	public function isProcessable(string $controllerName): bool
	{
		return static::CONTROLLER_NAME === $controllerName;
	}

	abstract public function handle(mixed $payload): SuccessResponse;
}
