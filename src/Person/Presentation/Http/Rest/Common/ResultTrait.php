<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Common;


trait ResultTrait
{
	private bool $success;

	private ?string $message = null;

	public function setSuccess(): void
	{
		$this->success = true;
	}

	public function setUnsuccess(): void
	{
		$this->success = false;
	}

	public function setMessage(?string $message): void
	{
		$this->message = $message;
	}

	public function isSuccess(): bool
	{
		return $this->success;
	}

	public function getMessage(): ?string
	{
		return $this->message;
	}
}
