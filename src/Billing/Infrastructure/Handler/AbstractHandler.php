<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Handler;

use App\Core\Http\Rest\Response\SuccessResponse;

readonly abstract class AbstractHandler
{
	protected const CONTROLLERS_NAME = ['ПЕРЕОПРЕДЕЛИ КОНТРОЛЛЕР В ХЕНДЛЕРЕ'];

	public function isProcessable(string $controllerName): bool
	{
		foreach (static::CONTROLLERS_NAME as $handledControllerName) {
			if ($handledControllerName === $controllerName) {
				return true;
			}
		}

		return false;
	}

	abstract public function handle(mixed $payload): SuccessResponse;
}
