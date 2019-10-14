<?php

if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
}

if (defined('ACF_LITE') && ACF_LITE) {
	require_once __DIR__ . '/fields.php';
}

function img_src($src) {
	$mtime = filemtime(__DIR__ . "/img/$src");
	$src = get_theme_file_uri("/img/$src?$mtime");
	echo $src;
}

function css_href($src) {
	$mtime = filemtime(__DIR__ . "/$src");
	$src = get_theme_file_uri("/$src?$mtime");
	echo $src;
}

function js_src($src) {
	$mtime = filemtime(__DIR__ . "/js/$src");
	$src = get_theme_file_uri("/js/$src?$mtime");
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
	add_theme_support('post-thumbnails');
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

	$redirect_path = isset($_POST['redirect']) ? $_POST['redirect'] : '/';
	if (substr($redirect_path, 0, 1) != '/') {
		$redirect_path = '/';
	}
	if (strpos($redirect_path, '?') === false) {
		$redirect_path = "$redirect_path?join=1#join";
	} else {
		$redirect_path = "$redirect_path&join=1#join";
	}
	$redirect = get_site_url() . $redirect_path;

	if ($_SERVER['HTTP_HOST'] != 'local.xrcr.life') {
		$to = 'info@xrcr.life';
		$subject = 'XRCR join submission';
		$message = print_r($_POST, true);
		wp_mail($to, $subject, $message);
	}

	$saved = false;
	if (! empty($_POST['email'])) {
		$post_id = wp_insert_post(array(
			'post_type' => 'contact',
			'post_status' => 'draft'
		));
		if (! empty($post_id)) {
			update_post_meta($post_id, 'first_name', $_POST['first_name']);
			update_post_meta($post_id, 'last_name', $_POST['last_name']);
			update_post_meta($post_id, 'phone', $_POST['phone']);
			update_post_meta($post_id, 'zip', $_POST['zip']);
			update_post_meta($post_id, 'email', $_POST['email']);

			$post_title = "{$_POST['last_name']}, {$_POST['first_name']}";
			if (empty($_POST['last_name'])) {
				$post_title = $_POST['first_name'];
			}

			wp_update_post(array(
				'ID' => $post_id,
				'post_title' => $post_title
			));
			$saved = true;
		}
	}

	if (! empty($_POST['ajax'])) {
		header('Content-Type: application/json');
		if ($saved) {
			echo json_encode(array(
				'ok' => 1,
				'feedback' => 'Thank you for signing up!'
			));
		} else {
			echo json_encode(array(
				'ok' => 0,
				'feedback' => 'There was a problem with saving your submission.'
			));
		}
	} else {
		wp_redirect($redirect);
	}

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
