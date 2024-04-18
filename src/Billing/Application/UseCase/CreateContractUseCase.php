<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Application\Dto\CreateContractDto;
use App\Billing\Domain\Entity\Contract;
use App\Billing\Domain\Exception\ContractCreateException;
use App\Billing\Domain\Repository\TariffRepositoryInterface;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use App\Person\Domain\Repository\MusicianRepositoryInterface;
use Throwable;

readonly class CreateContractUseCase
{
	public function __construct(
		private UnitOfWorkInterface $unitOfWork,
		private EntityIdGeneratorInterface $idGenerator,
		private TariffRepositoryInterface $tariffRepository,
		private MusicianRepositoryInterface $musicianRepository
	) {
	}

	/**
	 * @throws ContractCreateException
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

			return $contract;

		} catch (UnitOfWorkException $exception) {
			throw new ContractCreateException($exception, 'Ошибка сохранения контракта.');

		} catch (UuidGeneratorException $exception) {
			throw new ContractCreateException($exception, 'Ошибка генерации ID контракта.');

		} catch (Throwable $exception) {
			throw new ContractCreateException($exception);
		}
	}
}
