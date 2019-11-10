<?php

$redirect = xrcr_caller_redirect();
if (! empty($redirect)) {
	wp_redirect($redirect);
	exit;
}

acf_form_head();
get_header();

if ($type = xrcr_caller_ready()) {
	get_template_part('caller/call', $type);
} else if (xrcr_caller_select()) {
	get_template_part('caller/select');
} else {
	get_template_part('caller/volunteer');
}

get_footer();
