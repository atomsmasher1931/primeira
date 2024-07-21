<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Invoice;
use App\Billing\Domain\Exception\InvoiceManageException;
use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use Throwable;

final readonly class IssueInvoicesUseCase
{
	public function __construct(
		private ContractRepositoryInterface $contractRepository,
		private UnitOfWorkInterface $unitOfWork,
		private EntityIdGeneratorInterface $idGenerator,
	) {
	}

	public function __invoke(): int
	{
		try {
			$contracts = $this->contractRepository->getActiveContracts();

			$i = 0;
			foreach ($contracts as $contract) {
				$invoice = Invoice::create(
					$this->idGenerator->generate(),
					"{$contract->number}-1",
					$contract,
					$contract->getTariff(),
					$contract->getMusician()
				);

				$this->unitOfWork->persist($invoice);
				$this->unitOfWork->flush();
				$i++;
			}

			return $i;

		} catch (UnitOfWorkException $exception) {
			throw new InvoiceManageException($exception, 'Ошибка сохранения счёта.'.$exception->getMessage());

		} catch (UuidGeneratorException $exception) {
			throw new InvoiceManageException($exception, 'Ошибка генерации ID счёта.');

		} catch (Throwable $exception) {
			throw new InvoiceManageException($exception);
		}
	}
}
