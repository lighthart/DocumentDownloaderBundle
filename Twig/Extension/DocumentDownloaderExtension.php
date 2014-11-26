<?php

/*
 * This file is part of the TyHandDocumentDownloaderBundle package.
 *
 * (c) Tyler Hand <http://github.com/tyhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TyHand\DocumentDownloaderBundle\Twig\Extension;

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
     * Initialize
     *
     * @param  Twig_Environment $environment The twig environment object
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }


    /**
     * Get the list of functions this twig extension provides
     *
     * @return array The list of functions keyed by function name
     */
    public function getFunctions()
    {
        //Function definition
        return array(
            'tyhand_docdownloader_url' => new \Twig_Function_Method($this, 'url',  array('is_safe' => array('html')))
        );
    }


    /**
     * Return the name of the extension
     *
     * @return string Extension name
     */
    public function getName() 
    {
        return 'tyhand_docdownloader_extension';
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
        return $this->router->generate('TyHandDocDownloaderBundle_getFile', array('name' => $name));
    }
}