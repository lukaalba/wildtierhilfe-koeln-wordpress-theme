<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e('Zum Inhalt springen', 'wildtierhilfe'); ?></a>

<header id="site-header" class="site-header">
	<div class="container site-header__inner">

		<!-- Logo + Vereinsname -->
		<a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>">
			<?php
			$logo_id = get_theme_mod('custom_logo');
			if ($logo_id) {
				echo wp_get_attachment_image($logo_id, 'full', false, [
					'class' => 'site-brand__logo',
					'alt'   => '',
				]);
			}
			?>
			<span class="site-brand__name"><?php echo esc_html(get_bloginfo('name')); ?></span>
		</a>

		<!-- Hamburger-Button (nur unterhalb von 1024 px sichtbar) -->
		<button id="nav-toggle" class="nav-toggle" type="button"
			aria-controls="primary-nav" aria-expanded="false"
			aria-label="<?php esc_attr_e('Menü öffnen', 'wildtierhilfe'); ?>">
			<svg class="nav-toggle__icon nav-toggle__icon--open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<line x1="4" x2="20" y1="6" y2="6"/>
				<line x1="4" x2="20" y1="12" y2="12"/>
				<line x1="4" x2="20" y1="18" y2="18"/>
			</svg>
			<svg class="nav-toggle__icon nav-toggle__icon--close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<line x1="18" x2="6" y1="6" y2="18"/>
				<line x1="6" x2="18" y1="6" y2="18"/>
			</svg>
		</button>

		<!-- Hauptmenü -->
		<nav id="primary-nav" class="main-nav" aria-label="<?php esc_attr_e('Hauptmenü', 'wildtierhilfe'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 2,
			]);
			?>
		</nav>

	</div>
</header>
