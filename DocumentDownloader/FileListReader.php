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

/**
 * This class reads the file list class given the configuration settings and loads the data.
 *
 * The configuration setting for file list is put into this class and this will use that
 *
 * @author Tyler Hand <http://github.com/tyhand>
 */
class FileListReader
{
    ///////////////
    // VARIABLES //
    ///////////////

    /**
     * The saved file list if the file list has already been read
     *
     * @var array
     */
    protected $fileList;

    /**
     * Flag as to whether the file list has been read
     *
     * @var boolean
     */
    protected $ready;

    /**
     * The root directory
     *
     * @var string
     */
    protected $rootDir;

    //////////////////
    // BASE METHODS //
    //////////////////

    /**
     * Constructor
     *
     * @param string $rootDir The root directory path
     */
    public function __construct(
        $rootDir
    ) {
        //Set the root directory
        $this->rootDir = $rootDir;

        //Init the other variables
        // $this->fileList = null;
        $this->ready = false;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Read the file list configuration
     *
     * @return boolean Whether the init was successful or not
     */
    public function init()
    {
        //Read
        //$this->fileList = $this->read();
        // This used to be read.  Now it is passed from config file

        //Validate

        $valid = $this->validate($this->fileList);

        //Set the ready flag
        $this->ready = $valid;

        //Return
        return $this->ready;
    }

    /**
     * Validate the file list array
     *
     * @return boolean Whether the file list array is valid
     */
    public function validate(array $fileList)
    {
        /**
         * Check that
         * 1) each name is unique
         * 2) each file has a path
         * 3) check that there is only either allow or deny or neither
         */
        $visited = [];
        foreach ($fileList as $name => $info) {
            if (!isset($info['path']) || empty($info['path'])) {
                throw new \Exception(sprintf('Missing required "path" value for file "%s"', $name));
            }

            if (isset($info['allow']) && isset($info['deny'])) {
                throw new \Exception(sprintf('Both "allow" and "deny" present for file "%s"', $name));
            }

            //Check that the file exists
            $filePath = sprintf('%s/%s', $this->rootDir, $info['path']);
            if (!file_exists($filePath)) {
                throw new \Exception(sprintf('File [%s] does not exist', $filePath));
            }

        }

        //Return
        return true;
    }

    /**
     * Get the file list
     *
     * @return array The file list
     */
    public function getFileList()
    {
        if (!$this->ready) {
            $this->init();
        }
        return $this->fileList;
    }

    /**
     * Get the file list
     *
     * @return array The file list
     */
    public function setFileList($fileList)
    {
        $this->fileList = $fileList;
        if (!$this->ready) {
            $this->init();
        }
        return $this->fileList;
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Gets the The path to the file listing file.
     *
     * @return string
     */
    public function getFileListPath()
    {
        return $this->fileListPath;
    }

    /**
     * Sets the The path to the file listing file.
     *
     * @param string $fileListPath the file list path
     *
     * @return self
     */
    public function setFileListPath($fileListPath)
    {
        $this->fileListPath = $fileListPath;

        return $this;
    }

    /**
     * Gets the The root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Sets the The root directory.
     *
     * @param string $rootDir the root dir
     *
     * @return self
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;

        return $this;
    }
}
