<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Login\V1;

use App\Core\Security\TokenGenerator\JwtTokenGenerator;
use App\Person\Application\UseCase\GetEmployeeByLoginAndPasswordUseCase;
use App\Person\Presentation\Http\Rest\Employee\Login\V1\Input\LoginData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class LoginController extends AbstractController
{
	public function __construct(
		private readonly GetEmployeeByLoginAndPasswordUseCase $getEmployeeByLoginAndPasswordUseCase,
		private readonly JwtTokenGenerator $jwtTokenGenerator,
	) {
	}

	#[Route(path: '/api/person/v1/employee/login', name: 'employee_login')]
	public function __invoke(#[MapRequestPayload] LoginData $loginData): array
	{
		try {
			$employee = ($this->getEmployeeByLoginAndPasswordUseCase)($loginData->login, $loginData->password);
		} catch(Throwable $exception) {
			throw new UnauthorizedHttpException('', '', $exception);
		}

		return [
			'token' => ($this->jwtTokenGenerator)($employee),
		];
	}
}
