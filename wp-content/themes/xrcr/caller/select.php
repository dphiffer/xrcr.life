<form action="<?php echo site_url('/caller/'); ?>" id="about" class="call-type">
	<div class="container">
		<h2>Welcome to Caller City</h2>
		<?php the_content(); ?>
		<div class="buttons">
			<input type="hidden" name="type" value="hfe-follow-up">
		</div>
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
		<input type="submit" value="Start calling">
	</div>
</form>
<div id="call-history">
	<div class="container">
		<h2>Call history</h2>
		<?php

		$calls = get_posts(array(
			'post_type' => 'call'
		));

		if (empty($calls)) {
			echo "<h4><i>Nothing here yet.</i></h4>\n";
		} else {

			echo "<ol>\n";

			foreach ($calls as $call) {
				$when = human_time_diff(current_time('timestamp', 'utc'), strtotime($call->post_date_gmt));
				echo "<li class=\"call\"><h4><a href=\"/caller/?call=$call->ID\">$call->post_title</a></h4>$when ago</li>\n";
			}

			echo "</ol>\n";
		}

		?>
	</div>
</div>
