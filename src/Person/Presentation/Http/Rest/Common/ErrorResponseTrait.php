<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;


trait ErrorResponseTrait
{
	use ResponseTrait;

	private string $message;

	public function getMessage(): string
	{
		return $this->message;
	}
}
