<?php

declare(strict_types=1);

namespace App\Core\Ampq\Exception;

use Throwable;
use Exception;

class ConsumerDeserializationException extends Exception implements Throwable
{
	public function __construct(Throwable $previous)
	{
		parent::__construct("Ошибка десериализации. {$previous->getMessage()}", 0, $previous);
	}
}
