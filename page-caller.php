<?php

$redirect = xrcr_caller_redirect();
if (! empty($redirect)) {
	wp_redirect($redirect);
	exit;
}

if (have_posts()) {
	the_post();
}

acf_form_head();
get_header();

$template_name = 'volunteer';
if (xrcr_caller_ready()) {
	$template_name = 'form';
} else if (xrcr_caller_select()) {
	$template_name = 'start';
}

get_template_part('caller', $template_name);
get_footer();
