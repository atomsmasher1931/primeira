<?php

declare(strict_types=1);

namespace App\Person\Infrastructure\Gateway;

use App\Billing\Domain\Repository\MusicianLookupInterface;
use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Repository\MusicianRepositoryInterface;

/**
 * Шлюз инверсии зависимости: Person реализует интерфейс, объявленный контекстом Billing,
 * вместо того чтобы Billing импортировал репозиторий Person напрямую.
 */
final readonly class BillingMusicianLookupGateway implements MusicianLookupInterface
{
	public function __construct(private MusicianRepositoryInterface $musicianRepository)
	{
	}

	public function getById(string $id): Musician
	{
		return $this->musicianRepository->getById($id);
	}
}
