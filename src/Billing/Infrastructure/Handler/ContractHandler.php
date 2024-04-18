<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Handler;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Presentation\Http\Rest\V1\Controller\ContractController;
use App\Billing\Presentation\Http\Rest\V1\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\V1\Output\ContractDto;
use App\Billing\Presentation\Http\Rest\V1\Output\ContractsSuccessResponse;
use App\Billing\Presentation\Http\Rest\V1\Output\ContractSuccessResponse;
use App\Core\Http\Rest\Response\SuccessResponse;

/**
 * Обработчик вылетевших из контроллера контрактов
 */
readonly class ContractHandler extends AbstractHandler
{
	protected const CONTROLLER_NAME = ContractController::class;

	public function __construct(private readonly ContractDtoFactory $contractDtoFactory,)
	{
	}

	/**
	 * @param ContractDto[]|ContractDto $payload
	 */
	public function handle(mixed $payload): SuccessResponse
	{
		if (is_array($payload)) {
			$successResponse = new ContractsSuccessResponse($this->contractDtoFactory->createFromContracts($payload));
		} elseif ($payload instanceof Contract) {
			$successResponse = new ContractSuccessResponse($this->contractDtoFactory->createFromContract($payload));
		} else {
			$successResponse = new SuccessResponse();
		}

		return $successResponse;
	}
}
