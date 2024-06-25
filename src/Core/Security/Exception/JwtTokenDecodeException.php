<?php

declare(strict_types=1);

namespace App\Core\Security\Exception;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use \Throwable;

class JwtTokenDecodeException extends JWTDecodeFailureException
{
	public function __construct(Throwable $previous = null)
	{
		parent::__construct(JWTDecodeFailureException::INVALID_TOKEN, 'Невозможно декодировать JWT', $previous);
	}
}
