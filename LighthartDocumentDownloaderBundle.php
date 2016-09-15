<?php

/*
 * This file is part of the LighthartDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lighthart\DocumentDownloaderBundle;

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The bundle class
 */
class LighthartDocumentDownloaderBundle extends Bundle
{
    public function registerCommands(Application $application)
    {
        parent::registerCommands($application);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
