<?php if (get_option('ygj_logo') == '显示') { ?>
	<hgroup class="logo-site">
				<h1 class="site-title">
					<a href="<?php bloginfo('home'); ?>/">
						<img src="<?php bloginfo('template_directory'); ?>/images/logo.png" width="220" height="50" alt="<?php bloginfo('name'); ?>">
					</a>
				</h1>
			</hgroup><!-- .logo-site -->
<?php } else { ?>
		<hgroup class="logo-site">
				<h1 class="site-title">
					<a href="<?php bloginfo('home'); ?>/">
						<?php bloginfo('name'); ?>
					</a>
				</h1>
			</hgroup><!-- .logo-site -->
<?php } ?>