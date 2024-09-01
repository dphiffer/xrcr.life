# xrcr.life

Extinction Rebellion Capital District website

## Dependencies

-   [WordPress](https://wordpress.org/)
-   [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/) plugin

## Development dependencies

-   [Docker Desktop](https://www.docker.com/products/docker-desktop/)
-   [node.js](https://nodejs.org/en/) (tested on v20)
-   [Composer](https://getcomposer.org/)

## Dev environment setup

1. Copy `.env.sample` to `.env` and edit the `ACF_PRO_KEY` variable ([more info](https://www.advancedcustomfields.com/pro/))
2. Run the start script: `./bin/start`

## Production setup

Assumes you have filesystem ownership set to the user running the following commands.

```
cd wp-content/themes/xrcr
npm install
npm run build
```

## Image sizes

Image sizes are configured automatically.

-   Thumbnail: 285x176 (cropped)
-   Medium: 720x0
-   Large: 1000x0

## Local dev

1. Set up `/etc/hosts`:
    ```
    127.0.0.1       localhost local.xrcr.life
    ```
2. Add an Apache VirtualHost:
    ```
    <VirtualHost *:80>
        DocumentRoot "/Users/dphiffer/Sites/xrcr.life"
        ServerName local.xrcr.life
    </VirtualHost>
    ```
3. Restart Apache: `sudo apachectl restart`
4. Import MySQL dump
5. Configure `wp-config.php`:

    ```php
    define('WP_HOME', 'http://local.xrcr.life');
    define('WP_SITEURL', 'http://local.xrcr.life');
    define('UPLOADS', 'media');
    define('ACF_LITE', false);

    define('WP_DEBUG', true );
    define('WP_DEBUG_LOG', true );
    define('WP_DEBUG_DISPLAY', false );
    ```

6. Load up http://local.xrcr.life/
