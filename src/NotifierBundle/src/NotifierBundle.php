<?php

declare(strict_types=1);

namespace NotifierBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Бандл извещения
 * Пока это заглушка, по плану умеет слать письма и СМС
 */
class NotifierBundle extends AbstractBundle
{
	/**
	 * @param array                 $config
	 * @param ContainerConfigurator $container
	 * @param ContainerBuilder      $builder
	 *
	 * @return void
	 */
	public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->import('../config/services.yaml');
	}

}
