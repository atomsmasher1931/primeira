<?php

declare(strict_types=1);

namespace App\Core\Http\Rest\Response;

class SuccessResponse
{
	use ResponseTrait;

	public function __construct()
	{
		$this->setSuccess();
	}
}
