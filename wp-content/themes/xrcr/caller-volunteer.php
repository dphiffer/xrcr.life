<?php

$redirect = urlencode(site_url('/caller/'));

?>
<form action="/wp-admin/admin-ajax.php" method="post" id="join">
	<div class="container">
		<input type="hidden" name="action" value="xrcr_caller_volunteer">
		<?php if (! empty($_GET['ok'])) { ?>
			<?php if ($_GET['ok'] == -1) { ?>
				<h2>Error, admin needed</h2>
				<p><strong>This feature to be <a href="/wp-admin/admin.php?page=acf-options">configured in WordPress</a>.</strong></p>
				<p><a href="/caller/">Back to the sign up form</a></p>
			<?php } else { ?>
				<h2>Thank you</h2>
				<p><strong>You should hear from another XR rebel caller soon!</strong></p>
				<p><a href="/caller/">Back to the sign up form</a></p>
			<?php } ?>
		<?php } else { ?>
			<?php if (isset($_GET['ok'])) { ?>
				<h2>Error, try again</h2>
				<p>Note that all the form fields are required.</p>
			<?php } else { ?>
				<h2>Hello, rebel caller</h2>
				<p>Sign up to join us as a volunteer XR rebel caller.</p>
			<?php } ?>
			<p><strong>Already signed up? <a href="<?php echo site_url("/wp-login.php?redirect_to=$redirect"); ?>">Login</a></strong></p>
			<label for="name">
				Your name
			</label>
			<input type="text" name="name" id="name" class="input">
			<label for="email">
				Email address
			</label>
			<input type="email" name="email" id="email">
			<label for="phone">
				Phone number
			</label>
			<input type="phone" name="phone" id="phone">
			<div class="buttons">
				<input type="submit" value="Send">
			</div>
		<?php } ?>
	</div>
</form>
