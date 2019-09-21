<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php css_href('style.css'); ?>">
		<link rel="stylesheet" href="<?php css_href('fonts/fa/css/all.min.css'); ?>">
		<link rel="icon" href="<?php img_src('favicon-32x32.png'); ?>" sizes="32x32">
		<meta property="og:title" content="Extinction Rebellion: Capital Region">
		<meta property="og:description" content="We are facing an unprecedented global emergency. Life on Earth is in crisis: scientists agree we have entered a period of abrupt climate breakdown, and we are in the midst of a mass extinction of our own making.">
		<meta property="og:image" content="https://xrcr.life/wp-content/themes/xrcr/img/facebook.jpg">
		<meta property="og:url" content="https://xrcr.life/">
		<meta property="og:type" content="website">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="Extinction Rebellion: Capital Region">
		<meta name="twitter:description" content="We are facing an unprecedented global emergency. Life on Earth is in crisis: scientists agree we have entered a period of abrupt climate breakdown, and we are in the midst of a mass extinction of our own making.">
		<meta name="twitter:image" content="https://xrcr.life/wp-content/themes/xrcr/img/twitter.jpg">

		<?php if (function_exists('get_field') && get_field('google_analytics_id', 'options')) { ?>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-148457113-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', '<?php the_field('google_analytics_id', 'options'); ?>');
		</script>

		<?php } ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<nav>
			<a href="/" class="identity">
				XR Capital Region
			</a>
			<div class="social">
				<a href="https://www.instagram.com/extinctionrebellion.cr/"><i class="fab fa-instagram"></i></a>
				<a href="https://www.facebook.com/extinctionrebellion.cr/"><i class="fab fa-facebook-f"></i></a>
				<a href="https://twitter.com/_XRCR"><i class="fab fa-twitter"></i></a>
			</div>
		</nav>
