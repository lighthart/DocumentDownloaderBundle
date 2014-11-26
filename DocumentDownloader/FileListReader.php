<?php

/*
 * This file is part of the TyHandDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TyHand\DocumentDownloaderBundle\DocumentDownloader;

use Symfony\Component\Yaml\Parser;

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
     * The path to the file listing file
     *
     * @var string
     */
    protected $fileListPath;

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
    public function __construct($rootDir)
    {
        //Set the root directory
        $this->rootDir = $rootDir;

        //Init the other variables
        $this->fileListPath = null;
        $this->fileList = null;
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
        $this->fileList = $this->read();

        //Validate
        $valid = $this->validate($this->fileList);

        //Set the ready flag
        $this->ready = $valid;

        //Return
        return $this->ready;
    }


    /**
     * Reads the file list configuration
     *
     * @return array The array form of the file list
     */
    public function read()
    {
        //Check that the path was set
        if (null === $this->fileListPath) {
            throw new \Exception('Filepath for the Document Downloader file list is not set');
        }

        //Check that the file exists
        $filePath = sprintf('%s/%s', $this->rootDir, $this->fileListPath);
        if (!file_exists($filePath)) {
            throw new \Exception(sprintf('File [%s] does not exist', $filePath));
        }

        //Create a new YAML parser and read the file
        $parser = new Parser();
        try {
            $fileList = $parser->parse(file_get_contents($filePath));
        } catch(\Exception $e) {
            throw $e;
        }

        //Return the file list
        return $fileList;
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
        $visited = array();
        foreach($fileList as $name => $info) {
            if (in_array($name, $visited)) {
                throw new \Exception(sprintf('Name "%s" is used more than once in the document downloads file list', $name));
            } else {
                $visited[] = $name;
            }

            if (!isset($info['path']) || empty($info['path'])) {
                throw new \Exception(sprintf('Missing required "path" value for file "%s"', $name));
            }

            if (isset($info['allow']) && isset($info['deny'])) {
                throw new \Exception(sprintf('Both "allow" and "deny" present for file "%s"', $name));
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