<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Output;

use App\Person\Presentation\Http\Rest\Common\ResponseTrait;

class MusicianSuccessResponse
{
	use ResponseTrait;

	public function __construct(public readonly MusicianDto $musician)
	{
		$this->setSuccess();
	}
}
