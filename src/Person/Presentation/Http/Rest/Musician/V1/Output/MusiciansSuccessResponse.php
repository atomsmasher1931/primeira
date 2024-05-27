<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Musician\V1\Output;

use App\Core\Http\Rest\Response\SuccessResponse;

class MusiciansSuccessResponse extends SuccessResponse
{
	/**
	 * @param MusicianDto[] $musicians
	 */
	public function __construct(public readonly array $musicians){
		parent::__construct();
	}
}
