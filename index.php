<?php

get_header();

while (have_posts()) {
	the_post();

	echo '<div class="container">';
	echo '<h2>' . get_the_title() . '</h2>';

	the_content();

	echo '</div>';
}

get_footer();
