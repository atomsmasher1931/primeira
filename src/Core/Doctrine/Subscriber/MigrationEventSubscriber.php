<?php

declare(strict_types=1);

namespace App\Core\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Исправление ошибки, когда создаётся строка "CREATE SCHEMA public;" в откате миграции
 */
class MigrationEventSubscriber implements EventSubscriber
{
	/**
	 * @return string[]
	 */
	public function getSubscribedEvents()
	{
		return ['postGenerateSchema'];
	}

	/**
	 * @throws SchemaException
	 */
	public function postGenerateSchema(GenerateSchemaEventArgs $args): void
	{
		$schema = $args->getSchema();
		if (!$schema->hasNamespace('public')) {
			$schema->createNamespace('public');
		}
	}
}
