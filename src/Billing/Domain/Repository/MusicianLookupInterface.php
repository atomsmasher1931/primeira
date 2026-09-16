<?php

declare(strict_types=1);

namespace App\Billing\Domain\Repository;

use App\Person\Domain\Entity\Musician;
use App\Person\Domain\Exception\MusicianNotFoundException;

/**
 * Интерфейс, сформулированный в терминах Billing: «найди музыканта по id, чтобы привязать к договору».
 * Реализует его Person (см. Person\Infrastructure\Gateway\BillingMusicianLookupGateway) —
 * так Billing\Application не зависит от App\Person\Domain\Repository\MusicianRepositoryInterface
 * напрямую, зависимость идёт только в одну сторону — от Person к интерфейсу, объявленному Billing.
 *
 * Возвращаемый тип Musician остаётся Person-сущностью осознанно: Contract::create() требует реальный
 * Doctrine-управляемый объект для ManyToOne-связи — полное стирание типа потребовало бы разрыва
 * прямой Doctrine-связи Contract<->Musician (Уровень 3, см. docs/refactoring-plan.md).
 *
 * @see \App\Person\Infrastructure\Gateway\BillingMusicianLookupGateway
 */
interface MusicianLookupInterface
{
	/**
	 * @throws MusicianNotFoundException
	 */
	public function getById(string $id): Musician;
}
