<form action="<?php echo site_url('/caller/'); ?>" id="about" class="call-type">
	<div class="container">
		<h2>Welcome to Caller City</h2>
		<p>Calls are a critical part of how we keep momentum, thank you for your invaluable help! Calling people on the phone has become something of a lost art. Be yourself, try to listen, and enjoy the process. Our supporters, arrestees and circle organizers are on the other end of that phone line, and you can help them find their best, most righteous selves by helping out with XR!</p>
		<input type="hidden" name="type" value="hfe-follow-up">
		<?php

		/*

		// For now we only have one kind of call_type! This is for future use...

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
		*/

		?>
		</select>
		<input type="submit" value="Start">
	</div>
</form>
<div id="events">
	<div class="container">
		<h2>Call history</h2>
		<p><i>Nothing here yet.</i></p>
	</div>
</div>
