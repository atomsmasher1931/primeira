<?php

declare(strict_types=1);

namespace App\Core\Client\FiscalDataOperator;

use Exception;
use Throwable;

/**
 * Ошибка взаимодействия с ОФД — транслирует исключения FiscalDataOperatorBundle,
 * чтобы вызывающий код не зависел от классов бандла
 */
class FiscalDataOperatorClientException extends Exception implements Throwable
{
	public function __construct(string $message = "", ?Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}
}
