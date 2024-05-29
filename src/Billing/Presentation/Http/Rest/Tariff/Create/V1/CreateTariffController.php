<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Tariff\Create\V1;

use App\Billing\Application\Dto\CreateTariffDto;
use App\Billing\Application\UseCase\CreateTariffUseCase;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Billing\Presentation\Http\Rest\Common\Factory\TariffDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\TariffDto;
use App\Billing\Presentation\Http\Rest\Tariff\Create\V1\Input\CreateTariffData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use \DateTimeImmutable;

class CreateTariffController extends AbstractController
{
	public function __construct(
		private readonly CreateTariffUseCase $createTariffUseCase,
		private readonly TariffDtoFactory $tariffDtoFactory,
	) {
	}

	/**
	 * @throws \Exception
	 */
	#[Route(path: '/api/billing/v1/tariff/create', name: 'create_tariff', methods: ['POST'])]
	public function __invoke(#[MapRequestPayload] CreateTariffData $createTariffData): TariffDto
	{
		return $this->tariffDtoFactory->createFromTariff(
			$this->createTariffUseCase->create(
				new CreateTariffDto(
					MusicianDegreeTariffEnum::tryFrom($createTariffData->musicianDegree),
					TariffTypeEnum::tryFrom($createTariffData->type),
					$createTariffData->value,
					new DateTimeImmutable($createTariffData->startDate),
					new DateTimeImmutable($createTariffData->finishDate),
					TariffStatusEnum::tryFrom($createTariffData->status)
				)
			)
		);
	}
}
