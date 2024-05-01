<?php

declare(strict_types=1);

namespace App\Core\EventListener;

use App\Core\Environment\Environment;
use App\Core\Exception\NotFoundException;
use App\Core\Http\Rest\Response\ErrorResponse;
use App\Core\Http\Rest\Response\ErrorResponseInterface;
use App\Core\Http\Rest\Response\ValidationErrorResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class KernelExceptionEventListener
{
	public function __construct(
		private readonly SerializerInterface $serializer,
		private readonly Environment $environment
	) {
	}

	public function onKernelException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		if ($exception instanceof NotFoundException) {
			$event->setResponse(
				$this->getHttpResponse(new ErrorResponse($exception->getMessage()), Response::HTTP_NOT_FOUND)
			);

			return;
		}

		if ($exception instanceof NotFoundHttpException) {
			$message = null;
			if ($this->environment->isDevelopment()) {
				$message = trim("{$exception->getMessage()} {$exception->getPrevious()?->getMessage()}");
			}
			$event->setResponse(
				$this->getHttpResponse(new ErrorResponse($message ?? 'Неизвестный путь.'), Response::HTTP_NOT_FOUND)
			);

			return;
		}

		if ($exception instanceof HttpException && $exception->getPrevious() instanceof ValidationFailedException) {
			/** @var ValidationFailedException $validationException */
			$validationException = $exception->getPrevious();
			$violations = [];
			foreach ($validationException->getViolations() as $violation) {
				$violations[$violation->getPropertyPath()] = $violation->getMessage();
			}

			$event->setResponse(
				$this->getHttpResponse(new ValidationErrorResponse($violations), Response::HTTP_UNPROCESSABLE_ENTITY)
			);

			return;
		}

		if ($exception instanceof Throwable) {
			$message = null;
			if ($this->environment->isDevelopment()) {
				$message = trim("{$exception->getMessage()} {$exception->getPrevious()?->getMessage()}");
			}

			$event->setResponse(
				$this->getHttpResponse(
					new ErrorResponse($message ?? 'Неизвестная ошибка сервера'),
					Response::HTTP_INTERNAL_SERVER_ERROR
				)
			);
		}
	}

	private function getHttpResponse(ErrorResponseInterface $errorResponse, $code): Response {
		$responseData = $this->serializer->serialize(
			$errorResponse,
			JsonEncoder::FORMAT,
			[AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
		);

		return new Response($responseData, $code, ['Content-Type' => 'application/json']);
	}
}
