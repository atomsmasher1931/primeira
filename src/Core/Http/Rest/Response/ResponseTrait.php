<?php

declare(strict_types=1);

namespace App\Core\Http\Rest\Response;

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
