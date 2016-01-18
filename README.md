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
    lifetime: apc cache lifetime (seconds)
    analytics: analytics calculate lifetime (seconds)
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
    google_analytics_account_id: (string)
    google_analytics_view_id: (number)
```

### Step 3: Enable the admin service

Config the bundle in the config.yml:

``` yml
# app/config/config.yml
l_soft_ad:
#   ...
    analytics: analytics calculate lifetime (seconds)
```

### Step 4: Enable in base layout twig

``` twig

 {% if app.session.get('adData') %}
    {% set analytics = app.session.get('adData') %}
        <script>
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-72496853-1', 'auto']);
            _gaq.push(['_trackPageview']);
            _gaq.push(['_trackPageLoadTime']);
            {% for analytic in analytics %}
                _gaq.push(['_setCustomVar',
                    1,                   // This custom var is set to slot #2.  Required parameter.
                    '{{ analytic.ad_name }}',       // The 2nd-level name for your online content categories.  Required parameter.
                    '{{ analytic.domain }}',           // Sets the value of "Sub-section" to "Fashion" for this particular article.  Required parameter.
                    1                    // Sets the scope to page-level.  Optional parameter.
                ]);
            {% endfor %}

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
{% endif %}

```