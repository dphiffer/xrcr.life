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
echo '<div class="container">';
echo '<h2>' . get_the_title() . '</h2>';

the_content();

echo '</div>';

get_template_part('join-form');
get_footer();
