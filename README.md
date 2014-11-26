Document Downloader Bundle
==========================
A quick, lazy, and somewhat useless bundle to provide a quicker way to produce links to a static pdf document while providing some minor security.

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
Next, since this bundle has a controller action, you need to include the bundle's routing file into the project's main routing file.
```yaml
# app/config/routing.yml
TyHandDocumentDownloader:
    resource: "@TyHandDocumentDownloaderBundle/Resources/config/routing.yml"
    prefix: /documents
    # prefix can be whatever you need it to be
```
Finally, the bundle needs a path to the file containing the list of documents.  By default the bundle will look for it at app/config/file_list.yml.  If you desire the document list file to be there then installation of the bundle is complete; However, if you need the file list to be elsewhere you add a config option to change the location such as the following.
```yaml
# app/config/config.yml
tyhand_document_downloader:
    file_list: /path/relative/to/the/app/folder.yml
```

Usage
-----
