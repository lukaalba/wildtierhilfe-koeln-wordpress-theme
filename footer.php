<?php
/**
 * Footer: Copyright (aktuelles Jahr + Seitentitel), Untertitel und optional
 * das Menü "Footer" (z. B. Impressum, Datenschutz).
 * Seitentitel und Untertitel: Einstellungen → Allgemein.
 */
?>
<footer class="site-footer">
	<div class="container site-footer__inner">
		<p class="site-footer__copyright">
			&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>
		</p>

		<?php if (get_bloginfo('description')) : ?>
			<p class="site-footer__tagline"><?php echo esc_html(get_bloginfo('description')); ?></p>
		<?php endif; ?>

		<?php
		if (has_nav_menu('footer')) {
			wp_nav_menu([
				'theme_location'       => 'footer',
				'container'            => 'nav',
				'container_class'      => 'site-footer__nav',
				'container_aria_label' => __('Rechtliches', 'wildtierhilfe'),
				'depth'                => 1,
				'fallback_cb'          => false,
			]);
		}
		?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
