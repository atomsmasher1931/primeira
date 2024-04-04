<?php

declare(strict_types=1);

namespace App\Person\Domain\Repository;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;

interface MusicianRepositoryInterface
{

	/**
	 * @throws MusicianNotFoundException
	 */
	public function getById(string $id): Musician;

	/**
	 * @throws MusicianNotFoundException
	 */
	public function getByPhone(string $phone): Musician;
}
