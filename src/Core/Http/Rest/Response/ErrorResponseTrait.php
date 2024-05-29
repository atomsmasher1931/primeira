<?php

declare(strict_types=1);

namespace App\Core\Http\Rest\Response;


trait ErrorResponseTrait
{
	private bool $success;
	private string $message;

	private function setUnsuccess(): void
	{
		$this->success = false;
	}

	public function isSuccess(): bool
	{
		return $this->success;
	}

	public function getMessage(): string
	{
		return $this->message;
	}
}
