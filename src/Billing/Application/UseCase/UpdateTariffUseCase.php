<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Application\Dto\UpdateTariffDto;
use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Exception\TariffManageException;
use App\Billing\Domain\Exception\TariffNotFoundException;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use App\Core\Exception\NotFoundException;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use Throwable;

readonly final class UpdateTariffUseCase
{
	public function __construct(
		private TariffRepositoryInterface $tariffRepository,
		private UnitOfWorkInterface $unitOfWork
	) {
	}

	/**
	 * @throws TariffNotFoundException
	 * @throws TariffManageException
	 */
	public function putTariff(string $id, UpdateTariffDto $tariffDto): Tariff
	{

		try {
			$tariff = $this->tariffRepository->getById($id);
			$tariff->update($tariffDto->startDate, $tariffDto->finishDate, $tariffDto->status);

			$this->unitOfWork->persist($tariff);
			$this->unitOfWork->flush();
		} catch (UnitOfWorkException $exception) {
			throw new TariffManageException($exception, 'Ошибка сохранения тарифа');
		} catch (Throwable $exception) {
			throw new TariffManageException($exception);
		}

		return $tariff;
	}
}
