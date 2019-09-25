<?php
/*
Template Name: Home
*/
?>
<?php

get_header();

global $post;

if (has_post_thumbnail($post->ID)) {

	$attachment_id = get_post_thumbnail_id($post->ID);
	list($image) = wp_get_attachment_image_src($attachment_id, 'fullsize');

	?>
	<header class="image-bg" style="background-image: url('<?php echo $image; ?>');"></header>
<?php } else { ?>
<header>
	<div id="canvas"></div>
	<div class="container">
		<h1>
			<span class="small">Rebel</span><br><span class="smaller">For</span><br><span class="larger">Life</span>
		</h1>
	</div>
</header>
<?php } ?>
<div id="about">
	<div class="container">
		<img src="<?php img_src('xr-logo.svg'); ?>" alt="Extinction Rebellion" width="300" class="logo">
		<p>We are facing an unprecedented global emergency. Life on Earth is in crisis: scientists agree we have entered a period of abrupt climate breakdown, and we are in the midst of a mass extinction of our own making.</p>
		<a href="https://www.youtube.com/watch?v=n__y1FXK_jE" class="button">Watch The talk <i class="fab fa-youtube"></i></a>
		<p><i>Heading for Extinction<br>(And what to do about it)</i></p>
	</div>
</div>
<?php get_template_part('join-form'); ?>
<div id="events">
	<div class="container">
		<h2>Events</h2>
		<?php

		$query = new WP_Query(array(
			'post_type' => 'event',
			'posts_per_page' => 10,
			'meta_key' => 'time',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'time',
					'compare' => '>=',
					'value' => current_time("Y-m-d 00:00:00")
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
