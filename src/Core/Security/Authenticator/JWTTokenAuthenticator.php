<?php

declare(strict_types=1);

namespace App\Core\Security\Authenticator;

use App\Core\Security\Exception\JwtTokenDecodeException;
use App\Core\Security\Exception\RolesNotFoundInJwtPayloadException;
use App\Core\Security\Exception\UserNameNotFoundInJwtPayloadException;
use App\Core\Security\User\AuthUser;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class JWTTokenAuthenticator extends AbstractAuthenticator
{
	public function __construct(private readonly JWTEncoderInterface $jwtEncoder)
	{
	}

	public function supports(Request $request): ?bool
	{
		return true;
	}

	/**
	 * @throws JwtTokenDecodeException
	 * @throws UserNameNotFoundInJwtPayloadException
	 */
	public function authenticate(Request $request): Passport
	{
		$extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
		$token = $extractor->extract($request);
		if ($token === null) {
			throw new CustomUserMessageAuthenticationException('Клиент не прислал JWT');
		}

		try {
			$tokenData = $this->jwtEncoder->decode($token);
		} catch (JWTDecodeFailureException $exception) {
			throw new JwtTokenDecodeException($exception);
		}
		if (empty($tokenData['username'])) {
			throw new UserNameNotFoundInJwtPayloadException();
		}

		if (empty($tokenData['roles'])) {
			throw new RolesNotFoundInJwtPayloadException();
		}

		return new SelfValidatingPassport(
			new UserBadge(
				$tokenData['username'],
				fn() => new AuthUser($tokenData)
			)
		);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		return null;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
	{
		throw new AccessDeniedHttpException('Ошибка аутентификации, токен истёк');
	}
}
