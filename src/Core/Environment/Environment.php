<?php

declare(strict_types=1);

namespace App\Core\Environment;

use Symfony\Component\HttpKernel\KernelInterface;
use Throwable;

class Environment
{
	private EnvironmentEnum $environment;

	public function __construct(private readonly KernelInterface $kernel)
	{
		try {
			$this->environment = EnvironmentEnum::from($this->kernel->getEnvironment());
		} catch (Throwable $exception) {
			new EnvironmentException($exception->getMessage(), $exception->getCode(), $exception);
		}
	}

	public function isProduction(): bool
	{
		return $this->environment->isProduction();
	}

	public function isDevelopment(): bool
	{
		return $this->environment->isDevelopment();
	}

	public function isTest(): bool
	{
		return $this->environment->isTest();
	}
}
