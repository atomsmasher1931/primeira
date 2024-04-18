<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Handler;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Presentation\Http\Rest\V1\Controller\TariffController;
use App\Billing\Presentation\Http\Rest\V1\Factory\TariffDtoFactory;
use App\Billing\Presentation\Http\Rest\V1\Output\TariffDto;
use App\Billing\Presentation\Http\Rest\V1\Output\TariffsSuccessResponse;
use App\Billing\Presentation\Http\Rest\V1\Output\TariffSuccessResponse;
use App\Core\Http\Rest\Response\SuccessResponse;

/**
 * Обработчик событий, вылетевших из контроллера тарифов
 */
readonly class TariffHandler extends AbstractHandler
{
	protected const CONTROLLER_NAME = TariffController::class;

	public function __construct(private TariffDtoFactory $tariffDtoFactory)
	{
	}

	/**
	 * @param TariffDto[]|TariffDto $payload
	 */
	public function handle(mixed $payload): SuccessResponse
	{
		if (is_array($payload)) {
			$successResponse = new TariffsSuccessResponse($this->tariffDtoFactory->createFromTariffs($payload));
		} elseif ($payload instanceof Tariff) {
			$successResponse = new TariffSuccessResponse($this->tariffDtoFactory->createFromTariff($payload));
		} else {
			$successResponse = new SuccessResponse();
		}

		return $successResponse;
	}
}
