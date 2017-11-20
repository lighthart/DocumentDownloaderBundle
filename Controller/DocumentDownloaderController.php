<?php

/*
 * This file is part of the LighthartDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lighthart\DocumentDownloaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contains actions to get a file for download
 *
 * @author Tyler Hand <https://github.com/tyhand>
 * revised lthrt
 */
class DocumentDownloaderController extends Controller
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
        return $this->container->get('lighthart_document_downloader.downloader')->getResponseForFile($name, true);
    }
}
