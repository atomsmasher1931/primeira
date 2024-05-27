<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Common\Output;

use App\Core\Http\Rest\Response\SuccessResponse;

class TariffSuccessResponse extends SuccessResponse
{
	public function __construct(private readonly TariffDto $tariff)
	{
		parent::__construct();
	}

	public function getTariff(): TariffDto
	{
		return $this->tariff;
	}
}
