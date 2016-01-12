Ad widget for sonata admin.

## Installation
-----------------------

### Step1: Download LsoftAdBundle using composer

Add NotificationBundle in your composer.json:

```json
{
    "require": {
        "lsoft/ad-bundle": "dev-master",
    }
}
```

Now update composer.

Composer will install the bundle to your project's `vendor/lsoft` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new LSoft\AdBundle\LSoftAdBundle(),
    );
}
```

### Step 3: Enable the admin service

Config the bundle in the config.yml:

``` yml
# app/config/config.yml
l_soft_ad:
    pattern: pattern of apc cache (string)
    lifetime: apc cache lifetime (int)
```

### Step 4: Update database

Enable the bundle in the twig:

``` cmd
$ php app/console doctrine:schema:update --force

```

### Step 5: Create Ad use sonata admin

Open sonata admin find LSoft Ad group and create Ad, Domain and Ads provider.
Domain is name of your page and zone is position of ad


### Step 6: Enable the in twig

Enable the bundle in the twig:

``` twig
# MyBundle/Resources/views/myTwig.html.twig

 {{ render(controller('LSoftAdBundle:Default:index', { 'domain': 'domain', 'zone': 'top' })) }}

```

