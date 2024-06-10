<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Delete\V1;

use App\Core\Security\Voter\UserSelfDeleteVoter;
use App\Person\Application\UseCase\DeleteEmployeeUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DeleteEmployeeController extends AbstractController
{
	public function __construct(
		private readonly DeleteEmployeeUseCase $deleteEmployeeUseCase,
		private readonly AuthorizationCheckerInterface $authorizationChecker,
	) {
	}

	#[Route(path: '/api/person/v1/employee/delete/{id}', name: 'employee_delete', methods: ['DELETE'])]
	public function __invoke(string $id): void
	{
		if (!$this->authorizationChecker->isGranted(UserSelfDeleteVoter::DELETE, $id)) {
			throw new AccessDeniedHttpException();
		}

		($this->deleteEmployeeUseCase)($id);
	}
}
