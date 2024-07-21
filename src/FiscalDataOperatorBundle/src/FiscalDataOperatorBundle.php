<?php

declare(strict_types=1);

namespace FiscalDataOperatorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Бандл интеграции с ОФД
 * Пока заглушка, по плану умеет отправлять отчёт в ОФД и получать ссылку на чек
 */
class FiscalDataOperatorBundle extends AbstractBundle
{
	/**
	 * @inheritDoc
	 */
	public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->import('../config/services.yaml');
	}
}
