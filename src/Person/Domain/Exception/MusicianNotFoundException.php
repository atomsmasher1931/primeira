<?php

declare(strict_types=1);

namespace App\Person\Domain\Exception;

use App\Core\Exception\NotFoundException;

/**
 * Не найден музыкант
 */
class MusicianNotFoundException extends NotFoundException implements MusicianExceptionInterface
{
	public function __construct()
	{
		parent::__construct('Музыкант не найден');
	}
}
