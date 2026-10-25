<?php
/**
 * Bilderrotation auf der Startseite.
 * Bilder: Backend → Slider (nur Einträge mit Bild, sortiert nach "Reihenfolge").
 * Überschrift und Text: Design → Customizer → Startseite: Bilderrotation.
 */

$slides = get_posts([
	'post_type'      => 'slide',
	'posts_per_page' => -1,
	'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
	'meta_key'       => '_thumbnail_id', // nur Slides mit Bild
]);
?>
<section class="hero" aria-label="<?php esc_attr_e('Bilderrotation', 'wildtierhilfe'); ?>">

	<div class="hero__slides">
		<?php foreach ($slides as $index => $slide) : ?>
			<div class="hero__slide<?php echo $index === 0 ? ' is-active' : ''; ?>">
				<?php
				echo wp_get_attachment_image(get_post_thumbnail_id($slide), 'full', false, [
					'class'         => 'hero__image',
					'alt'           => '',
					'sizes'         => '100vw',
					'loading'       => $index === 0 ? 'eager' : 'lazy',
					'fetchpriority' => $index === 0 ? 'high' : 'auto',
				]);
				?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="container hero__content">
		<h1 class="hero__title"><?php echo esc_html(get_theme_mod('hero_title', get_bloginfo('name'))); ?></h1>
		<p class="hero__text"><?php echo esc_html(get_theme_mod('hero_text', wildtierhilfe_hero_text_default())); ?></p>
	</div>

	<?php if (count($slides) > 1) : ?>
		<button class="hero__arrow hero__arrow--prev" type="button" aria-label="<?php esc_attr_e('Vorheriges Bild', 'wildtierhilfe'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="m15 18-6-6 6-6"/>
			</svg>
		</button>
		<button class="hero__arrow hero__arrow--next" type="button" aria-label="<?php esc_attr_e('Nächstes Bild', 'wildtierhilfe'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="m9 18 6-6-6-6"/>
			</svg>
		</button>

		<div class="hero__dots">
			<?php foreach ($slides as $index => $slide) : ?>
				<button class="hero__dot" type="button"
					aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>"
					aria-label="<?php echo esc_attr(sprintf(__('Bild %d', 'wildtierhilfe'), $index + 1)); ?>"></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

</section>
