<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
		<link href="https://fonts.googleapis.com/css?family=Crimson+Text&display=swap" rel="stylesheet">
		<link rel="icon" href="<?php img_src('favicon-32x32.png'); ?>" sizes="32x32" />
		<?php wp_head(); ?>
	</head>
	<body>
		<header>
			<div class="container">
				<div class="identity">
					<img src="<?php img_src('xr-logo.png'); ?>" alt="Extinction Rebellion" class="logo">
					Capital Region
				</div>
				<h1>Rebel For Life</h1>
			</div>
		</header>
		<div class="container">
			<form action="/wp-admin/admin-ajax.php" method="post" id="join">
				<h2>Join the rebellion</h2>
				<?php if (! empty($_GET['join'])) { ?>
					<div class="joined">Thank you, we’ve received your submission!</div>
				<?php } ?>
				<input type="hidden" name="action" value="xrcr_join">
				<label for="first_name">First name</label>
				<input type="text" name="first_name" id="first_name">
				<label for="last_name">Last name</label>
				<input type="text" name="last_name" id="last_name">
				<label for="email">Email address</label>
				<input type="text" name="email" id="email">
				<label for="phone">Phone number (optional)</label>
				<input type="text" name="phone" id="phone">
				<label for="zip">Zip code (optional)</label>
				<input type="text" name="zip" id="zip">
				<input type="submit" value="Join">
			</form>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
