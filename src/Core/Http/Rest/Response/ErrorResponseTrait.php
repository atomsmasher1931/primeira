<?php

declare(strict_types=1);

namespace App\Core\Http\Rest\Response;


trait ErrorResponseTrait
{
	use ResponseTrait;

	private string $message;

	public function getMessage(): string
	{
		return $this->message;
	}
}
