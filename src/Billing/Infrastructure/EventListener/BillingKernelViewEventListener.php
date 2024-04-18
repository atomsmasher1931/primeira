<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\EventListener;

use App\Billing\Domain\Entity\Contract;
use App\Billing\Infrastructure\Handler\ContractHandler;
use App\Billing\Infrastructure\Handler\TariffHandler;
use App\Billing\Presentation\Http\Rest\V1\Controller\ContractController;
use App\Billing\Presentation\Http\Rest\V1\Controller\TariffController;
use App\Billing\Presentation\Http\Rest\V1\Factory\ContractDtoFactory;
use App\Billing\Presentation\Http\Rest\V1\Output\ContractSuccessResponse;
use App\Core\EventListener\AbstractKernelViewListener;
use App\Core\Http\Rest\Response\SuccessResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 */
class BillingKernelViewEventListener extends AbstractKernelViewListener
{
	private const PROCESSABLE_CONTROLLERS = [ContractController::class, TariffController::class];

	private const HANDLER = [

	];

	/**
	 */
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
