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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This class handles loading the configuration settings and setting up the related items
 *
 * @author TylerHand <http://github.com/tyhand>
 */
class LighthartDocumentDownloaderExtension extends Extension
{
    /**
     * Load the configurations settings
     *
     * @param  array            $configs   The config options
     * @param  ContainerBuilder $container The container builder
     */
    public function load(
        array            $configs,
        ContainerBuilder $container
    ) {
        //Load the configuration
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);
        $loader        = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        //Setup the file reader
        $fileListReaderDefinition = $container->getDefinition('lighthart_docdownloader.file_list_reader');

        //Set the file list path
        $fileListReaderDefinition->addMethodCall('setFileListPath', [$config['file_list']]);
        $fileListReaderDefinition->addMethodCall('init', []);
    }

    /**
     * Get the configuration for the document downloader bundle
     *
     * @param  array            $config    The array of config settings
     * @param  ContainerBuilder $container The container builder
     *
     * @return Configuration               The bundles configuration
     */
    public function getConfiguration(
        array            $config,
        ContainerBuilder $container
    ) {
        //Create the configruation for the report bundle
        return new Configuration();
    }

    /**
     * Get the alias of the configuration
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'ty_hand_document_downloader';
    }
}
