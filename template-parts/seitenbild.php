<?php
/**
 * Großes Titelbild oben auf einer Einzelseite (Tierart, Ratgeber-Beitrag):
 * das Beitragsbild über die volle Breite mit dem Titel in der Mitte.
 * Nur innerhalb der Schleife und nur mit Beitragsbild verwenden – ohne Bild
 * steht der Titel stattdessen über dem Text (siehe single.php).
 * CSS: .seitenbild in style.css; der Header liegt transparent darüber (nav.js).
 */
?>
<div class="seitenbild">
	<?php
	the_post_thumbnail('full', [
		'class'         => 'seitenbild__image',
		'alt'           => '',
		'sizes'         => '100vw',
		'loading'       => 'eager',
		'fetchpriority' => 'high',
	]);
	?>
	<div class="container seitenbild__content">
		<h1 class="seitenbild__title"><?php the_title(); ?></h1>
	</div>
</div>
