<?php

function img_src($src) {
	$src = get_theme_file_uri("/img/$src");
	echo $src;
}

function css_href($src) {
	$src = get_theme_file_uri("/$src");
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
}

function xrcr_enqueue() {
	wp_enqueue_script(
		'xrcr',
		get_theme_file_uri('js/xrcr.js'),
		array('jquery'),
		'1',
		true
	);
}
add_action('wp_enqueue_scripts', 'xrcr_enqueue');

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
