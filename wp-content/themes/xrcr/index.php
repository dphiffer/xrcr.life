<?php

if (! have_posts()) {
	require_once '404.php';
	return;
}

the_post();
get_header();

global $post;

if (has_post_thumbnail($post->ID)) {

	$attachment_id = get_post_thumbnail_id($post->ID);
	list($image) = wp_get_attachment_image_src($attachment_id, 'fullsize');

	?>
	<header class="image-bg" style="background-image: url('<?php echo $image; ?>');"></header>
<?php } else { ?>
<header class="mini">
	<div id="canvas"></div>
</header>
<?php

}

$cta_content = get_field('cta_content');
$container_class = 'container';

if (! empty($cta_content)) {
	$container_class .= ' has-cta';
}

echo "<div class=\"$container_class\">\n";

if (! empty($cta_content)) {
	echo "<div class=\"cta\">$cta_content</div>\n";
	echo "<div class=\"main\">\n";
}

?><h2><?php the_title(); ?></h2><?php
the_content();

if (! empty($cta_content)) {
	echo "</div>\n";
	echo "<br class=\"clear\">\n";
}

echo '</div>';

get_template_part('join-form');
get_footer();
