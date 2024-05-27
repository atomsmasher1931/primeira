<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Handler;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Presentation\Http\Rest\Common\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\ContractDto;
use App\Billing\Presentation\Http\Rest\Common\Output\ContractsSuccessResponse;
use App\Billing\Presentation\Http\Rest\Common\Output\ContractSuccessResponse;
use App\Billing\Presentation\Http\Rest\Contract\Create\V1\CreateContractController;
use App\Billing\Presentation\Http\Rest\Contract\GetById\V1\GetContractByIdController;
use App\Billing\Presentation\Http\Rest\Contract\GetByMusician\V1\GetContractByMusicianController;
use App\Core\Http\Rest\Response\SuccessResponse;

/**
 * Обработчик вылетевших из контроллера контрактов
 */
readonly class ContractHandler extends AbstractHandler
{
	protected const CONTROLLERS_NAME = [
		CreateContractController::class,
		GetContractByIdController::class,
		GetContractByMusicianController::class,
	];

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
