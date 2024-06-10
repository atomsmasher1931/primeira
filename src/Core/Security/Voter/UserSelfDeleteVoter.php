<?php

declare(strict_types=1);

namespace App\Core\Security\Voter;

use App\Person\Application\UseCase\GetEmployeeByIdUseCase;
use App\Person\Application\UseCase\GetEmployeeByLoginAndPasswordUseCase;
use App\Person\Domain\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSelfDeleteVoter extends Voter
{
	public const DELETE = 'delete';

	public function __construct(private readonly GetEmployeeByIdUseCase $getEmployeeByIdUseCase)
	{
	}

	protected function supports(string $attribute, $subject): bool
	{
		return $attribute === self::DELETE && is_string($subject);
	}

	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		$employee = ($this->getEmployeeByIdUseCase)($subject);
		$user = $token->getUser();
		if (!$user instanceof UserInterface) {
			return false;
		}

		return $user->getUserIdentifier() !== $employee->getUserIdentifier();
	}
}
