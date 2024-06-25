<?php

declare(strict_types=1);

namespace App\Person\Presentation\Http\Rest\Employee\Create\V1;

use App\Person\Application\Dto\CreateEmployeeDto;
use App\Person\Application\UseCase\CreateEmployeeUseCase;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Exception\EmployeeManageException;
use App\Person\Presentation\Http\Rest\Employee\Common\Factory\EmployeeDtoFactory;
use App\Person\Presentation\Http\Rest\Employee\Common\Output\EmployeeDto;
use App\Person\Presentation\Http\Rest\Employee\Create\V1\Input\CreateEmployeeData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateEmployeeController extends AbstractController
{
	public function __construct(
		private readonly EmployeeDtoFactory $userDtoFactory,
		private readonly CreateEmployeeUseCase $createUserUseCase,
	) {
	}

	/**
	 * @throws EmployeeManageException
	 */
	#[Route(path: '/api/person/v1/employee/create', name: 'employee_create', methods: ['POST'])]
	public function __invoke(#[MapRequestPayload] CreateEmployeeData $createUserData): EmployeeDto
	{
		return $this->userDtoFactory->createFromUser(
			($this->createUserUseCase)(
				new CreateEmployeeDto(
					$createUserData->lastName,
					$createUserData->firstName,
					$createUserData->patronymic,
					$createUserData->login,
					$createUserData->passwords,
					$createUserData->roles,
					$createUserData->phone,
					$createUserData->email,
					PersonStatusEnum::tryFrom($createUserData->status),
				)
			)
		);
	}
}
