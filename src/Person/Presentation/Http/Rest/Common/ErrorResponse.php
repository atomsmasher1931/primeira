<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;

class ErrorResponse implements ErrorResponseInterface
{
	use ErrorResponseTrait;

	public function __construct(string $message)
	{
		$this->setUnsuccess();
		$this->message =  $message;
	}
}
