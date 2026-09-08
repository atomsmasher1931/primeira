<?php

declare(strict_types=1);

namespace App\Person\Domain\Repository;

use App\Billing\Domain\Entity\Contract;

/**
 * Интерфейс, сформулированный в терминах Person: «дай мне договоры музыканта».
 * Реализует его Billing (см. Billing\Infrastructure\Gateway\PersonMusicianContractsGateway) —
 * так Person\Application не зависит от App\Billing\Domain\Repository\ContractRepositoryInterface
 * напрямую, зависимость идёт только в одну сторону — от Billing к интерфейсу, объявленному Person.
 *
 * Возвращаемый тип Contract остаётся Billing-сущностью осознанно: Musician::addContracts() наполняет
 * ORM-коллекцию Doctrine (OneToMany Musician::$contracts), а не просто читает данные для вывода —
 * полное стирание типа потребовало бы разрыва прямой Doctrine-связи Contract<->Musician (Уровень 3,
 * см. docs/refactoring-plan.md).
 *
 * @see \App\Billing\Infrastructure\Gateway\PersonMusicianContractsGateway
 */
interface MusicianContractsProviderInterface
{
	/**
	 * @return Contract[]
	 */
	public function findByMusicianId(string $musicianId): array;
}
