<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;

class ValidationErrorResponse implements ErrorResponseInterface
{
	use ErrorResponseTrait;

	/**
	 * @param string[] $errors
	 */
	public function __construct(private readonly array $errors)
	{
		$this->message = 'Ошибка валидации';
	}

	/**
	 * @return string[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
}
