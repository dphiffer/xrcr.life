<?php

if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
}

if (defined('ACF_LITE') && ACF_LITE) {
	require_once __DIR__ . '/fields.php';
}

function xrcr_after_setup_theme() {
	add_theme_support('html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption'
	));
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'xrcr_after_setup_theme');

function xrcr_init() {
	require_once __DIR__ . '/post-types.php';
	require_once __DIR__ . '/roles.php';
	register_nav_menu('footer-menu', 'Footer');
}
add_action('init', 'xrcr_init');

require_once __DIR__ . '/caller/functions.php';
require_once __DIR__ . '/lib/columns.php';
require_once __DIR__ . '/lib/contacts.php';
require_once __DIR__ . '/lib/join.php';
require_once __DIR__ . '/lib/utils.php';
