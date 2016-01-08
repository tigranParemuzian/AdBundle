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
imports:
    # ...
    - { resource: @LSoftAdBundle/Resources/config/services.xml }
```

### Step 4: Enable the in twig

Enable the bundle in the twig:

``` twig
# MyBundle/Resources/views/myTwig.html.twig

 {{ render(controller('LSoftAdBundle:Default:index', { 'domain': 'domain', 'zone': 'top' })) }}

```

