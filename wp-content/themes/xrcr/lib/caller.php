<?php

function xrcr_caller_volunteer() {
	$to = get_field('xrcr_caller_coordinator', 'options');
	if (empty($to)) {
		$ok = -1;
	} else if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone'])) {
		$ok = 0;
	} else {
		$ok = 1;
		wp_mail($to, 'Caller volunteer submission', print_r($_POST, true));
	}
	wp_redirect(site_url("/caller/?ok=$ok"));
	exit;
}
add_action('wp_ajax_xrcr_caller_volunteer', 'xrcr_caller_volunteer');
add_action('wp_ajax_nopriv_xrcr_caller_volunteer', 'xrcr_caller_volunteer');

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
		return site_url("/caller/?call=$call_id");
	}

	if (! empty($call_type)) {
		return site_url("/caller/?done=$call_type");
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

	if ($call_type == 'hfe-follow-up') {
		$contacts = get_posts(array(
			'post_type' => 'contact',
			'posts_per_page' => 10,
			'paged' => $page,
			'meta_key' => 'HFE_Talk_Date',
			'orderby' => 'meta_value',
			'order' => 'DESC',
			'meta_query' => array(
				array(
					'key' => 'NVDA_Training_Date',
					'value' => ''
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'attributes',
					'field' => 'slug',
					'terms' => 'circle-member',
					'operator' => 'NOT IN'
				)
			)
		));
	} else {
		return null;
	}

	if (empty($contacts)) {
		return null;
	}

	foreach ($contacts as $contact) {

		$phone = get_field('Phone', $contact->ID);
		if (empty($phone)) {
			continue;
		}

		if ($call_type == 'hfe-follow-up') {
			$hfe_date = get_field('HFE_Talk_Date', $contact->ID);
			if (empty($hfe_date)) {
				continue;
			}
		}

		$existing_calls = get_posts(array(
			'post_type' => 'call',
			'meta_key' => 'contact',
			'meta_value' => $contact->ID,
			'tax_query' => array(
				array(
					'taxonomy' => 'call_type',
					'field' => 'slug',
					'terms' => $call_type,
				)
			)
		));
		if (xrcr_caller_was_recently_called($existing_calls)) {
			continue;
		}

		$notes = '';
		$call_history = xrcr_caller_call_history($contact->ID);
		if (! empty($call_history)) {
			$last_call = $call_history[0];
			$notes = get_field('caller_notes', $last_call->ID);
		}

		$term = get_term_by('slug', $call_type, 'call_type');
		$call_id = wp_insert_post(array(
			'post_title' => "Pending call",
			'post_type' => 'call',
			'post_status' => 'publish',
			'tax_input' => array(
				'call_type' => array($term->term_taxonomy_id)
			)
		));
		update_field('contact', $contact->ID, $call_id);
		update_field('status', 'pending', $call_id);
		update_field('caller_notes', $notes, $call_id);
		xrcr_caller_save_post($call_id);

		return $call_id;
	}
	return xrcr_caller_pick_id($call_type, $page + 1);
}

function xrcr_caller_save_post($post_id) {

	// Sets the post_title.

	$post_type = get_post_type($post_id);
	if ($post_type != 'call') {
		return $post_id;
	}

	$status = get_field('status', $post_id);
	$status = str_replace('_', ' ', $status);
	$status = ucfirst($status);

	$contact_id = get_field('contact', $post_id);
	$email = trim(get_field('email', $contact_id));
	$first_name = trim(get_field('first_name', $contact_id));
	$last_name = trim(get_field('last_name', $contact_id));

	$name = "$first_name $last_name";
	if (empty($last_name)) {
		$name = $first_name;
	}
	if (empty($first_name)) {
		$name = $email;
	}

	$current_user = wp_get_current_user();
	$caller = $current_user->user_login;

	$post_title = "$status: $name ($caller)";

	// Avoid infinite loops
	remove_action('save_post', 'xrcr_caller_save_post');

	wp_update_post(array(
		'ID' => $post_id,
		'post_title' => $post_title
	));

	// Ok, we should've avoided an infinite loop
	add_action('save_post', 'xrcr_caller_save_post');

	return $post_id;
}
add_action('save_post', 'xrcr_caller_save_post');
add_action('acf/save_post', 'xrcr_caller_save_post');

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

	$call = get_post($_GET['call']);

	if (empty($call)) {
		return false;
	}

	return $call;
}

function xrcr_caller_context($call, $call_type_term) {

	$contact_id = get_field('contact', $call->ID);
	$context = array();

	$event_fields = array(
		'HFE_Talk_Date' => 'HFE Talk',
		'HFE_Trained_Date' => 'HFE Talk Training',
		'NVDA_Training_Date' => 'NVDA Training',
		'NVDATrainerTraining_Date' => 'NVDA Trainer Training'
	);
	foreach ($event_fields as $event_field => $event_name) {
		$date = get_field($event_field, $contact_id);
		if (! empty($date)) {
			$icon = '<i class="fa fa-check-circle"></i>';
			$time = strtotime($date);
			$date = date('M j, Y', $time);
			$context[$time] = "$icon Attended an $event_name on <b>$date</b>.";
		}
	}

	if (! empty($call_type_term)) {
		if ($call_type_term->slug == 'hfe-follow-up') {
			$nvda_events = xrcr_caller_upcoming_nvda_events();
			if (! empty($nvda_events)) {
				$icon = '<i class="fa fa-calendar-alt"></i>';
				$date = get_field('time', $nvda_events[0]->ID);
				$time = strtotime($date);
				$date = date('M j, Y', $time);
				$link = '<a href="/#events" target="_blank">Next NVDA training</a>';
				$context[$time] = "$icon $link is on <b>$date</b>.";
			}
		}
	}

	$call_history = xrcr_caller_call_history($contact_id);
	if (! empty($call_history)) {
		foreach ($call_history as $call) {
			$icon = '<i class="fa fa-phone-alt"></i>';
			$caller = get_user_by('ID', $call->post_author);
			if (! empty($caller)) {
				$caller = $caller->user_login;
			} else {
				$caller = 'unknown';
			}
			$link = "<a href=\"/caller/?call=$call->ID\">Called by $caller</a>";
			$time = strtotime($call->post_date);
			$date = date('M j, Y', $time);
			$context[$time] = "$icon $link on <b>$date</b></a>.";
		}
	}

	ksort($context);
	return $context;
}

function xrcr_caller_call_history($contact_id) {
	return get_posts(array(
		'post_type' => 'call',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'contact',
				'value' => $contact_id
			),
			array(
				'key' => 'status',
				'value' => 'called'
			)
		)
	));
}

function xrcr_caller_upcoming_nvda_events() {
	return get_posts(array(
		'post_type' => 'event',
		'posts_per_page' => 1,
		'meta_key' => 'time',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => 'time',
				'compare' => '>=',
				'value' => current_time("Y-m-d 00:00:00")
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'event_type',
				'field' => 'slug',
				'terms' => 'nvda-training',
			)
		)
	));
}
