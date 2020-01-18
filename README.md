# xrcr.life

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
