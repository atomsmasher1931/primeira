<?php

declare(strict_types=1);

namespace App\Billing\Domain\Exception;

use DomainException;
use Throwable;
class TooManyActiveContractsByMusicianException extends DomainException implements Throwable
{
	public function __construct(string $musicianId, int $contractQuantity)
	{
		parent::__construct("За музакантом {$musicianId} закреплено {$contractQuantity}, а должен быть 1");
	}
}
