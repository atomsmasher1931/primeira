<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\EventListener;

use App\Person\Domain\Entity\Musician;
use App\Person\Presentation\Http\Rest\Common\SuccessResponse;
use App\Person\Presentation\Http\Rest\V1\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\V1\Output\MusiciansSuccessResponse;
use App\Person\Presentation\Http\Rest\V1\Output\MusicianSuccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class KernelViewEventListener
{
	public function __construct(
		private readonly SerializerInterface $serializer,
		private readonly MusicianDtoFactory $musicianDtoFactory
	) {
	}

	public function onKernelView(ViewEvent $event): void
	{
		$value = $event->getControllerResult();

		if (is_array($value)) {
			$event->setResponse(
				$this->getHttpResponse(
					new MusiciansSuccessResponse($this->musicianDtoFactory->createFromMusicians($value))
				)
			);
		} elseif ($value instanceof Musician) {
			$event->setResponse(
				$this->getHttpResponse(
					new MusicianSuccessResponse($this->musicianDtoFactory->createFromMusician($value))
				)
			);
		} else {
			$event->setResponse(
				$this->getHttpResponse(
					new SuccessResponse()
				)
			);
		}
	}

	private function getHttpResponse(SuccessResponse $successResponse): Response
	{
		$responseData = $this->serializer->serialize(
			$successResponse,
			JsonEncoder::FORMAT,
			[AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
		);

		return new Response($responseData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
	}
}
