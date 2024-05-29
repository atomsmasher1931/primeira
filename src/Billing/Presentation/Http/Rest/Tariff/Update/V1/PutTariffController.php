<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Tariff\Update\V1;

use App\Billing\Application\Dto\UpdateTariffDto;
use App\Billing\Application\UseCase\UpdateTariffUseCase;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Billing\Presentation\Http\Rest\Common\Factory\TariffDtoFactory;
use App\Billing\Presentation\Http\Rest\Common\Output\TariffDto;
use App\Billing\Presentation\Http\Rest\Tariff\Update\V1\Input\PutTariffData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use \DateTimeImmutable;

class PutTariffController extends AbstractController
{
	public function __construct(
		private readonly UpdateTariffUseCase $updateTariffUseCase,
		private readonly TariffDtoFactory $tariffDtoFactory,
	) {
	}

	/**
	 * @throws \Exception
	 */
	#[Route(path: '/api/billing/v1/tariff/{id}', name: 'put_tariff', requirements: ['id' => '[0-9a-f\-]{36}'])]
	public function __invoke(#[MapRequestPayload] PutTariffData $putTariffData, string $id): TariffDto
	{
		return $this->tariffDtoFactory->createFromTariff(
			$this->updateTariffUseCase->putTariff(
				$id,
				new UpdateTariffDto(
					MusicianDegreeTariffEnum::tryFrom($putTariffData->musicianDegree),
					TariffTypeEnum::tryFrom($putTariffData->type),
					$putTariffData->value,
					new DateTimeImmutable($putTariffData->startDate),
					new DateTimeImmutable($putTariffData->finishDate),
					TariffStatusEnum::tryFrom($putTariffData->status),
				)
			)
		);
	}
}
