<?php

declare(strict_types=1);

namespace App\Core\Http\Rest\Response;

class ErrorResponse implements ErrorResponseInterface
{
	use ErrorResponseTrait;

	public function __construct(string $message)
	{
		$this->setUnsuccess();
		$this->message =  $message;
	}
}
