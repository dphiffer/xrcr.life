<?php
/*

Template: Call page

*/

global $contact;
acf_form_head();
get_header();

if (current_user_can('editor') || current_user_can('administrator')) {
	get_template_part('call-form');
} else {
	$redirect = get_site_url(null, '/call/');
	$redirect = urlencode($redirect);
	?>
	<div class="container">
		<h1>Oops, you can't access this page.</h1>
		<h2><a href="/wp-login.php?redirect_to=<?php echo $redirect; ?>">Maybe you need to sign in?</a></h2>
	</div>
	<?php
}

get_footer();
