<?php

if (defined('ACF_LITE') && ACF_LITE) {
	require_once __DIR__ . '/fields.php';
}

function img_src($src) {
	$src = get_theme_file_uri("/img/$src");
	echo $src;
}

function css_href($src) {
	$src = get_theme_file_uri("/$src");
	echo $src;
}

function js_src($src) {
	$src = get_theme_file_uri("/js/$src");
	echo $src;
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
}
add_action('after_setup_theme', 'xrcr_after_setup_theme');

function xrcr_init() {
	register_post_type('contact', array(
		'public' => false
	));
	$labels = array(
		'name'               => 'Events',
		'singular_name'      => 'Event',
		'menu_name'          => 'Calendar',
		'name_admin_bar'     => 'Calendar',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Event',
		'new_item'           => 'New Calendar Event',
		'edit_item'          => 'Edit Calendar Event',
		'view_item'          => 'View Calendar Event',
		'all_items'          => 'All Calendar Events',
		'search_items'       => 'Search Calendar Events',
		'parent_item_colon'  => 'Parent Calendar Events:',
		'not_found'          => 'No Calendar Events found.',
		'not_found_in_trash' => 'No Calendar Events found in Trash.',
	);
	$args = array(
		'labels'             => $labels,
		'description'        => 'Calendar Events',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'event'),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 2,
		'supports'           => array('title')
	);
	register_post_type('event', $args);

	register_nav_menu('footer-menu', 'Footer');
}
add_action('init', 'xrcr_init');

function xrcr_join() {

	$to = 'info@xrcr.life';
	$subject = 'XRCR join submission';
	$message = print_r($_POST, true);
	if ($_SERVER['HTTP_HOST'] != 'local.xrcr.life') {
		wp_mail($to, $subject, $message);
	}

	if (! empty($_POST['email'])) {
		$post_id = wp_insert_post(array(
			'post_type' => 'contact',
			'post_title' => $_POST['email']
		));
		update_post_meta($post_id, 'first_name', $_POST['first_name']);
		update_post_meta($post_id, 'last_name', $_POST['last_name']);
		update_post_meta($post_id, 'phone', $_POST['phone']);
		update_post_meta($post_id, 'zip', $_POST['zip']);
	}

	wp_redirect('/?join=1#join');
	exit;
}
add_action('wp_ajax_xrcr_join', 'xrcr_join');
add_action('wp_ajax_nopriv_xrcr_join', 'xrcr_join');

function xrcr_export() {
	$posts = get_posts(array(
		'post_type' => 'contact',
		'posts_per_page' => -1,
		'post_status' => 'any'
	));
	$fh = fopen('php://stdout', 'w');
	fputcsv($fh, array(
		'email',
		'first_name',
		'last_name',
		'phone',
		'zip'
	));
	foreach ($posts as $post) {
		$row = array($post->post_title);
		$row[] = get_post_meta($post->ID, 'first_name', true);
		$row[] = get_post_meta($post->ID, 'last_name', true);
		$row[] = get_post_meta($post->ID, 'phone', true);
		$row[] = get_post_meta($post->ID, 'zip', true);
		fputcsv($fh, $row);
	}
	fclose($fh);
	exit;
}

if (defined('WP_CLI') && WP_CLI) {
	WP_CLI::add_command('contacts', 'xrcr_export');
}
