<?php

declare(strict_types=1);

namespace App\Core\Ampq\Event;

use App\Billing\Domain\Enum\ContractStatusEnum;
use DateTimeImmutable;

final readonly class ContractCreatedEvent implements MessageInterface
{
	public function __construct(
		public string $id,
		public string $number,
		public DateTimeImmutable $startDate,
		public DateTimeImmutable $finishDate,
		public ContractStatusEnum $status,
		public string $tariffId,
		public int $tariffValue,
		public string $tariffType,
		public string $tariffDegree,
		public string $tariffStatus,
		public string $musicianId,
		public string $musicianEmail,
		public string $musicianPhone,
	) {
	}
}
