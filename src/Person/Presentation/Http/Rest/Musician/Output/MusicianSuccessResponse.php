<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\Output;

use App\Core\Http\Rest\Response\SuccessResponse;

class MusicianSuccessResponse extends SuccessResponse
{
	public function __construct(public readonly MusicianDto $musician)
	{
		parent::__construct();
	}
}
