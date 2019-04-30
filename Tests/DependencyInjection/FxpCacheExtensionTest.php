<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\CacheBundle\Tests\DependencyInjection;

use Fxp\Bundle\CacheBundle\DependencyInjection\FxpCacheExtension;
use Fxp\Bundle\CacheBundle\FxpCacheBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Fxp Cache Extension Tests.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class FxpCacheExtensionTest extends TestCase
{
    public function testNoConfig(): void
    {
        $container = $this->createContainer();

        $this->assertFalse($container->hasParameter('fxp_cache.override_cache_services'));
    }

    /**
     * Create container.
     *
     * @param array $configs    The configs
     * @param array $parameters The container parameters
     * @param array $services   The service definitions
     *
     * @return ContainerBuilder
     */
    protected function createContainer(array $configs = [], array $parameters = [], array $services = [])
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'FxpCacheBundle' => FxpCacheBundle::class,
            ],
            'kernel.cache_dir' => sys_get_temp_dir().'/fxp_cache_bundle',
            'kernel.debug' => true,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => sys_get_temp_dir().'/fxp_cache_bundle',
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => Container::class,
        ]));

        $sfExt = new FrameworkExtension();
        $extension = new FxpCacheExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($extension);

        foreach ($parameters as $name => $value) {
            $container->setParameter($name, $value);
        }

        foreach ($services as $id => $definition) {
            $container->setDefinition($id, $definition);
        }

        $sfExt->load([['annotations' => false]], $container);
        $extension->load($configs, $container);

        $bundle = new FxpCacheBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile();

        return $container;
    }
}
