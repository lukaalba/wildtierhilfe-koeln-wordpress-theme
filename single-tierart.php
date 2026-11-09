<?php
/**
 * Seite einer Tierart (Backend → Tierarten): oben das Beitragsbild über den
 * ganzen Bildschirm mit dem Titel in der Mitte, darunter der Text in
 * Lesebreite, zentriert. Ohne Beitragsbild steht der Titel über dem Text.
 */
?>
<?php get_header(); ?>

<main id="main">
	<?php while (have_posts()) : the_post(); ?>
		<?php if (has_post_thumbnail()) : ?>
			<?php get_template_part('template-parts/seitenbild'); ?>
		<?php endif; ?>

		<article class="entry entry--tierart">
			<div class="container entry__inner">
				<?php if (!has_post_thumbnail()) : ?>
					<h1 class="entry__title"><?php the_title(); ?></h1>
				<?php endif; ?>
				<div class="entry__content"><?php the_content(); ?></div>

				<?php $beitraege = wildtierhilfe_weitere_beitraege(); ?>
				<?php if ($beitraege) : ?>
					<aside class="weiterlesen" aria-labelledby="weiterlesen-titel">
						<h2 id="weiterlesen-titel" class="weiterlesen__titel"><?php esc_html_e('Mehr zum Thema', 'wildtierhilfe'); ?></h2>
						<ul class="weiterlesen__liste">
							<?php foreach ($beitraege as $beitrag) : ?>
								<li>
									<a class="weiterlesen__link" href="<?php echo esc_url(get_permalink($beitrag)); ?>">
										<span class="weiterlesen__name"><?php echo esc_html(get_the_title($beitrag)); ?></span>
										<span class="weiterlesen__pfeil" aria-hidden="true">→</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</aside>
				<?php endif; ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
