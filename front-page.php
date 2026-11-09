<?php
/**
 * Startseite: Bilderrotation, dann der Inhalt der Seite "Startseite"
 * (Backend → Seiten, falls dort etwas steht), dann alle Startseiten-Abschnitte
 * (Backend → Startseite), sortiert nach "Reihenfolge".
 * Welche Seite die Startseite ist: Einstellungen → Lesen.
 */
?>
<?php get_header(); ?>

<main id="main">
	<?php get_template_part('template-parts/hero'); ?>

	<?php while (have_posts()) : the_post(); ?>
		<?php if (is_page() && trim(get_the_content()) !== '') : ?>
			<article class="<?php echo esc_attr(wildtierhilfe_entry_classes()); ?>">
				<div class="container entry__inner">
					<div class="entry__content"><?php the_content(); ?></div>
				</div>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>

	<?php $abschnitte = wildtierhilfe_abschnitte(); ?>
	<?php while ($abschnitte->have_posts()) : $abschnitte->the_post(); ?>
		<?php
		// Ratgeber-Abschnitt ausblenden, solange es keine Beiträge gibt
		if (wildtierhilfe_ist_ratgeber_abschnitt(get_post()) && !wildtierhilfe_hat_beitraege()) {
			continue;
		}
		?>
		<article id="<?php echo esc_attr(get_post_field('post_name')); ?>" class="<?php echo esc_attr(wildtierhilfe_entry_classes()); ?>">
			<div class="container entry__inner">
				<h2 class="entry__title"><?php the_title(); ?></h2>
				<div class="entry__content"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>
