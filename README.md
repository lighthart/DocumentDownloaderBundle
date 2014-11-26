Document Downloader Bundle
==========================
A quick, lazy, and somewhat useless bundle to provide a quicker way to produce links to a static pdf document

Installing
----------
First add the project to your Symfony project's composer.json, which can be done via the command line like the following.
```bash
$ composer require tyhand/document-downloader-bundle "~0.1"
```
Next, add the bundle to your project's AppKernel.php in the register bundles method.
```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ... The other bundles
        new TyHand\DocumentDownloaderBundle\TyHandDocumentDownloaderBundle()
    )
} 
```
Finally, since this bundle has a controller action, you need to include the bundle's routing file into the project's main routing file.
```yaml
# app/config/routing.yml
TyHandDocumentDownloader:
    resource: "@TyHandDocumentDownloaderBundle/Resources/config/routing.yml"
    prefix: /documents
    # prefix can be whatever you need it to be
```

Usage
-----
