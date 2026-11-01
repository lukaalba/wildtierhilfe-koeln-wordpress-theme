<?php
/**
 * Eigenständige Seite (z. B. Impressum, Datenschutz). Inhalte: Backend → Seiten.
 */
?>
<?php get_header(); ?>

<main id="main">
	<?php while (have_posts()) : the_post(); ?>
		<article class="<?php echo esc_attr(wildtierhilfe_entry_classes()); ?>">
			<div class="container entry__inner">
				<h1 class="entry__title"><?php the_title(); ?></h1>
				<div class="entry__content"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
