<?php

declare(strict_types=1);

namespace App\Core\Client\Acquiring;

use Exception;
use Throwable;

/**
 * Ошибка взаимодействия с эквайрингом — транслирует исключения AcquiringBundle,
 * чтобы вызывающий код не зависел от классов бандла
 */
class AcquiringClientException extends Exception implements Throwable
{
	public function __construct(string $message = "", ?Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}
}
