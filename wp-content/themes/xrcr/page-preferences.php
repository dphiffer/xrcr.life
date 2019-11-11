<?php

$contact = null;
if (! empty($_GET['contact'])) {
	$contacts = get_posts(array(
		'post_type' => 'contact',
		'meta_query' => array(
			array(
				'key' => 'UUID',
				'value' => $_GET['contact']
			)
		)
	));
	if (! empty($contacts)) {
		$contact = $contacts[0];
	}
}

if (have_posts()) {
	the_post();
}

acf_form_head();
get_header();

?>
<div class="container">
	<?php

	if (empty($contact)) {
		echo '<h2>Oops, sorry</h2>';
		echo '<h4>Something went wrong looking up your info.</h4>';
	} else {

		?>
		<h2><?php the_title(); ?></h2>
		<?php the_content(); ?>
		<?php acf_form(array(
			'post_id' => $contact->ID,
			'fields' => array(
				'contact_method',
				'contact_times',
				'contact_misc'
			),
			'submit_value' => 'Update',
			'html_updated_message' => '<div class="feedback"><h4><i class="fa fa-check"></i> Your preferences are saved. Thank you!</h4></div>'
		));

	}

	?>
</div>
<?php
get_footer();
