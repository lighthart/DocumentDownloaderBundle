<?php

/*
 * This file is part of the TyHandDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TyHand\DocumentDownloaderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Console\Application;

/**
 * The bundle class
 */
class TyHandDocumentDownloaderBundle extends Bundle
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