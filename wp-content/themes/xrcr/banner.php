<?php

global $post;

if (has_post_thumbnail($post->ID)) {

	$attachment_id = get_post_thumbnail_id($post->ID);
	list($image) = wp_get_attachment_image_src($attachment_id, 'fullsize');

	?>
	<header class="image-bg" style="background-image: url('<?php echo $image; ?>');"></header>
<?php } else if (is_front_page()) { ?>
<header>
	<div id="canvas"></div>
	<div class="container">
		<h1>
			<span class="small">Rebel</span><br><span class="smaller">For</span><br><span class="larger">Life</span>
		</h1>
	</div>
</header>
<?php } else { ?>
	<header class="mini">
		<div id="canvas"></div>
	</header>
<?php } ?>
