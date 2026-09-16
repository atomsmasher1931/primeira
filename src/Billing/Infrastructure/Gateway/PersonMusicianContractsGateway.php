<?php

declare(strict_types=1);

namespace App\Billing\Infrastructure\Gateway;

use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Person\Domain\Repository\MusicianContractsProviderInterface;

/**
 * Шлюз инверсии зависимости: Billing реализует интерфейс, объявленный контекстом Person,
 * вместо того чтобы Person импортировал репозиторий Billing напрямую.
 */
final readonly class PersonMusicianContractsGateway implements MusicianContractsProviderInterface
{
	public function __construct(private ContractRepositoryInterface $contractRepository)
	{
	}

	public function findByMusicianId(string $musicianId): array
	{
		return $this->contractRepository->findByMusicianId($musicianId);
	}
}
