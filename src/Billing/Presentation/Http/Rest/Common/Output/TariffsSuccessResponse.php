<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Output;

use App\Core\Http\Rest\Response\SuccessResponse;

class TariffsSuccessResponse extends SuccessResponse
{

	/**
	 * @param TariffDto[] $tariffs
	 */
	public function __construct(private readonly array $tariffs)
	{
		parent::__construct();
	}

	/**
	 * @return TariffDto[]
	 */
	public function getTariffs(): array
	{
		return $this->tariffs;
	}
}
