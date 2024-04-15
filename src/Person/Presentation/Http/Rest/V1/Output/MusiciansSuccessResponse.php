<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\V1\Output;

use App\Person\Presentation\Http\Rest\Common\ResultTrait;

class MusiciansSuccessResponse
{
	use ResultTrait;

	/**
	 * @param MusicianDto[] $musicians
	 */
	public function __construct(public readonly array $musicians)
	{
		$this->setSuccess();
	}
}
