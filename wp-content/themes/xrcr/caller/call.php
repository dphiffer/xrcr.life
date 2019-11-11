<?php

$call_id = $_GET['call'];
$call = get_post($call_id);

$call_type_term = null;
$call_type_terms = get_the_terms($call, 'call_type');
if (! empty($call_type_terms)) {
	$call_type_term = $call_type_terms[0];
}

$contact_id = get_field('contact', $call_id);
$contact = get_post($contact_id);

$first_name = get_field('first_name', $contact->ID);
$last_name = get_field('last_name', $contact->ID);

$phone = get_field('Phone', $contact->ID);
$phone = xrcr_normalize_phone($phone);

?>
<div id="call">
	<div class="container">
		<h2><?php echo $first_name; ?><span class="last-name"> <?php echo $last_name; ?></span>
		</h2>
		<h3><?php echo $phone; ?></h3>
		<div class="nav-buttons">
			<?php if (! empty($call_type_term)) { ?>
				<a href="/caller/?type=<?php echo $call_type_term->slug; ?>" class="button">Next call</a>
			<?php } ?>
			<a href="/caller/" class="button btn-secondary">Done</a>
			<br class="clear">
		</div>
		<div class="call-context">
			<?php

			if (! empty($call_type_term)) {
				echo "<h4>$call_type_term->name</h4>\n";
				echo "<p>$call_type_term->description</p>\n";
			} else {
				echo "<h4>Context</h4>\n";
			}

			$context = xrcr_caller_context($call, $call_type_term);
			foreach ($context as $item) {
				echo "<p>$item</p>\n";
			}

			?>
		</div>
		<div class="call-details">
			<?php acf_form(array(
				'post_id' => $call->ID,
				'fields' => array(
					'status',
					'caller_notes'
				),
				'submit_value' => 'Save call',
				'html_updated_message' => 'Call details saved.',
				'return' => "?call=$call->ID"
			)); ?>
		</div>
	</div>
</div>
<div id="contact">
	<div class="container">
		<?php acf_form(array(
			'post_id' => $contact->ID,
			'fields' => array(
				'Interest_Actions',
				'Interest_Art',
				'Interest_HFE_Host',
				'Interest_Infrastructure',
				'Interest_Media',
				'Interest_Outreach',
				'Interest_Regen',
				'Avail_A_8+hrs/wk',
				'Avail_B_4-8hrs/wk',
				'Avail_C_2-3hrs/wk',
				'Avail_D_projects',
				'Avail_E_events',
				'Transportation_Help',
				'Skills_Text',
				'Heard_About_XR',
				'Willing_Arrested'
			),
			'submit_value' => 'Update contact',
			'html_updated_message' => 'Contact details saved.',
			'return' => "?call=$call->ID"
		)); ?>
	</div>
</div>
