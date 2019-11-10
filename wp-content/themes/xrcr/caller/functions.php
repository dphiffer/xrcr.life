<?php

function xrcr_caller_select() {

	if (! current_user_can('call_contacts')) {
		// Insufficient privileges (show the volunteer sign up form).
		return false;
	}

	$call_type = xrcr_caller_get_type();
	return empty($call_type);
}

function xrcr_caller_redirect() {

	if (! current_user_can('call_contacts')) {
		// Insufficient privileges (show the volunteer sign up form).
		return false;
	}

	if (! empty($_GET['call'])) {
		// Already redirected.
		return false;
	}

	$call_type = xrcr_caller_get_type();
	$call_id = xrcr_caller_pick_id($call_type);

	if (! empty($call_id)) {
		return site_url("/caller/?call=$call_id&type=$call_type");
	}

	return false;
}

function xrcr_caller_valid_types() {
	return array(
		'hfe-follow-up'
	);
}

function xrcr_caller_get_type() {

	if (empty($_GET['type'])) {
		return false;
	}

	if (! in_array($_GET['type'], xrcr_caller_valid_types())) {
		return false;
	}

	return $_GET['type'];
}

function xrcr_caller_pick_id($call_type, $page = 1) {

	if (empty($call_type)) {
		return null;
	}

	$contacts = get_posts(array(
		'post_type' => 'contact',
		'posts_per_page' => 10,
		'paged' => $page,
		'meta_key' => '_score',
		'orderby' => 'meta_value',
		'order' => 'DESC'
	));

	if (empty($contacts)) {
		return null;
	}

	foreach ($contacts as $contact) {
		$phone = get_field('Phone', $contact->ID);
		if (empty($phone)) {
			continue;
		}

		$existing_calls = get_posts(array(
			'post_type' => 'call',
			'meta_key' => 'contact',
			'meta_value' => $contact->ID
		));
		if (xrcr_caller_was_recently_called($existing_calls)) {
			continue;
		}

		$call_id = wp_insert_post(array(
			'post_title' => $contact->post_title,
			'post_type' => 'call',
			'post_status' => 'publish'
		));
		update_field('contact', $contact->ID, $call_id);
		update_field('status', 'pending', $call_id);

		return $call_id;
	}
	return xrcr_caller_pick_id($call_type, $page + 1);
}

function xrcr_caller_was_recently_called($existing_calls) {

	$skip_contact = false;
	$age_day = 60 * 60 * 24;
	$age_week = $age_day * 7;

	if (empty($existing_calls)) {
		return $skip_contact;
	}

	foreach ($existing_calls as $call) {

		$status = get_field('status', $call->ID);
		$call_time = strtotime($call->post_date_gmt);
		$call_age = current_time('timestamp', 'utf') - $call_time;

		// Don't count unpublished calls
		if ($call->post_status != 'publish') {
			continue;
		}

		// Within 24 hours, pending calls are skipped
		if ($status == 'pending' && $call_age < $age_day) {
			$skip_contact = true;
		}

		// Don't call anyone more frequently than a week
		if ($call_age < $age_week) {
			$skip_contact = true;
		}

	}
	return $skip_contact;
}

function xrcr_caller_ready() {

	if (! current_user_can('call_contacts')) {
		// Insufficient privileges (show the volunteer sign up form)
		return false;
	}

	if (empty($_GET['call'])) {
		return false;
	}

	$type = xrcr_caller_get_type();
	$call = get_post($_GET['call']);

	if (empty($type) || empty($call)) {
		return false;
	}

	return $type;
}
