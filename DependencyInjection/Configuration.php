<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonatra_cache');

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
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('override_cache_services');
        $node
            ->fixXmlConfig('override_cache_service')
            ->beforeNormalization()
                ->ifTrue(function ($v) {
                    return is_bool($v);
                })
                ->then(function ($v) {
                    return true === $v
                        ? array()
                        : array('_override_disabled');
                })
            ->end()
            ->prototype('scalar')->end()
        ;

        return $node;
    }
}
