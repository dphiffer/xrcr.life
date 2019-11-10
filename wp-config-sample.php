<?php

define('WP_HOME', 'https://xrcr.life');
define('WP_SITEURL', 'https://xrcr.life');
define('UPLOADS', 'media');

define('DB_NAME', 'xrcr_life');
define('DB_USER', 'xrcr_life');
define('DB_PASSWORD', 'password_here');
define('DB_HOST', '127.0.0.1');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

$table_prefix = 'wp_';

define('ACF_LITE', true);
define('WP_DEBUG', false);

if (! defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
}

require_once(ABSPATH . 'wp-settings.php');
