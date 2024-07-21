<?php

declare(strict_types=1);

namespace App\Billing\Domain\Repository;

use App\Billing\Domain\Entity\Invoice;

interface InvoiceRepositoryInterface
{
	/**
	 * @return Invoice[]
	 */
	public function getIssued(): array;

	/**
	 * @return Invoice[]
	 */
	public function getSentToAcquire(): array;

	/**
	 * @return Invoice[]
	 */
	public function getReceived(): array;
}
