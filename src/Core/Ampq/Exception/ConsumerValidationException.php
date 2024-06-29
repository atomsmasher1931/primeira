<?php

declare(strict_types=1);

namespace App\Core\Ampq\Exception;

use Throwable;
use Exception;

class ConsumerValidationException extends Exception implements Throwable
{
	public function __construct(string $errors)
	{
		parent::__construct("Ошибка валидации. {$errors}");
	}
}
