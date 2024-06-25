<?php

declare(strict_types=1);

namespace App\Core\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface
{
	private string $login;

	/** @var string[] */
	private array $roles;

	public function __construct(array $credentials)
	{
		$this->login = $credentials['username'];
		$this->roles = array_unique(array_merge($credentials['roles'], ['ROLE_USER']));
	}

	/**
	 * @return string[]
	 */
	public function getRoles(): array
	{
		return $this->roles;
	}

	public function eraseCredentials(): void
	{
	}

	public function getUserIdentifier(): string
	{
		return $this->login;
	}
}
