<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\CacheBundle;

use Fxp\Bundle\CacheBundle\DependencyInjection\Compiler\CacheCollectorPass;
use Fxp\Bundle\CacheBundle\DependencyInjection\Compiler\CachePoolPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FxpCacheBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CachePoolPass());

        if ($container->getParameter('kernel.debug')) {
            $container->addCompilerPass(new CacheCollectorPass(), PassConfig::TYPE_BEFORE_REMOVING);
        }
    }
}
