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

if (xrcr_caller_ready()) {
	get_template_part('caller/call');
} else if (xrcr_caller_select()) {
	get_template_part('caller/select');
} else {
	get_template_part('caller/volunteer');
}

get_footer();
