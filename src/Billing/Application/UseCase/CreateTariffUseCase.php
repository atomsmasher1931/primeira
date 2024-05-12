<?php

declare(strict_types=1);

namespace App\Billing\Application\UseCase;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Billing\Domain\Exception\TariffManageException;
use App\Billing\Presentation\Http\Rest\V1\Input\TariffManageDto;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Core\UuidGenerator\EntityIdGeneratorInterface;
use App\Core\UuidGenerator\UuidGeneratorException;
use Throwable;

readonly final class CreateTariffUseCase
{
	public function __construct(
		private EntityIdGeneratorInterface $idGenerator,
		private UnitOfWorkInterface $unitOfWork,
	) {
	}

	public function create(TariffManageDto $dto): Tariff
	{
		try {
			$tariff = Tariff::create(
				$this->idGenerator->generate(),
				MusicianDegreeTariffEnum::tryFrom($dto->musicianDegree),
				TariffTypeEnum::tryFrom($dto->type),
				$dto->value,
				$dto->startDate,
				$dto->finishDate,
				TariffStatusEnum::tryFrom($dto->status),
			);

			$this->unitOfWork->persist($tariff);
			$this->unitOfWork->flush();

			return $tariff;

		} catch (UnitOfWorkException $exception) {
			throw new TariffManageException($exception, 'Ошибка при сохранении тарифа.');

		} catch (UuidGeneratorException $exception) {
			throw new TariffManageException($exception, 'Ошибка генерации ID тарифа.');

		} catch (Throwable $exception) {
			throw new TariffManageException($exception);
		}
	}
}
