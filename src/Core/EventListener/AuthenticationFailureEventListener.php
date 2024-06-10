<?php

declare(strict_types=1);

namespace App\Core\EventListener;

use App\Core\Http\Rest\Response\ErrorResponse;
use App\Core\Http\Rest\Response\ErrorResponseInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: LoginFailureEvent::class, method: 'onAuthenticationFailure')]
final readonly class AuthenticationFailureEventListener
{
	public function __construct(protected readonly SerializerInterface $serializer)
	{
	}

	public function onAuthenticationFailure(LoginFailureEvent $event): void
	{
		if ($event->getException() instanceof BadCredentialsException) {
			$event->setResponse(
				$this->getHttpResponse(
					new ErrorResponse('Неверные логин или пароль'),
					Response::HTTP_UNAUTHORIZED
				)
			);
		}
	}

	private function getHttpResponse(ErrorResponseInterface $errorResponse, $code): Response
	{
		$responseData = $this->serializer->serialize(
			$errorResponse,
			JsonEncoder::FORMAT,
			[AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
		);

		return new Response($responseData, $code, ['Content-Type' => 'application/json']);
	}
}
