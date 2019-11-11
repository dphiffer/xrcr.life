<?php

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

	$first_name = trim(get_field('first_name', $post_id));
	$last_name = trim(get_field('last_name', $post_id));
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
	if (empty($fields)) {
		return $score;
	}
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

function xrcr_contacts_migrate() {

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

function xrcr_contacts_import($args) {

	if (count($args) < 1) {
		echo "Usage: wp contacts:import [csv file]\n";
		exit;
	}

	echo "Loading {$args[0]}...\n";
	$fh = fopen($args[0], 'r');

	$field_headers = xrcr_contacts_csv_headers();
	$csv_headers = fgetcsv($fh);

	// [csv column index]  => [field name]
	$field_map = array();

	foreach ($csv_headers as $field_name) {
		if (in_array($field_name, $field_headers)) {
			$field_map[] = $field_name;
		} else {
			$field_map[] = null;
			echo "Warning: ignoring CSV column $field_name\n";
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

	$created = 0;
	$updated = 0;

	while ($row = fgetcsv($fh)) {

		$email_index = array_search('email', $csv_headers);
		$email = xrcr_normalize_email($row[$email_index]);

		if (! empty($lookup[$email])) {
			$post_id = $lookup[$email];
			$updated++;
		} else {
			$post_id = wp_insert_post(array(
				'post_type' => 'contact',
				'post_title' => 'Contact import',
				'post_status' => 'publish'
			));
			$created++;
		}

		if (! empty($post_id)) {
			foreach ($csv_headers as $index => $field_name) {
				if (! empty($field_map[$index])) {
					$value = $row[$index];
					if (substr($field_name, -5, 5) == '_name') {
						$value = trim($value);
					} else if (substr($field_name, -5, 5) == '_Date' && ! empty($value)) {
						$value = date('Y/m/d', strtotime($value));
					}
					update_field($field_name, $value, $post_id);
				}
			}
			xrcr_update_contact($post_id);
		}

		echo ".";
	}

	echo "\n$updated existing contacts\n";
	echo "$created new contacts\n";
	exit;
}

function xrcr_contacts_export() {

	$headers = xrcr_contacts_csv_headers();

	$posts = get_posts(array(
		'post_type' => 'contact',
		'posts_per_page' => -1
	));
	$fh = fopen('php://stdout', 'w');

	fputcsv($fh, $headers);

	foreach ($posts as $post) {
		$row = array();
		foreach ($headers as $field_name) {
			$value = get_field($field_name, $post->ID);
			if (is_array($value)) {
				if (empty($value)) {
					$value = '';
				} else {
					$value = array_values($value);
					$value = array_shift($value);
				}
			}
			$row[] = $value;
		}
		fputcsv($fh, $row);
	}
	fclose($fh);

	exit;
}

function xrcr_contacts_csv_headers() {

	$fields_path = dirname(__DIR__) . '/lib/fields.json';
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

if (defined('WP_CLI') && WP_CLI) {
	WP_CLI::add_command('contacts:export', 'xrcr_contacts_export');
	WP_CLI::add_command('contacts:import', 'xrcr_contacts_import');
	WP_CLI::add_command('contacts:migrate', 'xrcr_contacts_migrate');
}
