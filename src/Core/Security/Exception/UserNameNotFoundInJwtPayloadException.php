<?php

declare(strict_types=1);

namespace App\Core\Security\Exception;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

/**
 * В payload JWT не найден USERNAME
 */
class UserNameNotFoundInJwtPayloadException extends JWTDecodeFailureException
{
	public function __construct()
	{
		parent::__construct(JWTDecodeFailureException::INVALID_TOKEN,'Не найдено поле USERNAME в PAYLOAD JWT');
	}
}
