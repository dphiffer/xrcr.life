<?php

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
