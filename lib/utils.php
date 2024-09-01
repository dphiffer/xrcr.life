<?php

function img_src($src) {
	$mtime = filemtime(dirname(__DIR__) . "/img/$src");
	$src = get_theme_file_uri("/img/$src?$mtime");
	echo $src;
}

function css_href($src) {
	$mtime = filemtime(dirname(__DIR__) . "/$src");
	$src = get_theme_file_uri("/$src?$mtime");
	echo $src;
}

function js_src($src) {
	$mtime = filemtime(dirname(__DIR__) . "/js/$src");
	$src = get_theme_file_uri("/js/$src?$mtime");
	echo $src;
}

function xrcr_normalize_email($email) {
	$email = trim($email);
	$email = strtolower($email);
	return $email;
}

function xrcr_normalize_phone($phone) {
	$phone = preg_replace('/\D/', '', $phone);
	if (substr($phone, 0, 1) == '1') {
		$phone = substr($phone, 1);
	}
	if (strlen($phone) == 10) {
		$phone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
	}
	return $phone;
}
