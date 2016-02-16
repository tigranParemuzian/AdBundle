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
doctrine:
# ...
     orm:
     # ...
        result_cache_driver: apc
# ...
l_soft_ad:
    pattern: pattern of apc cache (string)
    lifetime: apc cache lifetime (seconds)

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

## Enable Analytics
-----------------------

### Step 1: Create google analytics account

http://www.google.com/analytics

### Step 2: Add google analytics clint id and view id

``` yml
# app/config/parameters.yml
parameters:
# ....
    google_analytics_account_id: UA-XXXXXXXX-X # [a link](https://support.google.com/analytics/answer/1032385?hl=en)
    google_analytics_web_client_id: (google analytics web client id token) # [a link](https://console.developers.google.com/apis/credentials)
    google_analytics_view_id: xxxxxxxxx,
```

### Step 3: Enable the admin service

Config the bundle in the config.yml:

``` yml
# app/config/config.yml

# Twig Configuration
twig:
   # ...
    globals:
            google_analytics_account_id: %google_analytics_account_id%


l_soft_ad:
#   ...
    analytics: analytics calculate lifetime (seconds)
```

### Step 4: Enable in base layout twig

``` twig

    <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', '{{ google_analytics_account_id }}', 'auto');
                ga('require', 'displayfeatures');
                {% if app.session.get('adData') %}
                    {% set analytics = app.session.get('adData') %}
                    {% for analytic in analytics %}
                        {% if analytic.index %}
                            ga('set', 'dimension{{ analytic.index }}', '{{ analytic.domain }}');
                        {% endif %}
                    {% endfor %}
                {% endif %}
                ga('send', 'pageview');
        {{ app.session.set('adData', null) }}
    </script>

```

## Enable File in admin dashboard
-----------------------

### Step 1: Create google analytics account

Create web/uploads/l_soft_ad_images directory

### Step 2: Enable form twig widget

``` yml
# app/config/config.yml

twig:
    # ....
    form:
        resources: ['LSoftAdBundle:Form:ad_file.html.twig']
    # ....

```

### Step 3: Enable the bundle routing

Config the bundle in the routing.yml:

``` yml
# app/config/routing.yml

# ...
lsoft_ads:
    resource: "@LSoftAdBundle/Controller/"
    type:     annotation
    prefix:   /
# ...

```