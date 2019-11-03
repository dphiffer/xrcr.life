<?php

$contact = xrcr_get_call_contact();
$phone = get_field('Phone', $contact->ID);
$phone = xrcr_normalize_phone($phone);

?>
<div id="call">
	<div class="container">
		<h2><?php echo $contact->post_title; ?></h2>
		<a href="tel://<?php echo $phone; ?>" class="button">Call</a>
		<a href="#" class="button btn-secondary">Skip</a>
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
			'html_updated_message' => 'Contact saved.'
		)); ?>
	</div>
</div>
