<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\CacheBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Replace the symfony cache adapters by the fxp cache adapters for all services
 * with the "cache.pool" tag.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class CachePoolPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private static $stBase = 'Fxp\Component\Cache\Adapter\\';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $availables = $this->getAvailableServices($container);

        foreach ($container->findTaggedServiceIds('cache.pool') as $id => $attributes) {
            $def = $container->getDefinition($id);
            $name = substr(strrchr($def->getClass(), '\\'), 1);

            if ($this->endsWith($name, 'Adapter')) {
                $class = self::$stBase.$name;

                if (class_exists($class) && (empty($availables) || \in_array($id, $availables, true))) {
                    $def->setClass($class);
                }
            }
        }
    }

    /**
     * Check if the string ends with.
     *
     * @param string $haystack The haystack
     * @param string $needle   The needle
     *
     * @return bool
     */
    protected function endsWith(string $haystack, string $needle): bool
    {
        $length = \strlen($needle);

        return $length > 0 && (substr($haystack, -$length) === $needle);
    }

    /**
     * Get the availables service ids.
     *
     * @param ContainerBuilder $container The container
     *
     * @return string[]
     */
    private function getAvailableServices(ContainerBuilder $container): array
    {
        $availables = $container->getParameter('fxp_cache.override_cache_services');

        $container->getParameterBag()->remove('fxp_cache.override_cache_services');

        return $availables;
    }
}
