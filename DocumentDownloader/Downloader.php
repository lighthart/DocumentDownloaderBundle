<?php

/*
 * This file is part of the LighthartDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lighthart\DocumentDownloaderBundle\DocumentDownloader;

use Lighthart\DocumentDownloaderBundle\DocumentDownloader\FileListReader;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\TokenStorage;

/**
 * This class is responsible for checking that a file exists and retrieving them if they do
 *
 * @author Tyler Hand <http://github.com/tyhand>
 */
class Downloader
{
    ///////////////
    // VARIABLES //
    ///////////////

    /**
     * The file list reader
     *
     * @var FileListReader
     */
    protected $fileListReader;

    /**
     * The TokenStorage from Symfony
     *
     * @var tokenStorage
     */
    protected $tokenStorage;

    //////////////////
    // BASE METHODS //
    //////////////////

    /**
     * Constructor
     *
     * @param FileListReader  $fileListReader  The file list reader
     * @param TokenStorage $tokenStorage The TokenStorage from symfony
     */
    public function __construct(
        FileListReader $fileListReader,
        TokenStorage   $tokenStorage
    ) {
        $this->fileListReader = $fileListReader;
        $this->tokenStorage   = $tokenStorage;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Get the binary response containing the file aliased by the given name
     *
     * @param  string   $name The key name of the file in the file list config file
     * @param  boolean  $exceptions Flag that if set will throw exceptions instead of returning null
     *
     * @return Response         The Symfony response for the file
     */
    public function getResponseForFile(
        $name,
        $exceptions = true
    ) {
        //Check that the file exists and is accessible
        if (!$this->doesFileExistInList($name) || !$this->doesFileExist($name)) {
            if ($exceptions) {
                throw new NotFoundHttpException('The requested document does not exist');
            } else {
                return null;
            }
        }

        //Check that the current user has access to view the document (only check this if either allow or deny options were set)
        if ($this->hasSecurityRestrictions($name) && !$this->doesUserHaveAccess($name)) {
            if ($exceptions) {
                throw new AccessDeniedException('You do not have permission to access the requested document');
            } else {
                return null;
            }
        }

        //Get the file path
        $path = $this->getFilePath($name);
        if (null === $path) {
            if ($exceptions) {
                throw new NotFoundHttpException('The requested document does not exist');
            } else {
                return null;
            }
        }

        //Create the response
        $response = new BinaryFileResponse($path);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);

        //Return the response
        return $response;
    }

    /**
     * Check whether a given file
     *
     * @param  string  $name The name to check for in the file list
     *
     * @return boolean       Whether the file name appears in the file list
     */
    public function doesFileExistInList($name)
    {
        if (array_key_exists($name, $this->fileListReader->getFileList())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether the actual file exist
     *
     * @param  string  $name The name to check for in the file list
     *
     * @return boolean           Whether the file exist
     */
    public function doesFileExist($name)
    {
        $fileList = $this->fileListReader->getFileList();
        if (array_key_exists($name, $fileList) && isset($fileList[$name]['path'])) {
            if (file_exists($this->getFilePath($name))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks whether a file in the list has security restriction options set
     *
     * @param  string  $name The name of the file in the file list
     *
     * @return boolean       Whether security restrictions options are set or not
     */
    public function hasSecurityRestrictions($name)
    {
        $fileList = $this->fileListReader->getFileList();
        if (isset($fileList[$name])) {
            if (isset($fileList[$name]['allow']) || isset($fileList[$name]['deny'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check whether the current user have the required roles to view the file
     *
     * @param  string  $name The name of the file to check
     *
     * @return boolean       Whether the user has the required roles to view the file
     */
    public function doesUserHaveAccess($name)
    {
        $fileList = $this->fileListReader->getFileList();

        //Get the array of roles
        try {
            $roles = array_map(function ($role) {
                if (
                    $role instanceof \Symfony\Component\Security\Core\Role\SwitchUserRole ||
                    $role instanceof \Symfony\Component\Security\Core\Role\Role
                ) {
                    return $role->getRole();
                } else {
                    return (string) $role;
                }
            }, $this->tokenStorage->getToken()->getRoles());
        } catch (\Exception $e) {
            throw new \Exception('The Document Downloader requires the roles to either be in string format or castable to a string');
        }

        //Check if the user has any roles in the allow group or no roles in the deny group
        if (isset($fileList[$name])) {
            if (isset($fileList[$name]['allow'])) {
                $intersection = array_intersect($roles, $fileList[$name]['allow']);
                if (0 < count($intersection)) {
                    $return = true;
                } else {
                    $return = false;
                }
            } elseif (isset($fileList[$name]['deny'])) {
                $intersection = array_intersect($roles, $fileList[$name]['deny']);
                if (1 > count($intersection)) {
                    $return = true;
                } else {
                    $return = false;
                }
            }
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * Get the path of a file with the given name
     *
     * @param  string $name The name of the file in the file list
     *
     * @return string       The path to the file if it exists
     */
    public function getFilePath($name)
    {
        $fileList = $this->fileListReader->getFileList();
        if (isset($fileList[$name]) && isset($fileList[$name]['path'])) {
            return sprintf('%s/%s', $this->fileListReader->getRootDir(), $fileList[$name]['path']);
        } else {
            return null;
        }
    }
}
