<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php css_href('style.css'); ?>">
		<link rel="stylesheet" href="<?php css_href('fonts/fa/css/all.min.css'); ?>">
		<link rel="icon" href="<?php img_src('favicon-32x32.png'); ?>" sizes="32x32" />
		<?php wp_head(); ?>
	</head>
	<body>
		<nav>
			<a href="/" class="identity">
				<img src="<?php img_src('xr-logo.png'); ?>" alt="Extinction Rebellion" class="logo">
				Capital Region
			</a>
		</nav>
		<header>
			<div class="container">
				<h1>Rebel For Life</h1>
			</div>
		</header>
		<div class="container">
			<form action="/wp-admin/admin-ajax.php" method="post" id="join">
				<input type="hidden" name="action" value="xrcr_join">
				<h2>Join the rebellion</h2>
				<?php if (! empty($_GET['join'])) { ?>
					<div class="joined">Thank you, we’ve received your submission!</div>
				<?php } ?>
				<div class="column">
					<label for="first_name">First name</label>
					<input type="text" name="first_name" id="first_name">
				</div>
				<div class="column">
					<label for="last_name">Last name (optional)</label>
					<input type="text" name="last_name" id="last_name">
				</div>
				<div class="column">
					<label for="email">Email address</label>
					<input type="text" name="email" id="email">
				</div>
				<div class="column">
					<label for="phone">Phone number (optional)</label>
					<input type="text" name="phone" id="phone">
				</div>
				<div class="column">
					<label for="zip">Zip code (optional)</label>
					<input type="text" name="zip" id="zip">
				</div>
				<div class="buttons">
					<input type="submit" value="Join">
				</div>
			</form>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
