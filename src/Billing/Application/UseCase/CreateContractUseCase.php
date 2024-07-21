<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Application\Dto\CreateContractDto;
use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Exception\ContractManageException;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use App\Core\Ampq\Event\ContractCreatedEvent;
use App\Core\Ampq\MessageBus\MessageBus;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Throwable;

final readonly class CreateContractUseCase
{
	public function __construct(
		private UnitOfWorkInterface $unitOfWork,
		private EntityIdGeneratorInterface $idGenerator,
		private TariffRepositoryInterface $tariffRepository,
		private MusicianRepositoryInterface $musicianRepository,
		private MessageBus $messageBus,
	) {
	}

	/**
	 * @throws ContractManageException
	 */
	public function create(CreateContractDto $createContractDto): Contract
	{
		try {
			$contract = Contract::create(
				$this->idGenerator->generate(),
				Contract::generateNumber((int)$createContractDto->startDate->format('Y'), 1, 1),
				$createContractDto->startDate,
				$createContractDto->startDate,
				$this->tariffRepository->getById($createContractDto->tariff),
				$this->musicianRepository->getById($createContractDto->musician),
			);

			$this->unitOfWork->persist($contract);
			$this->unitOfWork->flush();

			$this->messageBus->dispatch(
				new ContractCreatedEvent(
					$contract->id,
					$contract->number,
					$contract->getStartDate(),
					$contract->getFinishDate(),
					$contract->getStatus(),
					$contract->getTariffId(),
					$contract->getTariffValue(),
					$contract->getTariffTypeName(),
					$contract->getTariffDegreeName(),
					$contract->getTariffStatusName(),
					$contract->getMusicianId(),
					$contract->getMusicianEmail(),
					$contract->getMusicianPhone(),
				)
			);

			return $contract;

		} catch (UnitOfWorkException $exception) {
			throw new ContractManageException($exception, 'Ошибка сохранения контракта.');

		} catch (UuidGeneratorException $exception) {
			throw new ContractManageException($exception, 'Ошибка генерации ID контракта.');

		} catch (Throwable $exception) {
			throw new ContractManageException($exception);
		}
	}
}
