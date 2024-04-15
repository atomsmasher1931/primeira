<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;

class ErrorResponse
{
	use ResultTrait;

	public function __construct(string $message)
	{
		$this->setSuccess(false);
		$this->setMessage($message);
	}
}
