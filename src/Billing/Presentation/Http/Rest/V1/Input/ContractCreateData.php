<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Input;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ContractCreateData
{
	public function __construct(
		#[Assert\Uuid]
		public string $musician,
		#[Assert\Uuid]
		public string $tariff,
		#[Assert\DateTime()]
		public string $startDate,
		#[Assert\DateTime()]
		public string $finishDate,
	) {
	}
}
