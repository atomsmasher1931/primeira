<?php

declare(strict_types=1);

namespace AcquiringBundle\Facade;

use AcquiringBundle\Dto\SendInvoiceDto;
use AcquiringBundle\Exception\AcquiringException;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use DateTimeImmutable;

final readonly class AcquiringFacade
{
	public function __construct(private EntityIdGeneratorInterface $idGenerator)
	{
	}

	public function sendInvoice(SendInvoiceDto $invoice): string
	{
		//TODO Здесь идёт авторизация, а потом запрос в Банк на выставление счёта по АПИ
		if ($invoice->payerPhone === '79991234567') {
			throw new AcquiringException('Ошибка запроса к эквайеру');
		}

		return $this->idGenerator->generate();
	}

	public function checkPayment(string $invoiceId): DateTimeImmutable
	{
		//TODO Здесь идёт авторизация, а потом запрос в Банк на получение статуса платежа счёта по АПИ
		if ($invoiceId === 'какой-то юид надо сгенерить') {
			throw new AcquiringException('Ошибка запроса к эквайеру');
		}

		return new DateTimeImmutable();
	}
}
