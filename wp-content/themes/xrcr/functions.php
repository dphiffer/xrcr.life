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
	require_once __DIR__ . '/post-types.php';
	register_nav_menu('footer-menu', 'Footer');
}
add_action('init', 'xrcr_init');

function xrcr_contact_title($post_id) {

	$post_type = get_post_type($post_id);
	if ($post_type != 'contact') {
		return;
	}

	// Avoid infinite loops
	remove_action('save_post', 'xrcr_contact_title');

	$first_name = get_field('first_name', $post_id);
	$last_name = get_field('last_name', $post_id);

	wp_update_post(array(
		'ID' => $post_id,
		'post_title' => "$last_name, $first_name"
	));
}
add_action('save_post', 'xrcr_contact_title');

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
			'post_title' => "{$_POST['last_name']}, {$_POST['first_name']}",
			'post_status' => 'publish'
		));
		update_post_meta($post_id, 'first_name', $_POST['first_name']);
		update_post_meta($post_id, 'last_name', $_POST['last_name']);
		update_post_meta($post_id, 'phone', $_POST['phone']);
		update_post_meta($post_id, 'zip', $_POST['zip']);
		update_post_meta($post_id, 'email', $_POST['email']);
	}

	wp_redirect('/?join=1#join');
	exit;
}
add_action('wp_ajax_xrcr_join', 'xrcr_join');
add_action('wp_ajax_nopriv_xrcr_join', 'xrcr_join');

function xrcr_export() {
	$posts = get_posts(array(
		'post_type' => 'contact',
		'posts_per_page' => -1
	));
	$fh = fopen('php://stdout', 'w');

	$fields = array(
		'id',
		'email',
		'first_name',
		'last_name',
		'phone',
		'zip',
		'house_meeting_host',
		'willing_arrest',
		'action_volunteer',
		'art_volunteer',
		'outreach_volunteer',
		'action_circle',
		'regen_circle',
		'media_circle',
		'infra_circle',
		'skills',
		'reference',
		'feedback',
		'oct_7_nyc',
		'input_notes'
	);

	fputcsv($fh, $fields);

	foreach ($posts as $post) {
		$row = array($post->ID);
		foreach ($fields as $field) {
			if ($field == 'id') {
				continue;
			}
			$row[] = get_field($field, $post->ID);
		}
		fputcsv($fh, $row);
	}
	fclose($fh);
	exit;
}

if (defined('WP_CLI') && WP_CLI) {
	WP_CLI::add_command('contacts', 'xrcr_export');
}
