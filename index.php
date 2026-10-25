<?php get_header(); ?>

<main id="main">
	<?php if (is_home()) : ?>
		<?php get_template_part('template-parts/hero'); ?>
	<?php endif; ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php
		// ACF-Felder: "Hintergrundfarbe" ("auto" bzw. leer = abwechselnd weiß/mint)
		// und "Inhaltsbreite" ("voll" = volle Breite statt Lesebreite)
		$background = function_exists('get_field') ? get_field('hintergrund') : '';
		$width      = function_exists('get_field') ? get_field('inhaltsbreite') : '';
		$modifier   = $background && $background !== 'auto' ? ' entry--' . $background : '';
		$modifier  .= $width === 'voll' ? ' entry--breit' : '';
		?>
		<article id="<?php echo esc_attr(get_post_field('post_name')); ?>" class="entry<?php echo esc_attr($modifier); ?>">
			<div class="container entry__inner">
				<h2 class="entry__title"><?php the_title(); ?></h2>
				<div class="entry__content"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
