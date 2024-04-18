<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Output;

use App\Core\Http\Rest\Response\SuccessResponse;

class ContractsSuccessResponse extends SuccessResponse
{
	/**
	 * @param ContractDto[] $contracts
	 */
	public function __construct(public array $contracts)
	{
		parent::__construct();
	}

	/**
	 * @return ContractDto[]
	 */
	public function getContracts(): array
	{
		return $this->contracts;
	}
}
