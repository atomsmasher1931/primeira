<?php

declare(strict_types=1);

namespace App\Person\Domain\Exception;

use App\Core\Exception\NotFoundException;
use Throwable;

/**
 * Не найден музыкант
 */
class MusicianNotFoundException extends NotFoundException implements Throwable
{
	public function __construct()
	{
		parent::__construct('Музыкант не найден');
	}
}
