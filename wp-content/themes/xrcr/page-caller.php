<?php

if ($call_type = xrcr_ready_to_call()) {
	$call_id = xrcr_get_call_id($call_type);
	wp_redirect("/caller/?call=$call_id&type=$call_type");
	exit;
}

acf_form_head();
get_header();

if (current_user_can('call_contacts') && ! empty($_GET['call'])) {
	get_template_part('call', 'form');
} else {
	get_template_part('call', 'volunteer');
}

get_footer();
