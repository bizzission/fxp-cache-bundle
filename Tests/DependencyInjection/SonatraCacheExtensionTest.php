<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\CacheBundle\Tests\DependencyInjection;

use Sonatra\Bundle\CacheBundle\DependencyInjection\SonatraCacheExtension;
use Sonatra\Bundle\CacheBundle\SonatraCacheBundle;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Sonatra Cache Extension Tests.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraCacheExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testNoConfig()
    {
        $container = $this->createContainer();

        $this->assertFalse($container->hasParameter('sonatra_cache.override_cache_services'));
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
    protected function createContainer(array $configs = array(), array $parameters = array(), array $services = array())
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.bundles' => array(
                'FrameworkBundle' => FrameworkBundle::class,
                'SonatraCacheBundle' => SonatraCacheBundle::class,
            ),
            'kernel.cache_dir' => sys_get_temp_dir().'/sonatra_cache_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => sys_get_temp_dir().'/sonatra_cache_bundle',
            'kernel.charset' => 'UTF-8',
        )));

        $sfExt = new FrameworkExtension();
        $extension = new SonatraCacheExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($extension);

        foreach ($parameters as $name => $value) {
            $container->setParameter($name, $value);
        }

        foreach ($services as $id => $definition) {
            $container->setDefinition($id, $definition);
        }

        $sfExt->load(array(array('annotations' => false)), $container);
        $extension->load($configs, $container);

        $bundle = new SonatraCacheBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
