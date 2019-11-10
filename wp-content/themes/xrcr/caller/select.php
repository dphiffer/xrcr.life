<form action="<?php echo site_url('/caller/'); ?>" class="container call-type">
	<h2>What kind of call are you making?</h2>
	<?php

	foreach (xrcr_caller_valid_types() as $call_type) {
		$term = get_term_by('slug', $call_type, 'call_type');
		?>
		<label>
			<input type="radio" name="type" value="<?php echo $call_type; ?>">
			<b><?php echo $term->name; ?></b>
			<div class="call-type-description"><?php echo $term->description; ?></div>
		</label>
		<?php
	}

	?>
	</select>
	<input type="submit" value="Start">
</form>
