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
