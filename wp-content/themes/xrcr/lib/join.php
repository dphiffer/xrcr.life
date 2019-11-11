<?php

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
