<?php

/*
 * This file is part of the TyHandDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TyHand\DocumentDownloaderBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contains actions to get a file for download
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class ReportController extends ContainerAware
{
    /**
     * Get a file for download
     *
     * @param  string   $name The name of the file to get in the file list file
     *
     * @return Response       The response
     */
    public function getFileAction($name)
    {
        return $this->container->get('tyhand_docdownloader.downloader')->getResponseForFile($name, true);
    }
}