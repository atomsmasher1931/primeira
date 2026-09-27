<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core\Client\FiscalDataOperator;

use App\Core\Client\FiscalDataOperator\FiscalDataOperatorClient;
use App\Core\Client\FiscalDataOperator\FiscalDataOperatorClientException;
use App\Core\Client\FiscalDataOperator\ReceiptDto;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Tests\Support\UnitTester;
use DateTimeImmutable;
use FiscalDataOperatorBundle\Exception\FiscalDataOperatorException;
use FiscalDataOperatorBundle\Facade\FiscalDataOperatorFacade;
use Mockery;
use Throwable;

/**
 * @covers \App\Core\Client\FiscalDataOperator\FiscalDataOperatorClient
 */
class FiscalDataOperatorClientCest
{
	private const ID = '47a7dfef-44b0-46a3-8aa1-1bc119559954';
	private const PAYER_NAME = 'Сидоров Василий, Петрович';

	/** Значение, на котором заглушка FiscalDataOperatorFacade кидает FiscalDataOperatorException */
	private const FAILING_PAYER_NAME = 'Тестовый Тест Тестов';

	public function testSendReceiptSuccess(UnitTester $I): void
	{
		$I->assertSame(
			self::ID,
			$this->getClient()->sendReceipt($this->getReceiptDto(self::PAYER_NAME))
		);
	}

	public function testSendReceiptTranslatesBundleException(UnitTester $I): void
	{
		$exception = $this->catchException(
			fn () => $this->getClient()->sendReceipt($this->getReceiptDto(self::FAILING_PAYER_NAME))
		);

		$I->assertInstanceOf(FiscalDataOperatorClientException::class, $exception);
		$I->assertInstanceOf(FiscalDataOperatorException::class, $exception->getPrevious());
	}

	public function testSendReceiptTranslatesNonBundleException(UnitTester $I): void
	{
		$flushException = new UnitOfWorkException('flush failed');

		$exception = $this->catchException(
			fn () => $this->getClient($flushException)->sendReceipt($this->getReceiptDto(self::PAYER_NAME))
		);

		$I->assertInstanceOf(FiscalDataOperatorClientException::class, $exception);
		$I->assertSame($flushException, $exception->getPrevious());
	}

	private function getClient(?Throwable $flushException = null): FiscalDataOperatorClient
	{
		$generator = Mockery::mock(EntityIdGeneratorInterface::class);
		$generator->shouldReceive('generate')->andReturn(self::ID);
		$unitOfWork = Mockery::mock(UnitOfWorkInterface::class);
		$unitOfWork->shouldReceive('persist');
		$flush = $unitOfWork->shouldReceive('flush');
		if ($flushException !== null) {
			$flush->andThrow($flushException);
		}

		return new FiscalDataOperatorClient(new FiscalDataOperatorFacade($generator, $unitOfWork));
	}

	private function getReceiptDto(string $payerName): ReceiptDto
	{
		return new ReceiptDto(
			new DateTimeImmutable('2026-09-10'),
			$payerName,
			'Оплата по договору',
			3000,
		);
	}

	private function catchException(callable $callback): ?Throwable
	{
		try {
			$callback();
		} catch (Throwable $exception) {
			return $exception;
		}

		return null;
	}
}
