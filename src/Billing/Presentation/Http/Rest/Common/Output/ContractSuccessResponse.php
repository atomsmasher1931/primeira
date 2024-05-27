<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Output;

use App\Core\Http\Rest\Response\SuccessResponse;

class ContractSuccessResponse extends SuccessResponse
{
	public function __construct(public readonly ContractDto $contract)
	{
		parent::__construct();
	}

	public function getContract(): ContractDto
	{
		return $this->contract;
	}
}
