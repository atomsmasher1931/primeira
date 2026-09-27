<?php

declare(strict_types=1);

namespace App\Core\Client\FiscalDataOperator;

use FiscalDataOperatorBundle\Dto\SendReceiptDto;
use FiscalDataOperatorBundle\Facade\FiscalDataOperatorFacade;
use Throwable;

final readonly class FiscalDataOperatorClient
{
	public function __construct(private FiscalDataOperatorFacade $facade)
	{
	}

	/**
	 * @throws FiscalDataOperatorClientException
	 */
	public function sendReceipt(ReceiptDto $receipt): string
	{
		try {
			return $this->facade->sendReceipt(
				new SendReceiptDto(
					$receipt->paymentDate,
					$receipt->payerName,
					$receipt->paymentOrderMessage,
					$receipt->value
				)
			);
		} catch (Throwable $exception) {
			throw new FiscalDataOperatorClientException($exception->getMessage(), $exception);
		}
	}
}
