<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Handler;

use App\Billing\Presentation\Http\Rest\V1\Controller\TariffController;
use App\Billing\Presentation\Http\Rest\V1\Output\TariffDto;
use App\Core\Http\Rest\Response\SuccessResponse;

/**
 * Обработчик событий, вылетевших из контроллера тарифов
 */
readonly class TariffHandler extends AbstractHandler
{
	protected const CONTROLLER_NAME = TariffController::class;

	/**
	 * @param TariffDto[]|TariffDto $payload
	 */
	public function handle(mixed $payload): SuccessResponse
	{
		return new SuccessResponse();
	}
}
