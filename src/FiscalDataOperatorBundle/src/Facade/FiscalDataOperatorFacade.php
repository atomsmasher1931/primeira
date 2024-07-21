<?php

declare(strict_types=1);

namespace FiscalDataOperatorBundle\Facade;

use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use FiscalDataOperatorBundle\Dto\SendReceiptDto;
use FiscalDataOperatorBundle\Entity\Receipt;
use FiscalDataOperatorBundle\Exception\FiscalDataOperatorException;

final readonly class FiscalDataOperatorFacade
{
	public function __construct(
		private EntityIdGeneratorInterface $idGenerator,
		private UnitOfWorkInterface $unitOfWork,
	) {
	}

	public function sendReceipt(SendReceiptDto $receiptDto): string
	{
		//TODO Здесь идёт авторизация, а потом запрос в ОФД на получение чеков по АПИ
		if ($receiptDto->payerName === 'Тестовый Тест Тестов') {
			throw new FiscalDataOperatorException('Ошибка запроса к ОФД');
		}

		$receipt = Receipt::create(
			$this->idGenerator->generate(),
			new \DateTimeImmutable(),
			$receiptDto->paymentDate,
			$this->idGenerator->generate(),
			$receiptDto->payerName,
			$receiptDto->paymentOrderMessage,
			$receiptDto->value,
		);

		$this->unitOfWork->persist($receipt);
		$this->unitOfWork->flush();

		return $receipt->getReceiptHash();
	}
}
