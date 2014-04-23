Neobazaar Main Module
=====================

[![Build Status](https://travis-ci.org/kaiohken1982/Neobazaar.png)](https://travis-ci.org/kaiohken1982/Neobazaar)
[![Coverage Status](https://coveralls.io/repos/kaiohken1982/Neobazaar/badge.png)](https://coveralls.io/r/kaiohken1982/Neobazaar)
[![Dependency Status](https://www.versioneye.com/user/projects/52c4ad43ec13757ae600003a/badge.png)](https://www.versioneye.com/user/projects/52c4ad43ec13757ae600003a)
[![Latest Stable Version](https://poser.pugx.org/neobazaar/neobazaar/v/stable.png)](https://packagist.org/packages/neobazaar/neobazaar)
[![Total Downloads](https://poser.pugx.org/neobazaar/neobazaar/downloads.png)](https://packagist.org/packages/neobazaar/neobazaar)
[![Latest Unstable Version](https://poser.pugx.org/neobazaar/neobazaar/v/unstable.png)](https://packagist.org/packages/neobazaar/neobazaar)

Neobazaar is a free classifieds web application.
It is composed by the following modules:

- [Neobazaar Skeleton Application](https://github.com/kaiohken1982/NeobazaarSkeletonApplication)
- **Neobazaar Main Module** (this module)
- [Neobazaar Document Module](https://github.com/kaiohken1982/NeobazaarDocumentModule)
- [Neobazaar User Module](https://github.com/kaiohken1982/NeobazaarUserModule)
- [Neobazaar Mailer Module](https://github.com/kaiohken1982/NeobazaarMailerModule) 
- [Neobazaar Public Application](https://github.com/kaiohken1982/NeobazaarPublicApplication)
- [Neobazaar Sitemap Module](https://github.com/kaiohken1982/NeobazaarSitemapModule)
- [Neobazaar Prerender Listener Module](https://github.com/kaiohken1982/NeobazaarPrerenderListenerModule)

### Duties of this module

- provide database mapping using Doctrine 2 
- provide Web Services with 'static' data (cities, categories etc.)
- provide a main application service capable to return EntityManager and Entity Repositories.

### Install with Composer
 ```
{
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kaiohken1982/Neobazaar.git"
        }
    ],
    "require": {
        ......,
        "neobazaar/neobazaar": "dev-master",
    }
}
 ```
 
### Schema generation and other Doctrine 2 Tool

You must register this module and Doctrine modules in order to user the following console commands in 
config/application.config.php. 
Furthermore you must move into your project root directory.

- ./vendor/bin/doctrine-module orm:schema-tool:create


### Run unit test
 
Please note you must be in the module root.

```
curl -s http://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit 
```

If you have xdebug enabled and you want to see code coverage 
run the command below, it'll create html files in 
Neobazaar\tests\data\coverage

```
./vendor/bin/phpunit --coverage-html data/coverage
```

### TODO

Remove dependency with razor/razor and port here Razor\Doctrine\ORM\* class.
Refactor all that part using a separate Sphinx module.