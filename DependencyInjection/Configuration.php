<?php

/*
 * This file is part of the LighthartDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lighthart\DocumentDownloaderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration settings for the bundle
 *
 * @author Tyler Hand <http://github.com/tyhand>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Build the cofig tree
     *
     * @return TreeBuilder The config tree builder object
     */
    public function getConfigTreeBuilder()
    {
        //Make a new builder
        $builder = new TreeBuilder();
        //Setup the config options
        $builder->root('file_list')
            ->prototype('array')
            ->children()
            ->scalarNode('path')->isRequired()->end()
            ->arrayNode('allow')->prototype('scalar')->end()->end()
            ->arrayNode('deny')->prototype('scalar')->end()->end()
            ->end()
        ;

        //Return
        return $builder;
    }
}
