<?php
/**
 * Einzelner Ratgeber-Beitrag (Backend → Beiträge): oben das Beitragsbild über
 * die volle Breite mit dem Titel in der Mitte (wie bei den Tierarten), darunter
 * der Text in Lesebreite. Ohne Beitragsbild steht der Titel über dem Text.
 */
?>
<?php get_header(); ?>

<main id="main">
	<?php while (have_posts()) : the_post(); ?>
		<?php if (has_post_thumbnail()) : ?>
			<?php get_template_part('template-parts/seitenbild'); ?>
		<?php endif; ?>

		<article class="<?php echo esc_attr(wildtierhilfe_entry_classes()); ?>">
			<div class="container entry__inner">
				<?php if (!has_post_thumbnail()) : ?>
					<h1 class="entry__title"><?php the_title(); ?></h1>
				<?php endif; ?>
				<div class="entry__content"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
