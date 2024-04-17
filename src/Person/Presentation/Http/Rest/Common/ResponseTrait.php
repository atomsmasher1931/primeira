<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;

trait ResponseTrait
{
	private bool $success = false;

	private function setSuccess(): void
	{
		$this->success = true;
	}

	private function setUnsuccess(): void
	{
		$this->success = false;
	}

	public function isSuccess(): bool
	{
		return $this->success;
	}
}
