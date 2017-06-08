<?php

/*
 * This file is part of the LighthartDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lighthart\DocumentDownloaderBundle\Twig\Extension;

use Symfony\Component\Routing\Router;

/**
 * This class extends the base twig extension class to provide a twig extension to create links to document downloads
 *
 * @author Tyler Hand <http://github.com/tyhand>
 */
class DocumentDownloaderExtension extends \Twig_Extension
{
    ///////////////
    // VARIABLES //
    ///////////////

    /**
     * The twig environment
     *
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * Symfony router component
     *
     * @var Router
     */
    private $router;

    //////////////////
    // BASE METHODS //
    //////////////////

    /**
     * Constructor
     *
     * @param Router $router The symfony router component
     */
    public function __construct(Router $router)
    {
        //Set the variables
        $this->router = $router;
    }

    ////////////////////////////
    // TWIG EXTENSION METHODS //
    ////////////////////////////

    /**
     * Get the list of functions this twig extension provides
     *
     * @return array The list of functions keyed by function name
     */
    public function getFunctions()
    {
        //Function definition
        return [
            new \Twig_SimpleFunction('lighthart_docdownloader_url',
                [$this, 'url'],
                [
                    // 'needs_environment' => true,
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    /**
     * Return the name of the extension
     *
     * @return string Extension name
     */
    public function getName()
    {
        return 'lighthart_docdownloader_extension';
    }

    ///////////////
    // FUNCTIONS //
    ///////////////

    /**
     * Create a url for the document with the given name
     *
     * @param  string $name The name of the file in the file list
     *
     * @return string       The url
     */
    public function url($name)
    {
        //Create the link
        return $this->router->generate('LighthartDocDownloaderBundle_getFile', ['name' => $name]);
    }
}
