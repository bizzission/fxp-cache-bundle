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

use Sonatra\Bundle\CacheBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * Configuration Tests.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testNoConfig()
    {
        $config = array();
        $processor = new Processor();
        $configuration = new Configuration(array(), array());
        $res = $processor->processConfiguration($configuration, array($config));

        $valid = array(
            'override_cache_services' => array(),
        );

        $this->assertSame($valid, $res);
    }

    public function testOverrideCacheServicesConfig()
    {
        $config = array(
            'override_cache_services' => array(
                'cache.adapter.filesystem',
            ),
        );
        $processor = new Processor();
        $configuration = new Configuration(array(), array());
        $res = $processor->processConfiguration($configuration, array($config));

        $valid = array(
            'override_cache_services' => array(
                'cache.adapter.filesystem',
            ),
        );

        $this->assertSame($valid, $res);
    }

    public function testOverrideCacheServicesConfigWithTrueValue()
    {
        $config = array(
            'override_cache_services' => true,
        );
        $processor = new Processor();
        $configuration = new Configuration(array(), array());
        $res = $processor->processConfiguration($configuration, array($config));

        $valid = array(
            'override_cache_services' => array(),
        );

        $this->assertSame($valid, $res);
    }

    public function testOverrideCacheServicesConfigWithFalseValue()
    {
        $config = array(
            'override_cache_services' => false,
        );
        $processor = new Processor();
        $configuration = new Configuration(array(), array());
        $res = $processor->processConfiguration($configuration, array($config));

        $valid = array(
            'override_cache_services' => array(
                '_override_disabled',
            ),
        );

        $this->assertSame($valid, $res);
    }
}
