<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\EventListener;

use App\Core\EventListener\AbstractKernelViewListener;
use App\Core\Http\Rest\Response\SuccessResponse;
use App\Person\Domain\Entity\Musician;
use App\Person\Presentation\Http\Rest\V1\Controller\PersonController;
use App\Person\Presentation\Http\Rest\V1\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\V1\Output\MusiciansSuccessResponse;
use App\Person\Presentation\Http\Rest\V1\Output\MusicianSuccessResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class PersonKernelViewEventListener extends AbstractKernelViewListener
{
	private const PROCESSABLE_CONTROLLERS = [PersonController::class];

	public function __construct(
		protected readonly SerializerInterface $serializer,
		private readonly MusicianDtoFactory $musicianDtoFactory
	) {
	}

	public function onKernelView(ViewEvent $event): void
	{
		if ($this->isProcessableController($event, self::PROCESSABLE_CONTROLLERS) === false) {
			return;
		}

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
}
