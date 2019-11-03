<?php

if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
}

if (defined('ACF_LITE') && ACF_LITE) {
	require_once __DIR__ . '/fields.php';
}

function img_src($src) {
	$mtime = filemtime(__DIR__ . "/img/$src");
	$src = get_theme_file_uri("/img/$src?$mtime");
	echo $src;
}

function css_href($src) {
	$mtime = filemtime(__DIR__ . "/$src");
	$src = get_theme_file_uri("/$src?$mtime");
	echo $src;
}

function js_src($src) {
	$mtime = filemtime(__DIR__ . "/js/$src");
	$src = get_theme_file_uri("/js/$src?$mtime");
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
	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'xrcr_after_setup_theme');

function xrcr_init() {
	require_once __DIR__ . '/post-types.php';
	register_nav_menu('footer-menu', 'Footer');
}
add_action('init', 'xrcr_init');

function xrcr_get_contact_id($email) {

	if (empty($email)) {
		return null;
	}
	$email = xrcr_normalize_email($email);

	global $wpdb;
	$post_id = $wpdb->get_var($wpdb->prepare("
		SELECT post_id
		FROM wp_postmeta
		WHERE meta_key = 'email'
		  AND meta_value = %s
	", $email));

	if (empty($post_id)) {
		return null;
	}

	return intval($post_id);
}

function xrcr_update_contact($post_id) {

	// Sets the post_title and normalizes the email address, and assigns a score
	// based on volunteering availability.

	$post_type = get_post_type($post_id);
	if ($post_type != 'contact') {
		return $post_id;
	}

	$first_name = get_field('first_name', $post_id);
	$last_name = get_field('last_name', $post_id);
	$email = get_field('email', $post_id);
	$normalized_email = xrcr_normalize_email($email);

	if ($email != $normalized_email) {
		$email = $normalized_email;
		update_field('email', $email, $post_id);
	}

	$post_title = "$last_name, $first_name";
	if (empty($last_name)) {
		$post_title = $first_name;
	}
	if (empty($first_name)) {
		$post_title = $email;
	}

	$score = xrcr_get_contact_score($post_id);
	update_post_meta($post_id, '_score', $score);

	// Avoid infinite loops
	remove_action('save_post', 'xrcr_update_contact');

	wp_update_post(array(
		'ID' => $post_id,
		'post_title' => $post_title
	));

	// Ok, we should've avoided an infinite loop
	add_action('save_post', 'xrcr_update_contact');

	return $post_id;
}
add_action('save_post', 'xrcr_update_contact');

function xrcr_get_contact_score($post_id) {
	$score = 0;
	$fields = get_fields($post_id);
	foreach ($fields as $field => $value) {
		if ($field == 'Avail_E_events' && ! empty($value)) {
			$score += 1;
		} else if ($field == 'Avail_D_projects' && ! empty($value)) {
			$score += 2;
		} else if ($field == 'Avail_C_2-3hrs/wk' && ! empty($value)) {
			$score += 2;
		} else if ($field == 'Avail_B_4-8hrs/wk' && ! empty($value)) {
			$score += 4;
		} else if ($field == 'Avail_A_8+hrs/wk' && ! empty($value)) {
			$score += 8;
		} else if (substr($field, 0, 8) == 'Interest' && ! empty($value)) {
			$score += 1;
		}
	}
	return $score;
}

function xrcr_join() {

	$redirect_path = isset($_POST['redirect']) ? $_POST['redirect'] : '/';
	if (substr($redirect_path, 0, 1) != '/') {
		$redirect_path = '/';
	}
	if (strpos($redirect_path, '?') === false) {
		$redirect_path = "$redirect_path?join=1#join";
	} else {
		$redirect_path = "$redirect_path&join=1#join";
	}
	$redirect = get_site_url() . $redirect_path;

	if ($_SERVER['HTTP_HOST'] != 'local.xrcr.life') {
		$to = 'info@xrcr.life';
		$subject = 'XRCR join submission';
		$message = print_r($_POST, true);
		wp_mail($to, $subject, $message);
	}

	$saved = false;
	if (! empty($_POST['email'])) {
		$post_id = xrcr_get_contact_id($_POST['email']);
		if (empty($post_id)) {
			$post_id = wp_insert_post(array(
				'post_type' => 'contact',
				'post_status' => 'publish'
			));
		}
		if (! empty($post_id)) {
			update_field('first_name', $_POST['first_name'], $post_id);
			update_field('last_name', $_POST['last_name'], $post_id);
			update_field('Phone', $_POST['Phone'], $post_id);
			update_field('zip_code', $_POST['zip_code'], $post_id);
			update_field('email', $_POST['email'], $post_id);
			xrcr_update_contact($post_id);
			$saved = true;
		}
	}

	if (! empty($_POST['ajax'])) {
		header('Content-Type: application/json');
		if ($saved) {
			echo json_encode(array(
				'ok' => 1,
				'feedback' => 'Thank you for signing up!'
			));
		} else {
			echo json_encode(array(
				'ok' => 0,
				'feedback' => 'There was a problem with saving your submission.'
			));
		}
	} else {
		wp_redirect($redirect);
	}

	exit;
}
add_action('wp_ajax_xrcr_join', 'xrcr_join');
add_action('wp_ajax_nopriv_xrcr_join', 'xrcr_join');

function xrcr_contact_headers() {

	$fields_path = __DIR__ . '/fields.json';
	if (! file_exists($fields_path)) {
		echo "Error: could not find fields.json\n";
		exit;
	}

	$fields_json = file_get_contents($fields_path);
	$fields = json_decode($fields_json, 'as hash');
	$headers = array();

	foreach ($fields as $fieldset) {
		if ($fieldset['title'] == 'Contact Details') {
			foreach ($fieldset['fields'] as $field) {
				$headers[] = $field['name'];
			}
			break;
		}
	}

	return $headers;
}

function xrcr_normalize_email($email) {
	$email = trim($email);
	$email = strtolower($email);
	return $email;
}

function xrcr_add_contact_score_column($columns) {
	$new_columns = array();
	foreach ($columns as $key => $value) {
		if ($key == 'date') {
			$new_columns['score'] = 'Score';
		}
		$new_columns[$key] = $value;
	}
	return $new_columns;
}
add_filter('manage_contact_posts_columns', 'xrcr_add_contact_score_column');

function xrcr_add_contact_score_data($column, $post_id) {
	if ($column == 'score') {
		echo get_post_meta($post_id, '_score', true);
	}
}
add_action('manage_contact_posts_custom_column', 'xrcr_add_contact_score_data', 10, 2);

function xrcr_contact_score_column_sortable($columns) {
	$columns['score'] = 'score';
	return $columns;
}
add_filter('manage_edit-contact_sortable_columns', 'xrcr_contact_score_column_sortable');

function xrcr_add_custom_column_sort_request() {
	add_filter('request', 'xrcr_add_custom_column_do_sortable');
}
add_action('load-edit.php', 'xrcr_add_custom_column_sort_request');

function xrcr_add_custom_column_do_sortable($vars) {

	if (isset($vars['post_type']) && $vars['post_type'] == 'contact') {
		if (isset($vars['orderby']) && $vars['orderby'] == 'score') {

			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_score',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

function xrcr_migrate_contacts() {

	global $wpdb;

	$curr_version = 2;
	$option_key = 'xrcr_contacts_migration_version';

	$version = get_option($option_key, 0);
	$version = intval($version);

	if ($version < 1) {
		$wpdb->update('wp_postmeta', array(
			'meta_key' => 'zip_code'
		), array(
			'meta_key' => 'zip'
		));
		$wpdb->update('wp_postmeta', array(
			'meta_key' => 'Phone'
		), array(
			'meta_key' => 'phone'
		));
		$results = $wpdb->get_results("
			SELECT pm.post_id AS post_id, pm.meta_value AS email
			FROM wp_postmeta pm, wp_posts p
			WHERE p.ID = pm.post_id
			  AND p.post_type = 'contact'
			  AND pm.meta_key = 'email'
		");
		foreach ($results as $row) {
			$wpdb->update('wp_postmeta', array(
				'meta_value' => xrcr_normalize_email($row->email)
			), array(
				'post_id' => $row->post_id,
				'meta_key' => 'email'
			));
		}
	}

	if ($version < 2) {
		$contacts = get_posts(array(
			'post_type' => 'contact',
			'posts_per_page' => -1
		));
		foreach ($contacts as $post) {
			$score = xrcr_get_contact_score($post->ID);
			update_post_meta($post->ID, '_score', $score);
		}
	}

	update_option($option_key, $curr_version);
	echo "Updated contacts to migration $curr_version\n";

}

function xrcr_import_contacts($args) {

	if (count($args) < 1) {
		echo "Usage: wp import:contacts [csv file]\n";
		exit;
	}

	echo "Loading {$args[0]}...\n";
	$fh = fopen($args[0], 'r');

	$expected_headers = xrcr_contact_headers();
	$headers = fgetcsv($fh);

	foreach ($expected_headers as $index => $field_name) {
		if ($headers[$index] != $field_name) {
			echo "Error: CSV headers did not match ({$headers[$index]} instead of $field_name)\n";
			exit;
		}
	}

	global $wpdb;
	$results = $wpdb->get_results("
		SELECT pm.post_id AS post_id, pm.meta_value AS email
		FROM wp_postmeta AS pm, wp_posts AS p
		WHERE p.ID = pm.post_id
		  AND p.post_type = 'contact'
		  AND pm.meta_key = 'email'
	");
	$lookup = array();
	foreach ($results as $row) {
		$lookup[$row->email] = intval($row->post_id);
	}

	while ($row = fgetcsv($fh)) {

		$email = xrcr_normalize_email($row[2]);

		if (! empty($lookup[$email])) {
			$post_id = $lookup[$email];
		} else {
			$post_id = wp_insert_post(array(
				'post_type' => 'contact',
				'post_status' => 'publish'
			));
		}

		if (! empty($post_id)) {
			foreach ($headers as $index => $field_name) {
				update_field($field_name, $row[$index], $post_id);
			}
			xrcr_update_contact($post_id);
		}
	}
	exit;
}

function xrcr_export_contacts() {

	$headers = xrcr_contact_headers();

	$posts = get_posts(array(
		'post_type' => 'contact',
		'posts_per_page' => -1
	));
	$fh = fopen('php://stdout', 'w');

	fputcsv($fh, $headers);

	foreach ($posts as $post) {
		$row = array();
		foreach ($headers as $field_name) {
			$row[] = get_field($field_name, $post->ID);
		}
		fputcsv($fh, $row);
	}
	fclose($fh);

	exit;
}

if (defined('WP_CLI') && WP_CLI) {
	WP_CLI::add_command('export:contacts', 'xrcr_export_contacts');
	WP_CLI::add_command('import:contacts', 'xrcr_import_contacts');
	WP_CLI::add_command('migrate:contacts', 'xrcr_migrate_contacts');
}
