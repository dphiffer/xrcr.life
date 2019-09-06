<?php
/*
Template Name: Home
*/
?>
<?php get_header(); ?>
<header>
	<div id="canvas"></div>
	<div class="container">
		<h1>
			<span class="small">Rebel</span><br><span class="smaller">For</span><br><span class="larger">Life</span>
		</h1>
	</div>
</header>
<div id="about">
	<div class="container">
		<img src="<?php img_src('xr-logo.svg'); ?>" alt="Extinction Rebellion" width="300" class="logo">
		<p>We are facing an unprecedented global emergency. Life on Earth is in crisis: scientists agree we have entered a period of abrupt climate breakdown, and we are in the midst of a mass extinction of our own making.</p>
		<div class="cta">
			<a href="https://www.youtube.com/watch?v=n__y1FXK_jE" class="button">Watch The talk <i class="fab fa-youtube"></i></a>
			<p><i>Heading for Extinction<br>(And what to do about it)</i></p>
		</div>
	</div>
</div>
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
<div id="events">
	<div class="container">
		<h2>Events</h2>
		<?php

		$query = new WP_Query(array(
			'post_type' => 'event',
			'posts_per_page' => 3,
			'meta_key' => 'time',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'time',
					'compare' => '>=',
					'value' => current_time("Y-m-d H:i:s")
				)
			)
		));

		while ($query->have_posts()) {
			$query->the_post();
			echo "<div class=\"event\">";
			?>
			<h4 class="time">
				<i class="fa fa-calendar"></i>
				<?php the_field('time'); ?>
			</h4>
			<h3><a href="<?php the_field('link'); ?>"><?php the_title(); ?></a></h3>
			<div class="location">
				<i class="fa fa-map-pin"></i>
				<?php the_field('location'); ?>
			</div>
			<?php
			echo "</div>";
		}

		?>
	</div>
</div>
<?php get_footer(); ?>
