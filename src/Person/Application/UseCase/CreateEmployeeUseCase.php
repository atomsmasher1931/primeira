<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\PasswordHasher\PasswordHasherInterface;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Person\Application\Dto\CreateEmployeeDto;
use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Exception\EmployeeManageException;
use Throwable;

final readonly class CreateEmployeeUseCase
{
	public function __construct(
		private UnitOfWorkInterface $unitOfWork,
		private EntityIdGeneratorInterface $idGenerator,
		private PasswordHasherInterface $passwordHasher,
	) {
	}

	public function __invoke(CreateEmployeeDto $dto): Employee
	{
		try {
			$employee = Employee::create(
				$this->idGenerator->generate(),
				$dto->lastName,
				$dto->firstName,
				$dto->patronymic,
				$dto->login,
				bin2hex(random_bytes(64)),
				$dto->roles,
				$dto->phone,
				$dto->email,
				$dto->status,
			);

			$employee->changePassword($this->passwordHasher->hash($employee, $dto->password));

			$this->unitOfWork->persist($employee);
			$this->unitOfWork->flush();
		} catch (UnitOfWorkException $exception) {
			throw new EmployeeManageException($exception, 'Ошибка сохранения пользователя');
		} catch (UuidGeneratorException $exception) {
			throw new EmployeeManageException($exception, 'Ошибка генерации GUID пользователя');
		} catch (Throwable $exception) {
			throw new EmployeeManageException($exception);
		}

		return $employee;
	}
}
