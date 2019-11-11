<?php

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

	$expected_headers = xrcr_contacts_csv_headers();
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

	$created = 0;
	$updated = 0;

	while ($row = fgetcsv($fh)) {

		$email = xrcr_normalize_email($row[2]);

		if (! empty($lookup[$email])) {
			$post_id = $lookup[$email];
			$updated++;
		} else {
			$post_id = wp_insert_post(array(
				'post_type' => 'contact',
				'post_status' => 'publish'
			));
			$created++;
		}

		if (! empty($post_id)) {
			foreach ($headers as $index => $field_name) {
				if (substr($field_name, -5, 5) == '_name') {
					$row[$index] = trim($row[$index]);
				} else if (substr($field_name, -5, 5) == '_Date' && ! empty($row[$index])) {
					$row[$index] = date('Y/m/d', strtotime($row[$index]));
				}
				update_field($field_name, $row[$index], $post_id);
			}
			xrcr_update_contact($post_id);
		}
	}

	echo "$updated existing contacts\n";
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
			$row[] = get_field($field_name, $post->ID);
		}
		fputcsv($fh, $row);
	}
	fclose($fh);

	exit;
}

function xrcr_contacts_csv_headers() {

	$fields_path = dirname(__DIR__) . '/fields.json';
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
