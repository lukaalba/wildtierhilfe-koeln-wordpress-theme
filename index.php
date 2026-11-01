<?php get_header(); ?>

<main id="main">
	<?php if (is_home()) : ?>
		<?php get_template_part('template-parts/hero'); ?>
	<?php endif; ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article id="<?php echo esc_attr(get_post_field('post_name')); ?>" class="<?php echo esc_attr(wildtierhilfe_entry_classes()); ?>">
			<div class="container entry__inner">
				<h2 class="entry__title"><?php the_title(); ?></h2>
				<div class="entry__content"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
