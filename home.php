<?php
/**
 * Ratgeber-Übersicht: alle Beiträge als Karten.
 * Titel und Einleitung kommen von der Seite, die unter Einstellungen → Lesen
 * als "Beitragsseite" eingetragen ist (Seite "Ratgeber").
 */

$ratgeber = get_post((int) get_option('page_for_posts'));
?>
<?php get_header(); ?>

<main id="main">
	<section class="entry entry--breit">
		<div class="container entry__inner">
			<h1 class="entry__title"><?php echo esc_html($ratgeber ? get_the_title($ratgeber) : __('Ratgeber', 'wildtierhilfe')); ?></h1>

			<div class="entry__content">
				<?php
				if ($ratgeber && trim($ratgeber->post_content) !== '') {
					echo apply_filters('the_content', $ratgeber->post_content);
				}

				$beitraege = $GLOBALS['wp_query']->posts;
				echo $beitraege
					? wildtierhilfe_beitrags_uebersicht($beitraege, 'h2')
					: '<p>' . esc_html__('Noch keine Beiträge.', 'wildtierhilfe') . '</p>';
				?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
