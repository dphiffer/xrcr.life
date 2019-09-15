<?php get_header(); ?>

<header class="mini">
	<div id="canvas"></div>
</header>

<?php

while (have_posts()) {
	the_post();

	echo '<div class="container">';
	echo '<h2>' . get_the_title() . '</h2>';

	the_content();

	echo '</div>';
}

get_template_part('join-form');
get_footer();
