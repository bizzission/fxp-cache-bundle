<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('fxp_cache');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->append($this->getOverrideCacheServicesNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Get override cache services node.
     *
     * @return ArrayNodeDefinition
     */
    protected function getOverrideCacheServicesNode()
    {
        $treeBuilder = new TreeBuilder('override_cache_services');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->fixXmlConfig('override_cache_service')
            ->beforeNormalization()
            ->ifTrue(function ($v) {
                return \is_bool($v);
            })
            ->then(function ($v) {
                return true === $v
                        ? []
                        : ['_override_disabled'];
            })
            ->end()
            ->prototype('scalar')->end()
        ;

        return $node;
    }
}
