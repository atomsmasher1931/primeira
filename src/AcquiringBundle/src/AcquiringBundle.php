<?php

declare(strict_types=1);

namespace AcquiringBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Бандл эквайринга
 * Пока заглушка, по плану умеет отправлять счета в банк и спрашивать оплачены ли они
 */
class AcquiringBundle extends AbstractBundle
{
	/**
	 * @inheritDoc
	 */
	public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->import('../config/services.yaml');
	}
}
