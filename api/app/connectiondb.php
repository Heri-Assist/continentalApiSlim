<?php

use DI\ContainerBuilder;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Application\Settings\SettingsInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Carga las configuraciones
$settings = require __DIR__ . '/settings.php';
$settings($containerBuilder);

$containerBuilder->addDefinitions([
	EntityManager::class => function () use ($containerBuilder): EntityManager {
			// Obtiene el contenedor de configuraciones
			$container = $containerBuilder->build();

			// Obtiene las configuraciones de Doctrine del contenedor
			$settings = $container->get(SettingsInterface::class);
			$doctrineSettings = $settings->get('doctrine');

			$cache = $doctrineSettings['dev_mode'] ?
					DoctrineProvider::wrap(new ArrayAdapter()) :
					DoctrineProvider::wrap(new FilesystemAdapter(directory: $doctrineSettings['cache_dir']));

			$config = Setup::createAttributeMetadataConfiguration(
					$doctrineSettings['metadata_dirs'],
					$doctrineSettings['dev_mode'],
					null,
					$cache
			);

			return EntityManager::create($doctrineSettings['connection'], $config);
	},
]);

$container = $containerBuilder->build();

return $container;


