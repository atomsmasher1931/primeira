<?php

declare(strict_types=1);

namespace App\Core\Client\FiscalDataOperator;

use FiscalDataOperatorBundle\Dto\SendReceiptDto;
use FiscalDataOperatorBundle\Facade\FiscalDataOperatorFacade;

final readonly class FiscalDataOperatorClient
{
	public function __construct(private FiscalDataOperatorFacade $facade)
	{
	}

	public function sendReceipt(ReceiptDto $receipt): string
	{
		return $this->facade->sendReceipt(
			new SendReceiptDto(
				$receipt->paymentDate,
				$receipt->payerName,
				$receipt->paymentOrderMessage,
				$receipt->value
			)
		);
	}
}
