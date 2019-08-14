<?php

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

function img_src($src) {
	$src = get_theme_file_uri("/img/$src");
	echo $src;
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
	wp_mail($to, $subject, $message);

	wp_redirect('/?join=1#join');
	exit;
}
add_action('wp_ajax_xrcr_join', 'xrcr_join');
add_action('wp_ajax_nopriv_xrcr_join', 'xrcr_join');
