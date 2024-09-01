		<footer>
			<div class="container">
				<?php wp_nav_menu(array(
					'theme_location' => 'footer-menu'
				)); ?>
				<ul class="social">
					<li><a href="mailto:info@xrcr.life"><i class="fa fa-envelope"></i>Contact us</a></li>
					<li><a href="https://www.instagram.com/xrcrlife/"><i class="fab fa-instagram"></i>XRCR on Instagram</a>
					</li>
					<li><a href="https://www.facebook.com/xrcrlife/"><i class="fab fa-facebook-square"></i>XRCR on Facebook</a>
					</li>
					<li><a href="https://twitter.com/xrcrlife"><i class="fab fa-twitter"></i>XRCR on Twitter</a>
					</li>
				</ul>
				<br class="clear">
			</div>
		</footer>
		<?php wp_footer(); ?>
		<script src="<?php js_src('jquery.min.js'); ?>"></script>
		<script src="<?php js_src('p5.min.js'); ?>"></script>
		<script src="<?php js_src('xrcr.js'); ?>"></script>
	</body>
</html>
