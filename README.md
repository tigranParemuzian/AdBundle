Ad widget for sonata admin.

## Installation
-----------------------

### Step1: Download LsoftAdBundle using composer

Add NotificationBundle in your composer.json:

```js
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
        new Lsoft\AdBundle\LsoftAdBundleBundle(),
    );
}
```

