<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;

class SuccessResponse
{
	use ResponseTrait;

	public function __construct()
	{
		$this->setSuccess();
	}
}
