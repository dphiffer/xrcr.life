<?php

if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
	add_filter('acf/settings/save_json', function() {
		return __DIR__ . '/fields';
	});
	add_filter('acf/settings/load_json', function() {
		return array(__DIR__ . '/fields');
	});
}

require_once __DIR__ . '/lib/caller.php';
require_once __DIR__ . '/lib/columns.php';
require_once __DIR__ . '/lib/contacts.php';
require_once __DIR__ . '/lib/join.php';
require_once __DIR__ . '/lib/utils.php';

function xrcr_init() {
	require_once __DIR__ . '/lib/post-types.php';
	require_once __DIR__ . '/lib/roles.php';
	register_nav_menu('footer-menu', 'Footer');
	register_nav_menu('nav-bar', 'Navigation bar');
}
add_action('init', 'xrcr_init');

function xrcr_after_setup_theme() {
	add_theme_support('html5', array(
		'gallery',
		'caption'
	));
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'xrcr_after_setup_theme');
