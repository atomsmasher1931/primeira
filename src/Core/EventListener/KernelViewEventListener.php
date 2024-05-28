<?php

declare(strict_types=1);

namespace App\Core\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class KernelViewEventListener
{
	public function __construct(protected readonly SerializerInterface $serializer)
	{
	}

	public function onKernelView(ViewEvent $event): void
	{
		$value = $event->getControllerResult();

		$event->setResponse($this->getHttpResponse($value));
	}


	protected function getHttpResponse(mixed $responsePayload): Response
	{
		$responseData = $this->serializer->serialize(
			$responsePayload,
			JsonEncoder::FORMAT,
			[AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
		);

		return new Response($responseData, $responsePayload === [] ? Response::HTTP_NO_CONTENT : Response::HTTP_OK, ['Content-Type' => 'application/json']);
	}

}
