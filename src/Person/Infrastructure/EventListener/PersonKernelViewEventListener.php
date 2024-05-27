<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\EventListener;

use App\Core\EventListener\AbstractKernelViewListener;
use App\Core\Http\Rest\Response\SuccessResponse;
use App\Person\Domain\Entity\Musician;
use App\Person\Presentation\Http\Rest\Musician\Common\Factory\MusicianDtoFactory;
use App\Person\Presentation\Http\Rest\Musician\Create\V1\CreateMusicianController;
use App\Person\Presentation\Http\Rest\Musician\Delete\V1\DeleteMusicianController;
use App\Person\Presentation\Http\Rest\Musician\GetById\V1\GetMusicianByIdController;
use App\Person\Presentation\Http\Rest\Musician\GetByPhone\V1\GetMusicianByPhoneController;
use App\Person\Presentation\Http\Rest\Musician\Output\MusicianSuccessResponse;
use App\Person\Presentation\Http\Rest\Musician\Update\V1\PatchMusicianController;
use App\Person\Presentation\Http\Rest\Musician\Update\V1\PutMusicianController;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class PersonKernelViewEventListener extends AbstractKernelViewListener
{
	private const PROCESSABLE_CONTROLLERS = [
		CreateMusicianController::class,
		GetMusicianByIdController::class,
		GetMusicianByPhoneController::class,
		PutMusicianController::class,
		PatchMusicianController::class,
		DeleteMusicianController::class,
	];

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
					new MusiciansSuccessResqponse($this->musicianDtoFactory->createFromMusicians($value))
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
