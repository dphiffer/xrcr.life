<?php

$call_id = $_GET['call'];
$call = get_post($call_id);
$contact_id = get_field('contact', $call_id);
$contact = get_post($contact_id);

$phone = get_field('Phone', $contact->ID);
$phone = xrcr_normalize_phone($phone);

?>
<div id="call">
	<div class="container">
		<h2><?php echo get_field('first_name', $contact->ID); ?>
			<span class="last-name"><?php echo get_field('last_name', $contact->ID); ?></span>
		</h2>
		<h3><?php echo $phone; ?></h3>
		<a href="/caller/?type=<?php echo $_GET['type']; ?>" class="button btn-secondary">Next</a>
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
