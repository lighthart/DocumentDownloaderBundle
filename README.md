Document Downloader Bundle
==========================
A quick, lazy, and somewhat useless bundle to provide a quicker way to produce links to a static pdf document while providing some minor security.  Orginally by thand, but he stopped maintaining it

Installing
----------
First add the project to your Symfony project's composer.json, which can be done via the command line like the following.
```bash
$ composer require lighthart/document-downloader-bundle "~0.1"
```

Next, add the bundle to your project's AppKernel.php in the register bundles method.
```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ... The other bundles
        new Lighthart\DocumentDownloaderBundle\LighthartDocumentDownloaderBundle()
    )
} 
```

Next, since this bundle has a controller action, you need to include the bundle's routing file into the project's main routing file.
```yaml
# app/config/routing.yml
LighthartDocumentDownloader:
    resource: "@LighthartDocumentDownloaderBundle/Resources/config/routing.yml"
    prefix: /documents
    # prefix can be whatever you need it to be
```

Finally, the bundle needs a path to the file containing the list of documents.  By default the bundle will look for it at app/config/file_list.yml.  If you desire the document list file to be there then installation of the bundle is complete; However, if you need the file list to be elsewhere you add a config option to change the location such as the following.
```yaml
# app/config/config.yml
lighthart_document_downloader:
    file_list: /path/relative/to/the/app/folder.yml
```

Usage
-----
### Basic
To create a link to a static document, first add the document to the file list yaml.
```yaml
# file_list.yml
lighthart_document_downloader:
    file1alias:
        path: ../Resources/MyPDF1.pdf
        deny: [ROLE_DENY]
    file2alias:
        path: ../Resources/MyPDF2.pdf
    file3alias:
        path: ../Resources/MyPDF3.pdf
```

Then import this file into your config.yml:
```yaml
imports:
    - { resource: "file_list.yml" }
```

Alternatively the 'lighthart_document_downloader' block can be put straight into the config, or imported any other relevant way.

Then in the twig file where you want to have a link to the document use the twig function provided in the bundle to create something along the lines of the following.
```twig
<a target="_blank" href="{{ lighthart_docdownloader_url('my_pdf') }}">My PDF</a>
```

### Restricting By Roles
There are two ways to restrict by roles, 'allow' and 'deny'.  Allow sets a list of roles that a user needs at least one of to retrieve the document; Whereas Deny sets a list of roles that if the user has any of them then they cannot retrieve the document.

For example, if given the following example config:
```yaml
# file_list.yml
my_pdf:
    path: ../Resources/MyPDF.pdf
    allow:
        - ROLE_USER

supervisor_notice:
    path: ../Resources/SupervisorNotice.pdf
    allow:
        - ROLE_SUPERVISOR
```
A user with the roles ['ROLE_USER', 'ROLE_SUPERVISOR'] can view both documents, but a user with the roles ['ROLE_USER'] can only view the my_pdf document.  If the link to supervisor_notice was somehow visible to the user without the supervisor role, a 403 will be returned if they try to retrieve it.

Another example this time for deny:
```yaml
# file_list.yml
coup_detat_plans:
    path: ../Resources/donotlookatthis.pdf
    deny:
        - ROLE_SUPERVISOR
```
In this case any user with 'ROLE_SUPERVISOR' cannot view the document.

Currently the 'allow' option and the 'deny' option cannot be used at the same time for a single file.

