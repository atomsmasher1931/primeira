<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core\Client\Acquiring;

use AcquiringBundle\Exception\AcquiringException;
use AcquiringBundle\Facade\AcquiringFacade;
use App\Core\Client\Acquiring\AcquiringClient;
use App\Core\Client\Acquiring\AcquiringClientException;
use App\Core\Client\Acquiring\InvoiceDto;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Tests\Support\UnitTester;
use DateTimeImmutable;
use Mockery;
use Throwable;

/**
 * @covers \App\Core\Client\Acquiring\AcquiringClient
 */
class AcquiringClientCest
{
	private const ACQUIRE_INVOICE_ID = '47a7dfef-44b0-46a3-8aa1-1bc119559954';
	private const PAYER_PHONE = '79990000000';

	/** Значения, на которых заглушка AcquiringFacade кидает AcquiringException */
	private const FAILING_PAYER_PHONE = '79991234567';
	private const FAILING_ACQUIRE_INVOICE_ID = 'какой-то юид надо сгенерить';

	public function testSendInvoiceSuccess(UnitTester $I): void
	{
		$I->assertSame(
			self::ACQUIRE_INVOICE_ID,
			$this->getClient()->sendInvoice($this->getInvoiceDto(self::PAYER_PHONE))
		);
	}

	public function testSendInvoiceTranslatesBundleException(UnitTester $I): void
	{
		$exception = $this->catchException(
			fn () => $this->getClient()->sendInvoice($this->getInvoiceDto(self::FAILING_PAYER_PHONE))
		);

		$I->assertInstanceOf(AcquiringClientException::class, $exception);
		$I->assertInstanceOf(AcquiringException::class, $exception->getPrevious());
	}

	public function testSendInvoiceTranslatesNonBundleException(UnitTester $I): void
	{
		$generatorException = new UuidGeneratorException('generate failed');

		$exception = $this->catchException(
			fn () => $this->getClient($generatorException)->sendInvoice($this->getInvoiceDto(self::PAYER_PHONE))
		);

		$I->assertInstanceOf(AcquiringClientException::class, $exception);
		$I->assertSame($generatorException, $exception->getPrevious());
	}

	public function testCheckPaymentSuccess(UnitTester $I): void
	{
		$I->assertInstanceOf(
			DateTimeImmutable::class,
			$this->getClient()->checkPayment(self::ACQUIRE_INVOICE_ID)
		);
	}

	public function testCheckPaymentTranslatesBundleException(UnitTester $I): void
	{
		$exception = $this->catchException(
			fn () => $this->getClient()->checkPayment(self::FAILING_ACQUIRE_INVOICE_ID)
		);

		$I->assertInstanceOf(AcquiringClientException::class, $exception);
		$I->assertInstanceOf(AcquiringException::class, $exception->getPrevious());
	}

	private function getClient(?Throwable $generatorException = null): AcquiringClient
	{
		$generator = Mockery::mock(EntityIdGeneratorInterface::class);
		$generate = $generator->shouldReceive('generate');
		if ($generatorException !== null) {
			$generate->andThrow($generatorException);
		} else {
			$generate->andReturn(self::ACQUIRE_INVOICE_ID);
		}

		return new AcquiringClient(new AcquiringFacade($generator));
	}

	private function getInvoiceDto(string $payerPhone): InvoiceDto
	{
		return new InvoiceDto(
			new DateTimeImmutable('2026-09-01'),
			new DateTimeImmutable('2026-09-15'),
			$payerPhone,
			'vsidorov@primeira.ru',
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
