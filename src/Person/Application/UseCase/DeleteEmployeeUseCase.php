<?php

declare(strict_types=1);

namespace App\Person\Application\UseCase;

use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Person\Domain\Exception\EmployeeManageException;
use App\Person\Domain\Exception\EmployeeNotFoundException;
use App\Person\Domain\Repository\EmployeeRepositoryInterface;
use Throwable;

final readonly class DeleteEmployeeUseCase
{
	public function __construct(
		private EmployeeRepositoryInterface $employeeRepository,
		private UnitOfWorkInterface $unitOfWork
	) {
	}

	/**
	 * @throws EmployeeNotFoundException
	 * @throws EmployeeManageException
	 */
	public function __invoke(string $id): void
	{
		try {
			$this->unitOfWork->remove($this->employeeRepository->getById($id));
			$this->unitOfWork->flush();

		} catch (UnitOfWorkException $exception) {
			throw new EmployeeManageException($exception, 'Ошибка удаления пользователя');

		} catch (EmployeeNotFoundException $exception) {
			throw $exception;

		} catch (Throwable $exception) {
			throw new EmployeeManageException($exception);
		}
	}
}
