<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\EventListener;

use App\Billing\Infrastructure\Handler\ContractHandler;
use App\Billing\Infrastructure\Handler\TariffHandler;
use App\Billing\Presentation\Http\Rest\Contract\Create\V1\CreateContractController;
use App\Billing\Presentation\Http\Rest\Contract\GetById\V1\GetContractByIdController;
use App\Billing\Presentation\Http\Rest\Contract\GetByMusician\V1\GetContractByMusicianController;
use App\Billing\Presentation\Http\Rest\V1\Controller\TariffController;
use App\Core\EventListener\AbstractKernelViewListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 */
class BillingKernelViewEventListener extends AbstractKernelViewListener
{
	private const PROCESSABLE_CONTROLLERS = [
		CreateContractController::class,
		GetContractByIdController::class,
		GetContractByMusicianController::class,
		TariffController::class
	];

	public function __construct(
		protected readonly SerializerInterface $serializer,
		private readonly ContractHandler $contractHandler,
		private readonly TariffHandler $tariffHandler,
	) {
	}

	public function onKernelView(ViewEvent $event): void
	{
		if ($this->isProcessableController($event, self::PROCESSABLE_CONTROLLERS) === false) {
			return;
		}

		$value = $event->getControllerResult();

		$responseDto = $this->getHandler($event, [$this->contractHandler, $this->tariffHandler])->handle($value);

		$event->setResponse(
			$this->getHttpResponse(
				$responseDto
			)
		);
	}
}
