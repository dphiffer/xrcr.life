<div id="join">
	<div class="container">
		<form action="/wp-admin/admin-ajax.php" method="post">
			<h2>Join the rebellion</h2>
			<div class="form-step1">
				<input type="hidden" name="action" value="xrcr_join">
				<input type="hidden" name="redirect" value="<?php htmlentities($_SERVER['REQUEST_URI']); ?>">
				<?php if (! empty($_GET['join'])) { ?>
					<div class="joined">Thank you, we’ve received your submission!</div>
				<?php } ?>
				<div class="column">
					<label for="first_name">First name</label>
					<input type="text" name="first_name" id="first_name">
				</div>
				<div class="column">
					<label for="last_name">Last name (optional)</label>
					<input type="text" name="last_name" id="last_name">
				</div>
				<div class="column">
					<label for="email">Email address</label>
					<input type="text" name="email" id="email">
				</div>
				<div class="column">
					<label for="Phone">Phone number (optional)</label>
					<input type="text" name="Phone" id="Phone">
				</div>
				<div class="column">
					<label for="zip_code">Zip code (optional)</label>
					<input type="text" name="zip_code" id="zip_code">
				</div>
				<div class="buttons">
					<input type="submit" value="Join">
				</div>
			</div>
			<div id="join-feedback" class="hidden"></div>
		</form>
	</div>
</div>
