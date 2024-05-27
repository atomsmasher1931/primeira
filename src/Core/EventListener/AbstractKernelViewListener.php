<?php

declare(strict_types=1);

namespace App\Core\EventListener;

use App\Billing\Infrastructure\Handler\AbstractHandler;
use App\Core\Http\Rest\Response\SuccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractKernelViewListener
{
	protected readonly SerializerInterface $serializer;

	/**
	 * @param AbstractHandler[] $handlers
	 */
	protected function getHandler(ViewEvent $event, array $handlers): AbstractHandler
	{
		$controllerName = $this->getProcessableController($event);

		foreach ($handlers as $handler) {
			if ($handler->isProcessable($controllerName)) {
				return $handler;
			}
		}

		throw new \LogicException("Не найден обработчик для контроллера {$controllerName}");
	}

	protected function isProcessableController(ViewEvent $event, array $processableControllers): bool
	{
		return in_array($this->getProcessableController($event), $processableControllers, true);
	}

	private function getProcessableController(ViewEvent $event): string
	{

		if (is_array($event->controllerArgumentsEvent->getController())) {
			return get_class($event->controllerArgumentsEvent->getController()[0]);
		} else {
			return get_class($event->controllerArgumentsEvent->getController());
		}
	}

	protected function getHttpResponse(SuccessResponse $successResponse): Response
	{
		$responseData = $this->serializer->serialize(
			$successResponse,
			JsonEncoder::FORMAT,
			[AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
		);

		return new Response($responseData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
	}
}
