<?php
/**
 * Wildtierhilfe Theme – Grundeinrichtung
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme-Unterstützung und Menüpositionen.
 */
add_action('after_setup_theme', function () {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('custom-logo', [
		'height'      => 96,
		'width'       => 96,
		'flex-height' => true,
		'flex-width'  => true,
	]);
	add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);

	// Farbpalette im Block-Editor (z. B. Hintergrundfarbe von Karten)
	add_theme_support('editor-color-palette', [
		['name' => __('Weiß', 'wildtierhilfe'), 'slug' => 'white', 'color' => '#ffffff'],
		['name' => __('Mint', 'wildtierhilfe'), 'slug' => 'mint', 'color' => '#e8f5e0'],
		['name' => __('Creme', 'wildtierhilfe'), 'slug' => 'cream', 'color' => '#f5f4e0'],
		['name' => __('Sand', 'wildtierhilfe'), 'slug' => 'sand', 'color' => '#f5ece0'],
		['name' => __('Grün', 'wildtierhilfe'), 'slug' => 'brand', 'color' => '#245b38'],
		['name' => __('Dunkelgrün', 'wildtierhilfe'), 'slug' => 'brand-dark', 'color' => '#1d4a2d'],
		['name' => __('Text', 'wildtierhilfe'), 'slug' => 'text', 'color' => '#374151'],
	]);

	register_nav_menus([
		'primary' => __('Hauptmenü (Header)', 'wildtierhilfe'),
		'footer'  => __('Footer (z. B. Impressum, Datenschutz)', 'wildtierhilfe'),
	]);
});

/**
 * Beitragstyp "Slide": die Bilder der Bilderrotation auf der Startseite.
 * Jeder Eintrag ist ein Bild (Beitragsbild); die Reihenfolge kommt aus dem Feld
 * "Reihenfolge". Nur im Backend sichtbar, keine eigenen Seiten im Frontend.
 */
add_action('init', function () {
	register_post_type('slide', [
		'labels' => [
			'name'                  => __('Slider', 'wildtierhilfe'),
			'singular_name'         => __('Slide', 'wildtierhilfe'),
			'menu_name'             => __('Slider', 'wildtierhilfe'),
			'all_items'             => __('Alle Bilder', 'wildtierhilfe'),
			'add_new'               => __('Bild hinzufügen', 'wildtierhilfe'),
			'add_new_item'          => __('Neues Bild hinzufügen', 'wildtierhilfe'),
			'edit_item'             => __('Bild bearbeiten', 'wildtierhilfe'),
			'featured_image'        => __('Bild', 'wildtierhilfe'),
			'set_featured_image'    => __('Bild festlegen', 'wildtierhilfe'),
			'remove_featured_image' => __('Bild entfernen', 'wildtierhilfe'),
			'use_featured_image'    => __('Als Bild verwenden', 'wildtierhilfe'),
		],
		'public'        => false,
		'show_ui'       => true,
		'menu_position' => 20,
		'menu_icon'     => 'dashicons-images-alt2',
		'supports'      => ['title', 'thumbnail', 'page-attributes'],
	]);
});

/**
 * Beitragstyp "Abschnitt": die Abschnitte der Startseite (Mission, Tiere,
 * Helfen, Kontakt …). Sie erscheinen untereinander auf der Startseite
 * (front-page.php), sortiert nach "Reihenfolge", jeweils mit ihrem Slug als
 * Anker. Eigene Seiten im Frontend gibt es nicht – die Einzelansicht leitet
 * zum Abschnitt auf der Startseite weiter, nur die Vorschau aus dem Editor
 * bleibt erreichbar.
 */
add_action('init', function () {
	register_post_type('abschnitt', [
		'labels' => [
			'name'               => __('Startseiten-Abschnitte', 'wildtierhilfe'),
			'singular_name'      => __('Abschnitt', 'wildtierhilfe'),
			'menu_name'          => __('Startseite', 'wildtierhilfe'),
			'all_items'          => __('Alle Abschnitte', 'wildtierhilfe'),
			'add_new'            => __('Abschnitt hinzufügen', 'wildtierhilfe'),
			'add_new_item'       => __('Neuen Abschnitt hinzufügen', 'wildtierhilfe'),
			'edit_item'          => __('Abschnitt bearbeiten', 'wildtierhilfe'),
			'new_item'           => __('Neuer Abschnitt', 'wildtierhilfe'),
			'view_item'          => __('Abschnitt ansehen', 'wildtierhilfe'),
			'search_items'       => __('Abschnitte durchsuchen', 'wildtierhilfe'),
			'not_found'          => __('Keine Abschnitte gefunden.', 'wildtierhilfe'),
			'not_found_in_trash' => __('Keine Abschnitte im Papierkorb.', 'wildtierhilfe'),
		],
		'public'              => false,
		'publicly_queryable'  => true, // für die Vorschau im Editor
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true, // Menüpunkte springen zum Abschnitt
		'show_in_rest'        => true, // Block-Editor
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-admin-home',
		'rewrite'             => ['slug' => 'abschnitt', 'with_front' => false],
		'supports'            => ['title', 'editor', 'page-attributes', 'revisions'],
	]);
});

/**
 * Beitragstyp "Tierart": eine eigene Seite je Tierart (z. B. "/tiere/igel/").
 * Beitragsbild = großes Bild oben auf der Seite und Bild in der Vorschau.
 * Auf der Startseite zeigt der Block "Tierarten-Vorschau" alle Tierarten als
 * Tabs (Bild, Anfang des Textes, "Weiter lesen …"), sortiert nach "Reihenfolge".
 */
add_action('init', function () {
	register_post_type('tierart', [
		'labels' => [
			'name'                  => __('Tierarten', 'wildtierhilfe'),
			'singular_name'         => __('Tierart', 'wildtierhilfe'),
			'menu_name'             => __('Tierarten', 'wildtierhilfe'),
			'all_items'             => __('Alle Tierarten', 'wildtierhilfe'),
			'add_new'               => __('Tierart hinzufügen', 'wildtierhilfe'),
			'add_new_item'          => __('Neue Tierart hinzufügen', 'wildtierhilfe'),
			'edit_item'             => __('Tierart bearbeiten', 'wildtierhilfe'),
			'new_item'              => __('Neue Tierart', 'wildtierhilfe'),
			'view_item'             => __('Tierart ansehen', 'wildtierhilfe'),
			'search_items'          => __('Tierarten durchsuchen', 'wildtierhilfe'),
			'not_found'             => __('Keine Tierarten gefunden.', 'wildtierhilfe'),
			'not_found_in_trash'    => __('Keine Tierarten im Papierkorb.', 'wildtierhilfe'),
			'featured_image'        => __('Bild', 'wildtierhilfe'),
			'set_featured_image'    => __('Bild festlegen', 'wildtierhilfe'),
			'remove_featured_image' => __('Bild entfernen', 'wildtierhilfe'),
			'use_featured_image'    => __('Als Bild verwenden', 'wildtierhilfe'),
		],
		'public'        => true,
		'has_archive'   => false,
		'show_in_rest'  => true,
		'menu_position' => 5,
		'menu_icon'     => 'dashicons-pets',
		'rewrite'       => ['slug' => 'tiere', 'with_front' => false],
		'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions'],
	]);
});

/**
 * Alle veröffentlichten Tierarten, sortiert nach "Reihenfolge".
 */
function wildtierhilfe_tierarten()
{
	return get_posts([
		'post_type'      => 'tierart',
		'posts_per_page' => -1,
		'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
	]);
}

/**
 * Kurzer Text für den Tab einer Tierart: Feld "Tab-Beschriftung", sonst der Titel.
 */
function wildtierhilfe_tierart_label($post)
{
	$label = trim((string) get_post_meta(get_post($post)->ID, 'tab_beschriftung', true));
	return $label !== '' ? $label : get_the_title($post);
}

/**
 * Block "Tierarten-Vorschau" (Editor: Block einfügen → "Tierarten"): alle
 * Tierarten als Tierart-Blöcke (Bild + Text), die tabs.js zu Tabs zusammenfasst.
 * Im Editor zeigt editor.js eine Vorschau, gespeichert wird nur der Platzhalter.
 */
add_action('init', function () {
	register_block_type('wildtierhilfe/tierarten', [
		'api_version'     => 3,
		'title'           => __('Tierarten-Vorschau', 'wildtierhilfe'),
		'category'        => 'widgets',
		// Ausgabe nutzt das Markup von "Medien und Text" – dessen CSS lädt WordPress
		// sonst nur, wenn der Block selbst auf der Seite steht
		'style_handles'   => ['wp-block-media-text', 'wildtierhilfe-blocks'],
		'supports'        => ['html' => false],
		'render_callback' => 'wildtierhilfe_render_tierarten',
	]);
}, 11); // nach dem Registrieren von blocks.css

function wildtierhilfe_render_tierarten()
{
	$html = '';

	foreach (wildtierhilfe_tierarten() as $tierart) {
		$text = wildtierhilfe_tierart_vorschau_text($tierart);
		$link = get_permalink($tierart);

		$html .= sprintf(
			'<div class="wp-block-media-text is-stacked-on-mobile is-style-tierart tierart-vorschau" id="%1$s" data-tab-label="%2$s">'
				. '<figure class="wp-block-media-text__media">%3$s</figure>'
				. '<div class="wp-block-media-text__content">'
				. '<h3 class="wp-block-heading">%4$s</h3>'
				. '<p class="tierart-vorschau__text">%5$s</p>'
				. '<p class="tierart-vorschau__mehr"><a href="%6$s" aria-label="%8$s">%7$s</a></p>'
				. '</div></div>',
			esc_attr($tierart->post_name),
			esc_attr(wildtierhilfe_tierart_label($tierart)),
			get_the_post_thumbnail($tierart, 'large', ['alt' => '']),
			esc_html(get_the_title($tierart)),
			esc_html($text),
			esc_url($link),
			esc_html__('Weiter lesen …', 'wildtierhilfe'),
			esc_attr(sprintf(__('Weiter lesen: %s', 'wildtierhilfe'), get_the_title($tierart)))
		);
	}

	return $html;
}

/**
 * Block "Beitrags-Übersicht" (Editor: Block einfügen → "Beitrags-Übersicht"):
 * Beiträge als Karten (Titel + Kurztext), die ganze Karte verlinkt auf den
 * Beitrag. In der Seitenleiste des Blocks werden die Beiträge ausgewählt und
 * sortiert (editor.js); ohne Auswahl erscheinen die neuesten.
 */
add_action('init', function () {
	register_block_type('wildtierhilfe/beitraege', [
		'api_version'     => 3,
		'title'           => __('Beitrags-Übersicht', 'wildtierhilfe'),
		'category'        => 'widgets',
		'style_handles'   => ['wildtierhilfe-blocks'],
		'supports'        => ['html' => false],
		'attributes'      => [
			'beitraege' => ['type' => 'array', 'items' => ['type' => 'integer'], 'default' => []],
			'anzahl'    => ['type' => 'integer', 'default' => 4],
		],
		'render_callback' => 'wildtierhilfe_render_beitraege',
	]);
}, 11);

function wildtierhilfe_render_beitraege($attributes)
{
	$ids = array_filter(array_map('intval', $attributes['beitraege'] ?? []));

	$beitraege = get_posts($ids ? [
		'post_type'      => 'post',
		'post__in'       => $ids,
		'orderby'        => 'post__in', // Reihenfolge wie im Editor ausgewählt
		'posts_per_page' => -1,
	] : [
		'post_type'      => 'post',
		'posts_per_page' => max(1, (int) ($attributes['anzahl'] ?? 4)),
	]);

	return wildtierhilfe_beitrags_uebersicht($beitraege);
}

/**
 * Beiträge als Karten (Titel + Kurztext, ganze Karte verlinkt). Genutzt vom
 * Block "Beitrags-Übersicht" und von der Ratgeber-Übersicht (home.php).
 * Hat ein Beitrag ein Beitragsbild, füllt es die Karte als Hintergrund
 * (Text dann weiß auf dunklem Verlauf, blocks.css).
 */
function wildtierhilfe_beitrags_uebersicht($beitraege, $ueberschrift = 'h3')
{
	if (!$beitraege) {
		return '';
	}

	$html = '<div class="beitrags-uebersicht">';
	foreach ($beitraege as $beitrag) {
		$text = wildtierhilfe_kurztext($beitrag, 25);
		$bild = has_post_thumbnail($beitrag)
			? get_the_post_thumbnail($beitrag, 'large', [
				'class'   => 'beitrags-karte__bild',
				'alt'     => '',
				'sizes'   => '(min-width: 640px) 40rem, 100vw',
				'loading' => 'lazy',
			])
			: '';

		$html .= sprintf(
			'<a class="beitrags-karte%5$s" href="%1$s">%6$s<%4$s class="beitrags-karte__titel">%2$s</%4$s>%3$s</a>',
			esc_url(get_permalink($beitrag)),
			esc_html(get_the_title($beitrag)),
			$text !== '' ? '<p class="beitrags-karte__text">' . esc_html($text) . '</p>' : '',
			tag_escape($ueberschrift),
			$bild !== '' ? ' beitrags-karte--bild' : '',
			$bild
		);
	}
	$html .= '</div>';

	return $html;
}

/**
 * Kurztext eines Beitrags: der "Textauszug" (Seitenleiste im Editor), sonst
 * der Anfang des Textes mit höchstens $woerter Wörtern.
 */
function wildtierhilfe_kurztext($post, $woerter)
{
	if (has_excerpt($post)) {
		return wp_strip_all_tags($post->post_excerpt);
	}

	$text = excerpt_remove_blocks(strip_shortcodes($post->post_content));
	return wp_trim_words(wp_strip_all_tags($text), $woerter, ' …');
}

/**
 * Text der Vorschau: der "Textauszug" (Seitenleiste im Editor), sonst der
 * Anfang des Textes. Etwas länger als nötig – die Vorschau kürzt per CSS
 * auf 5 Zeilen (blocks.css).
 */
function wildtierhilfe_tierart_vorschau_text($post)
{
	return wildtierhilfe_kurztext($post, 90);
}

/**
 * ACF: kurze Tab-Beschriftung für Tierarten (z. B. "Bilche" statt
 * "Bilche (Garten- und Siebenschläfer)").
 */
add_action('acf/init', function () {
	acf_add_local_field_group([
		'key'      => 'group_wildtierhilfe_tierart',
		'title'    => __('Tab auf der Startseite', 'wildtierhilfe'),
		'fields'   => [
			[
				'key'          => 'field_wildtierhilfe_tab_beschriftung',
				'label'        => __('Tab-Beschriftung', 'wildtierhilfe'),
				'name'         => 'tab_beschriftung',
				'type'         => 'text',
				'instructions' => __('Kurzer Text für den Tab. Leer: Titel der Tierart.', 'wildtierhilfe'),
			],
		],
		'location' => [
			[
				['param' => 'post_type', 'operator' => '==', 'value' => 'tierart'],
			],
		],
		'position' => 'side',
	]);

	acf_add_local_field_group([
		'key'      => 'group_wildtierhilfe_tierart_links',
		'title'    => __('Weiterführende Beiträge', 'wildtierhilfe'),
		'fields'   => [
			[
				'key'           => 'field_wildtierhilfe_weitere_beitraege',
				'label'         => __('Beiträge', 'wildtierhilfe'),
				'name'          => 'weitere_beitraege',
				'type'          => 'post_object',
				'instructions'  => __('Bis zu 3 Beiträge, erscheinen als Links unter dem Text, z. B. „Erste Hilfe“. Reihenfolge per Ziehen.', 'wildtierhilfe'),
				'post_type'     => ['post'],
				'post_status'   => ['publish'],
				'multiple'      => 1,
				'allow_null'    => 1,
				'ui'            => 1,
				'return_format' => 'id',
			],
		],
		'location' => [
			[
				['param' => 'post_type', 'operator' => '==', 'value' => 'tierart'],
			],
		],
		'position' => 'side',
	]);
});

/**
 * Höchstens 3 weiterführende Beiträge: Beim Speichern mit mehr erscheint ein
 * Hinweis am Feld.
 */
const WILDTIERHILFE_MAX_WEITERE_BEITRAEGE = 3;

add_filter('acf/validate_value/key=field_wildtierhilfe_weitere_beitraege', function ($valid, $value) {
	if ($valid === true && is_array($value) && count($value) > WILDTIERHILFE_MAX_WEITERE_BEITRAEGE) {
		return sprintf(__('Bitte höchstens %d Beiträge auswählen.', 'wildtierhilfe'), WILDTIERHILFE_MAX_WEITERE_BEITRAEGE);
	}

	return $valid;
}, 10, 2);

/**
 * Weiterführende Beiträge einer Tierart (Feld "Weiterführende Beiträge"),
 * nur veröffentlichte, höchstens 3.
 */
function wildtierhilfe_weitere_beitraege($post = null)
{
	$ids = function_exists('get_field') ? get_field('weitere_beitraege', $post) : [];

	$beitraege = array_values(array_filter(array_map('get_post', (array) $ids), function ($beitrag) {
		return $beitrag && get_post_status($beitrag) === 'publish';
	}));

	return array_slice($beitraege, 0, WILDTIERHILFE_MAX_WEITERE_BEITRAEGE);
}

/**
 * Einmalige Umstellung (Version 2): Die Tierart-Blöcke ("Medien und Text" mit
 * Stil "Tierart") in den Startseiten-Abschnitten werden zu eigenen Tierarten
 * (Bild → Beitragsbild, Überschrift → Titel, Text → Inhalt, Slug = bisheriger
 * Anker, z. B. "igel"). An ihrer Stelle steht danach der Block
 * "Tierarten-Vorschau".
 */
add_action('init', function () {
	if ((int) get_option('wildtierhilfe_abschnitte_version') !== 1) {
		return;
	}
	update_option('wildtierhilfe_abschnitte_version', 2);

	$order = 0;

	foreach (get_posts(['post_type' => 'abschnitt', 'post_status' => 'any', 'posts_per_page' => -1]) as $abschnitt) {
		$blocks = parse_blocks($abschnitt->post_content);
		$new    = [];
		$placed = false;

		foreach ($blocks as $block) {
			if (!wildtierhilfe_is_tab($block) || $block['blockName'] !== 'core/media-text') {
				$new[] = $block;
				continue;
			}

			$tab     = wildtierhilfe_tab($block);
			$title   = '';
			$content = [];
			foreach ($block['innerBlocks'] as $inner) {
				if ($title === '' && $inner['blockName'] === 'core/heading') {
					$title = trim(wp_strip_all_tags($inner['innerHTML']));
				} else {
					$content[] = $inner;
				}
			}

			$id = wp_insert_post([
				'post_type'    => 'tierart',
				'post_status'  => $abschnitt->post_status === 'publish' ? 'publish' : 'draft',
				'post_title'   => wp_slash($title !== '' ? $title : $tab['label']),
				'post_name'    => $tab['anchor'],
				'post_content' => wp_slash(serialize_blocks($content)),
				'menu_order'   => ++$order,
			]);

			if ($id && !is_wp_error($id)) {
				if (!empty($block['attrs']['mediaId'])) {
					set_post_thumbnail($id, (int) $block['attrs']['mediaId']);
				}
				if (!empty($block['attrs']['tabLabel'])) {
					update_post_meta($id, 'tab_beschriftung', $block['attrs']['tabLabel']);
					update_post_meta($id, '_tab_beschriftung', 'field_wildtierhilfe_tab_beschriftung');
				}
			}

			if (!$placed) {
				$new[]  = ['blockName' => 'wildtierhilfe/tierarten', 'attrs' => [], 'innerBlocks' => [], 'innerHTML' => '', 'innerContent' => []];
				$placed = true;
			}
		}

		if ($placed) {
			// Leerzeilen zwischen den Blöcken neu setzen (sonst bleiben die der entfernten Blöcke stehen)
			$new = array_filter($new, function ($block) {
				return $block['blockName'] !== null || trim($block['innerHTML']) !== '';
			});

			wp_update_post([
				'ID'           => $abschnitt->ID,
				'post_content' => wp_slash(implode("\n\n", array_map('serialize_block', $new))),
			]);
		}
	}

	flush_rewrite_rules(false);
}, 21);

/**
 * Einmalige Umstellung (Version 3): Startseiten-Abschnitt
 * "Informationsmaterial" mit dem Block "Beitrags-Übersicht" direkt nach
 * "Helfen" anlegen (die folgenden Abschnitte rücken eine Stelle nach hinten).
 */
add_action('init', function () {
	if ((int) get_option('wildtierhilfe_abschnitte_version') !== 2) {
		return;
	}
	update_option('wildtierhilfe_abschnitte_version', 3);

	if (get_page_by_path('informationsmaterial', OBJECT, 'abschnitt')) {
		return;
	}

	$helfen = get_page_by_path('helfen', OBJECT, 'abschnitt');
	$order  = $helfen ? $helfen->menu_order + 1 : 99;

	foreach (get_posts(['post_type' => 'abschnitt', 'post_status' => 'any', 'posts_per_page' => -1]) as $abschnitt) {
		if ($abschnitt->menu_order >= $order) {
			wp_update_post(['ID' => $abschnitt->ID, 'menu_order' => $abschnitt->menu_order + 1]);
		}
	}

	$einleitung = __('Wissenswertes und praktische Hinweise rund um den Schutz und die Versorgung heimischer Wildtiere.', 'wildtierhilfe');

	wp_insert_post([
		'post_type'    => 'abschnitt',
		'post_status'  => 'publish',
		'post_title'   => wp_slash(__('Informationsmaterial', 'wildtierhilfe')),
		'post_name'    => 'informationsmaterial',
		'menu_order'   => $order,
		'post_content' => wp_slash(
			'<!-- wp:paragraph {"align":"center","className":"is-style-einleitung"} -->' . "\n"
			. '<p class="has-text-align-center is-style-einleitung">' . esc_html($einleitung) . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n\n"
			. '<!-- wp:wildtierhilfe/beitraege /-->'
		),
	]);
}, 22);

/**
 * Einmalige Umstellung (Version 4): Ratgeber.
 * 1. Seite "Ratgeber" anlegen und unter Einstellungen → Lesen als
 *    Beitragsseite eintragen – sie zeigt alle Beiträge (home.php).
 * 2. Beiträge liegen unter "/ratgeber/<Titelform>/" (Einstellungen → Permalinks).
 * 3. Menüpunkte, die auf den Startseiten-Abschnitt mit der Beitrags-Übersicht
 *    zeigen, zeigen jetzt auf die Seite "Ratgeber".
 */
add_action('init', function () {
	if ((int) get_option('wildtierhilfe_abschnitte_version') !== 3) {
		return;
	}
	update_option('wildtierhilfe_abschnitte_version', 4);

	$ratgeber = get_post((int) get_option('page_for_posts'));

	if (!$ratgeber || $ratgeber->post_type !== 'page') {
		$ratgeber = get_page_by_path('ratgeber');
	}

	if (!$ratgeber) {
		// Nicht in Menüs mit "Neue Seiten automatisch hinzufügen" aufnehmen
		remove_action('transition_post_status', '_wp_auto_add_pages_to_menu', 10);

		$einleitung = __('Wissenswertes und praktische Hinweise rund um den Schutz und die Versorgung heimischer Wildtiere.', 'wildtierhilfe');

		$id = wp_insert_post([
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => wp_slash(__('Ratgeber', 'wildtierhilfe')),
			'post_name'    => 'ratgeber',
			'post_content' => wp_slash(
				'<!-- wp:paragraph {"align":"center","className":"is-style-einleitung"} -->' . "\n"
				. '<p class="has-text-align-center is-style-einleitung">' . esc_html($einleitung) . '</p>' . "\n"
				. '<!-- /wp:paragraph -->'
			),
		]);
		$ratgeber = $id && !is_wp_error($id) ? get_post($id) : null;
	}

	if (!$ratgeber) {
		return;
	}

	update_option('page_for_posts', $ratgeber->ID);

	// Permalinks: "/ratgeber/<Titelform>/" (bei "/index.php/…"-Permalinks mit diesem Präfix)
	global $wp_rewrite;
	$prefix = str_starts_with((string) get_option('permalink_structure'), '/index.php/') ? '/index.php' : '';
	$wp_rewrite->set_permalink_structure($prefix . '/' . $ratgeber->post_name . '/%postname%/');

	// Menüpunkte: Abschnitt mit Beitrags-Übersicht → Seite "Ratgeber"
	$menu_items = get_posts([
		'post_type'      => 'nav_menu_item',
		'posts_per_page' => -1,
		'meta_query'     => [['key' => '_menu_item_object', 'value' => 'abschnitt']],
	]);
	foreach ($menu_items as $item) {
		$abschnitt = get_post((int) get_post_meta($item->ID, '_menu_item_object_id', true));
		if ($abschnitt && has_block('wildtierhilfe/beitraege', $abschnitt)) {
			update_post_meta($item->ID, '_menu_item_object', 'page');
			update_post_meta($item->ID, '_menu_item_object_id', $ratgeber->ID);
		}
	}

	flush_rewrite_rules(false);
}, 23);

/**
 * Gibt es veröffentlichte Beiträge (Ratgeber)? Ohne Beiträge werden der
 * Startseiten-Abschnitt mit der Beitrags-Übersicht (front-page.php) und
 * Menüpunkte zum Ratgeber ausgeblendet.
 */
function wildtierhilfe_hat_beitraege()
{
	static $hat = null;

	if ($hat === null) {
		$hat = (bool) get_posts([
			'post_type'      => 'post',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		]);
	}

	return $hat;
}

/**
 * Ist dieser Startseiten-Abschnitt der Ratgeber (enthält er den Block
 * "Beitrags-Übersicht")?
 */
function wildtierhilfe_ist_ratgeber_abschnitt($post)
{
	return get_post_type($post) === 'abschnitt' && has_block('wildtierhilfe/beitraege', $post);
}

/**
 * Hauptmenü und Footer-Menü: Ohne Beiträge verschwinden Menüpunkte, die zur
 * Ratgeber-Seite oder zum Ratgeber-Abschnitt führen (samt ihren Unterpunkten).
 */
add_filter('wp_nav_menu_objects', function ($items) {
	if (wildtierhilfe_hat_beitraege()) {
		return $items;
	}

	$ratgeber_seite = (int) get_option('page_for_posts');
	$entfernt       = [];

	foreach ($items as $key => $item) {
		$ist_ratgeber = $item->type === 'post_type' && (
			($item->object === 'page' && (int) $item->object_id === $ratgeber_seite && $ratgeber_seite)
			|| ($item->object === 'abschnitt' && wildtierhilfe_ist_ratgeber_abschnitt((int) $item->object_id))
		);

		if ($ist_ratgeber || in_array((int) $item->menu_item_parent, $entfernt, true)) {
			$entfernt[] = (int) $item->db_id;
			unset($items[$key]);
		}
	}

	return array_values($items);
}, 5); // vor dem Ergänzen der Tab-Unterpunkte

/**
 * Ratgeber-Übersicht (Beitragsseite, home.php): alle Beiträge ohne
 * Seitenumbruch, neueste zuerst.
 */
add_action('pre_get_posts', function ($query) {
	if (!is_admin() && $query->is_main_query() && $query->is_home()) {
		$query->set('posts_per_page', -1);
		$query->set('ignore_sticky_posts', true);
	}
});

/**
 * Seiten mit großem Bild oben (Startseite: Bilderrotation, Tierart und
 * Ratgeber-Beitrag: Beitragsbild). Der Header liegt dann transparent darüber.
 */
function wildtierhilfe_has_hero()
{
	return is_front_page() || (is_singular(['tierart', 'post']) && has_post_thumbnail());
}

/**
 * Alle Abschnitte der Startseite, sortiert nach "Reihenfolge".
 */
function wildtierhilfe_abschnitte()
{
	return new WP_Query([
		'post_type'      => 'abschnitt',
		'posts_per_page' => -1,
		'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
		'no_found_rows'  => true,
	]);
}

/**
 * Einmalige Umstellung (läuft beim ersten Aufruf nach dem Theme-Update, auch
 * auf dem Live-System):
 * 1. Die bisherigen Beiträge (= Startseiten-Abschnitte) werden zu "Abschnitt".
 *    Beiträge sind damit frei für Aktuelles/Wissenswertes.
 * 2. Menüpunkte, die auf diese Beiträge zeigen, zeigen jetzt auf die Abschnitte.
 * 3. Gibt es noch keine statische Startseite, wird die Seite "Startseite"
 *    angelegt und unter Einstellungen → Lesen als Startseite eingetragen.
 */
add_action('init', function () {
	if (get_option('wildtierhilfe_abschnitte_version') >= 1) {
		return;
	}
	// Erst festhalten, damit parallele Aufrufe die Umstellung nicht doppelt ausführen
	update_option('wildtierhilfe_abschnitte_version', 1);

	global $wpdb;

	$ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post'");
	if ($ids) {
		$in = implode(',', array_map('intval', $ids));

		$wpdb->query("UPDATE {$wpdb->posts} SET post_type = 'abschnitt' WHERE ID IN ($in)");

		$wpdb->query(
			"UPDATE {$wpdb->postmeta} AS object
			 JOIN {$wpdb->postmeta} AS object_id
			   ON object_id.post_id = object.post_id AND object_id.meta_key = '_menu_item_object_id'
			 SET object.meta_value = 'abschnitt'
			 WHERE object.meta_key = '_menu_item_object'
			   AND object.meta_value = 'post'
			   AND object_id.meta_value IN ($in)"
		);

		foreach ($ids as $id) {
			clean_post_cache((int) $id);
		}
	}

	if (get_option('show_on_front') !== 'page' || !get_post(get_option('page_on_front'))) {
		// Die Startseite nicht in Menüs mit "Neue Seiten automatisch hinzufügen" aufnehmen
		remove_action('transition_post_status', '_wp_auto_add_pages_to_menu', 10);

		$front_id = wp_insert_post([
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => __('Startseite', 'wildtierhilfe'),
			'post_name'   => 'startseite',
		]);

		if ($front_id && !is_wp_error($front_id)) {
			update_option('show_on_front', 'page');
			update_option('page_on_front', $front_id);
		}
	}

	flush_rewrite_rules(false);
}, 20);

/**
 * Customizer: Überschrift und Text der Bilderrotation (für alle Bilder gleich).
 */
add_action('customize_register', function ($wp_customize) {
	$wp_customize->add_section('wildtierhilfe_hero', [
		'title'    => __('Startseite: Bilderrotation', 'wildtierhilfe'),
		'priority' => 30,
	]);

	$wp_customize->add_setting('hero_title', [
		'default'           => get_bloginfo('name'),
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('hero_title', [
		'label'   => __('Überschrift', 'wildtierhilfe'),
		'section' => 'wildtierhilfe_hero',
		'type'    => 'text',
	]);

	$wp_customize->add_setting('hero_text', [
		'default'           => wildtierhilfe_hero_text_default(),
		'sanitize_callback' => 'sanitize_textarea_field',
	]);
	$wp_customize->add_control('hero_text', [
		'label'   => __('Text', 'wildtierhilfe'),
		'section' => 'wildtierhilfe_hero',
		'type'    => 'textarea',
	]);

	// PDF-Formulare: Links auf "?mitgliedsantrag" führen zur hier gewählten Datei
	$wp_customize->add_section('wildtierhilfe_formulare', [
		'title'    => __('Formulare (PDF)', 'wildtierhilfe'),
		'priority' => 31,
	]);

	$wp_customize->add_setting('mitgliedsantrag_pdf', [
		'sanitize_callback' => 'absint',
	]);
	$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mitgliedsantrag_pdf', [
		'label'       => __('Mitgliedsantrag', 'wildtierhilfe'),
		'description' => __('Link für Buttons: ?mitgliedsantrag', 'wildtierhilfe'),
		'section'     => 'wildtierhilfe_formulare',
		'mime_type'   => 'application/pdf',
	]));
});

/**
 * Feste Adresse für den Mitgliedsantrag: "?mitgliedsantrag" leitet zur im
 * Customizer gewählten PDF weiter. So bleibt der Link im Button gleich, auch
 * wenn die PDF ausgetauscht wird.
 */
add_action('template_redirect', function () {
	if (!isset($_GET['mitgliedsantrag'])) {
		return;
	}

	$url = wp_get_attachment_url((int) get_theme_mod('mitgliedsantrag_pdf'));
	wp_safe_redirect($url ?: home_url('/'), 302);
	exit;
});

function wildtierhilfe_hero_text_default()
{
	return __('Wir kümmern uns um verletzte und verwaiste Wildtiere und bereiten sie auf ihre Rückkehr in die Natur vor.', 'wildtierhilfe');
}

/**
 * Block-Stile "Infobox", "Aufruf" (Gruppe), "Tierart" (Medien und Text) und
 * "Karte" (Gruppe) mit passenden Vorlagen
 * (Editor: Block einfügen → Vorlagen). CSS: blocks.css
 */
add_action('init', function () {
	wp_register_style(
		'wildtierhilfe-blocks',
		get_theme_file_uri('blocks.css'),
		[],
		wildtierhilfe_asset_version('blocks.css')
	);

	// Absatz "Einleitung": größerer, zentrierter Text unter einer Abschnitts-Überschrift
	register_block_style('core/paragraph', [
		'name'         => 'einleitung',
		'label'        => __('Einleitung', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	register_block_style('core/group', [
		'name'         => 'infobox',
		'label'        => __('Infobox', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	register_block_pattern('wildtierhilfe/infobox', [
		'title'      => __('Infobox', 'wildtierhilfe'),
		'categories' => ['text'],
		'content'    => '<!-- wp:group {"className":"is-style-infobox"} -->
<div class="wp-block-group is-style-infobox"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html__('Überschrift', 'wildtierhilfe') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__('Hier steht der Text der Infobox.', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
	]);

	// Tierart: "Medien und Text"-Block; das Bild wechselt automatisch rechts/links.
	register_block_style('core/media-text', [
		'name'         => 'tierart',
		'label'        => __('Tierart', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	register_block_pattern('wildtierhilfe/tierart', [
		'title'      => __('Tierart', 'wildtierhilfe'),
		'categories' => ['text'],
		'content'    => '<!-- wp:media-text {"className":"is-style-tierart"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-tierart"><figure class="wp-block-media-text__media"></figure><div class="wp-block-media-text__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html__('Tierart', 'wildtierhilfe') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__('Hier steht der Text zur Tierart.', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->',
	]);

	// Karte: eine einzelne Karte mit Rahmen in Markengrün. Mehrere Karten
	// hintereinander stehen automatisch zu zweit nebeneinander (blocks.css).
	// Schalter "Volle Breite" und "Schatten statt Rahmen" in der Seitenleiste: editor.js
	register_block_style('core/group', [
		'name'         => 'karte',
		'label'        => __('Karte', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	register_block_pattern('wildtierhilfe/karte', [
		'title'      => __('Karte', 'wildtierhilfe'),
		'categories' => ['text'],
		'content'    => '<!-- wp:group {"className":"is-style-karte"} -->
<div class="wp-block-group is-style-karte"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html__('Überschrift', 'wildtierhilfe') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__('Hier steht der Text der Karte.', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>' . esc_html__('Erster Punkt', 'wildtierhilfe') . '</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>' . esc_html__('Zweiter Punkt', 'wildtierhilfe') . '</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->',
	]);

	// Aufruf: hervorgehobene Box mit zentriertem Text und Button.
	register_block_style('core/group', [
		'name'         => 'aufruf',
		'label'        => __('Aufruf', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	register_block_pattern('wildtierhilfe/aufruf', [
		'title'      => __('Aufruf mit Button', 'wildtierhilfe'),
		'categories' => ['call-to-action'],
		'content'    => '<!-- wp:group {"className":"is-style-aufruf"} -->
<div class="wp-block-group is-style-aufruf"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html__('Überschrift', 'wildtierhilfe') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__('Hier steht der Text des Aufrufs.', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#kontakt">' . esc_html__('Jetzt Kontakt aufnehmen', 'wildtierhilfe') . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
	]);

	// Tab: Gruppen mit Stil "Tab" direkt hintereinander werden auf der Website
	// zu Tabs (tabs.js), im Editor stehen sie untereinander. Tab-Text: Seitenleiste
	// → "Tab-Beschriftung" (editor.js). Darin sind alle Blöcke erlaubt, auch Karten.
	register_block_style('core/group', [
		'name'         => 'tab',
		'label'        => __('Tab', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	register_block_pattern('wildtierhilfe/tabs', [
		'title'      => __('Tabs', 'wildtierhilfe'),
		'categories' => ['text'],
		'content'    => '<!-- wp:group {"className":"is-style-tab","tabLabel":"' . esc_attr__('Tab 1', 'wildtierhilfe') . '"} -->
<div class="wp-block-group is-style-tab"><!-- wp:paragraph -->
<p>' . esc_html__('Inhalt des ersten Tabs.', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-tab","tabLabel":"' . esc_attr__('Tab 2', 'wildtierhilfe') . '"} -->
<div class="wp-block-group is-style-tab"><!-- wp:paragraph -->
<p>' . esc_html__('Inhalt des zweiten Tabs.', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
	]);

	// Kontaktangabe: Icon + fette Bezeichnung + Wert. Das Icon wird über den
	// Stil gewählt (Seitenleiste → Stile).
	$kontakt_stile = [
		'kontakt-email'     => __('Kontakt: E-Mail', 'wildtierhilfe'),
		'kontakt-telefon'   => __('Kontakt: Telefon', 'wildtierhilfe'),
		'kontakt-adresse'   => __('Kontakt: Adresse', 'wildtierhilfe'),
		'kontakt-instagram' => __('Kontakt: Instagram', 'wildtierhilfe'),
		'kontakt-facebook'  => __('Kontakt: Facebook', 'wildtierhilfe'),
	];

	foreach ($kontakt_stile as $name => $label) {
		register_block_style('core/group', [
			'name'         => $name,
			'label'        => $label,
			'style_handle' => 'wildtierhilfe-blocks',
		]);
	}

	register_block_pattern('wildtierhilfe/kontaktangabe', [
		'title'      => __('Kontaktangabe', 'wildtierhilfe'),
		'categories' => ['text'],
		'content'    => '<!-- wp:group {"className":"is-style-kontakt-email"} -->
<div class="wp-block-group is-style-kontakt-email"><!-- wp:paragraph -->
<p>' . esc_html__('E-Mail', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>' . esc_html__('name@beispiel.de', 'wildtierhilfe') . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
	]);

	// Popup: Gruppe mit Stil "Popup" und HTML-Anker (z. B. "spenden"). Auf der
	// Website ist sie zunächst unsichtbar und öffnet sich als Fenster, sobald ein
	// Link auf "#spenden" geklickt wird (popup.js). Im Editor steht sie als
	// gestrichelte Box im Inhalt. Ein Bild darin (z. B. ein GiroCode) wird
	// automatisch klein und zentriert angezeigt.
	register_block_style('core/group', [
		'name'         => 'popup',
		'label'        => __('Popup', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	// Bankangabe: erster Absatz = Bezeichnung, zweiter = Wert. Auf der Website
	// kommt ein "Kopieren"-Knopf dazu (popup.js).
	register_block_style('core/group', [
		'name'         => 'bankangabe',
		'label'        => __('Bankangabe', 'wildtierhilfe'),
		'style_handle' => 'wildtierhilfe-blocks',
	]);

	$bankangabe = function ($label, $value) {
		return '<!-- wp:group {"className":"is-style-bankangabe"} -->
<div class="wp-block-group is-style-bankangabe"><!-- wp:paragraph -->
<p>' . esc_html($label) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>' . esc_html($value) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
	};

	// Spenden-Popup: die Wege zu spenden als ausklappbare Abschnitte (Block
	// "Details"). Es ist immer nur einer offen (popup.js).
	$abschnitt = function ($titel, $inhalt) {
		return '<!-- wp:details -->
<details class="wp-block-details"><summary>' . esc_html($titel) . '</summary>' . $inhalt . '</details>
<!-- /wp:details -->';
	};

	$button = function ($text, $url) {
		return '<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"linkTarget":"_blank","rel":"noopener"} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html($text) . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->';
	};

	$absatz = function ($text) {
		return '<!-- wp:paragraph -->
<p>' . esc_html($text) . '</p>
<!-- /wp:paragraph -->';
	};

	register_block_pattern('wildtierhilfe/spenden-popup', [
		'title'      => __('Popup: Spenden', 'wildtierhilfe'),
		'categories' => ['call-to-action'],
		'content'    => '<!-- wp:group {"anchor":"spenden","className":"is-style-popup"} -->
<div id="spenden" class="wp-block-group is-style-popup"><!-- wp:heading -->
<h2 class="wp-block-heading">' . esc_html__('Spenden', 'wildtierhilfe') . '</h2>
<!-- /wp:heading -->

' . $absatz(__('Vielen Dank, dass Sie unsere Arbeit unterstützen möchten!', 'wildtierhilfe')) . '

' . $abschnitt(__('PayPal', 'wildtierhilfe'),
	$absatz(__('Schnell und einfach online spenden.', 'wildtierhilfe')) . "\n\n"
	. $button(__('Mit PayPal spenden', 'wildtierhilfe'), 'https://www.paypal.com/donate/?hosted_button_id=PLATZHALTER')
) . '

' . $abschnitt(__('Amazon-Wunschliste', 'wildtierhilfe'),
	$absatz(__('Sie möchten lieber Sachspenden schicken? Auf unserer Wunschliste finden Sie Futter, Pflegematerial und Zubehör, das wir gerade dringend brauchen.', 'wildtierhilfe')) . "\n\n"
	. $button(__('Zur Wunschliste', 'wildtierhilfe'), 'https://www.amazon.de/hz/wishlist/ls/PLATZHALTER')
) . '

' . $abschnitt(__('Überweisung', 'wildtierhilfe'),
	$bankangabe(__('Kontoinhaber', 'wildtierhilfe'), 'Wildtierhilfe e. V.') . "\n\n"
	. $bankangabe(__('IBAN', 'wildtierhilfe'), 'DE00 0000 0000 0000 0000 00') . "\n\n"
	. $bankangabe(__('BIC', 'wildtierhilfe'), 'XXXXXXXXXXX') . "\n\n"
	. $bankangabe(__('Bank', 'wildtierhilfe'), 'Name der Bank')
) . '</div>
<!-- /wp:group -->',
	]);
});

/**
 * CSS-Klassen eines Inhalts-Abschnitts (index.php, page.php) aus den ACF-Feldern
 * "Hintergrundfarbe" und "Inhaltsbreite".
 */
function wildtierhilfe_entry_classes()
{
	$background = function_exists('get_field') ? get_field('hintergrund') : '';
	$width      = function_exists('get_field') ? get_field('inhaltsbreite') : '';

	$classes = 'entry';
	$classes .= $background && $background !== 'auto' ? ' entry--' . $background : '';
	$classes .= $width === 'voll' ? ' entry--breit' : '';

	return $classes;
}

/**
 * ACF: Darstellung von Beiträgen und Seiten (Seitenleiste im Editor):
 * Hintergrundfarbe ("Automatisch" = abwechselnd weiß/mint) und
 * Inhaltsbreite (Lesebreite 56rem oder volle Breite).
 */
add_action('acf/init', function () {
	acf_add_local_field_group([
		'key'      => 'group_wildtierhilfe_beitrag',
		'title'    => __('Darstellung', 'wildtierhilfe'),
		'fields'   => [
			[
				'key'           => 'field_wildtierhilfe_hintergrund',
				'label'         => __('Hintergrundfarbe', 'wildtierhilfe'),
				'name'          => 'hintergrund',
				'type'          => 'select',
				'choices'       => [
					'auto'  => __('Automatisch (abwechselnd weiß/mint)', 'wildtierhilfe'),
					'white' => __('Weiß', 'wildtierhilfe'),
					'mint'  => __('Mint', 'wildtierhilfe'),
					'cream' => __('Creme', 'wildtierhilfe'),
					'sand'  => __('Sand', 'wildtierhilfe'),
				],
				'default_value' => 'auto',
				'return_format' => 'value',
			],
			[
				'key'           => 'field_wildtierhilfe_inhaltsbreite',
				'label'         => __('Inhaltsbreite', 'wildtierhilfe'),
				'name'          => 'inhaltsbreite',
				'type'          => 'select',
				'choices'       => [
					'lesebreite' => __('Lesebreite (Standard)', 'wildtierhilfe'),
					'voll'       => __('Volle Breite', 'wildtierhilfe'),
				],
				'default_value' => 'lesebreite',
				'return_format' => 'value',
			],
		],
		'location' => [
			[
				['param' => 'post_type', 'operator' => '==', 'value' => 'abschnitt'],
			],
			[
				['param' => 'post_type', 'operator' => '==', 'value' => 'page'],
			],
		],
		'position' => 'side',
	]);
});

/**
 * Startseite mit Bilderrotation: Klasse "has-hero" am body – das Karussell
 * beginnt dann ganz oben und der Header liegt darüber (style.css).
 */
add_filter('body_class', function ($classes) {
	if (wildtierhilfe_has_hero()) {
		$classes[] = 'has-hero';
	}

	return $classes;
});

/**
 * WordPress-Emoji-Skript abschalten: Es speichert ein Testergebnis im
 * sessionStorage des Browsers und lädt bei Bedarf Emoji-Grafiken von
 * s.w.org (WordPress.org). Moderne Browser stellen Emojis ohnehin selbst dar.
 * So setzt die Seite nichts auf dem Gerät der Besucher und lädt nichts von
 * fremden Servern (siehe auch: kein Cookie-Banner nötig).
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
add_filter('emoji_svg_url', '__return_false');

/**
 * Version einer Asset-Datei: Änderungszeitpunkt (verhindert Browser-Caching beim Entwickeln).
 * Fällt auf die Theme-Version zurück, falls die Datei (noch) nicht existiert.
 */
function wildtierhilfe_asset_version($relative_path)
{
	$file = get_theme_file_path($relative_path);
	return file_exists($file) ? filemtime($file) : wp_get_theme()->get('Version');
}

/**
 * Block-Editor: Aus Vorlagen eingefügte Blöcke (Karte, Infobox, Tierart …) voll
 * bearbeitbar machen. Ohne diese Einstellung sperrt WordPress sie auf
 * "nur Inhalt" – dann fehlen u. a. Ausrichtung, Farben und Stile.
 */
add_filter('block_editor_settings_all', function ($settings) {
	$settings['disableContentOnlyForUnsyncedPatterns'] = true;
	return $settings;
});

/**
 * Block-Editor: Schalter "Volle Breite" für Karten.
 */
add_action('enqueue_block_editor_assets', function () {
	wp_enqueue_script(
		'wildtierhilfe-editor',
		get_theme_file_uri('editor.js'),
		['wp-block-editor', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-hooks', 'wp-server-side-render'],
		wildtierhilfe_asset_version('editor.js'),
		true
	);
});

/**
 * Tab-Block erkennen: Tierart ("Medien und Text" mit Stil "Tierart") oder
 * Gruppe mit Stil "Tab".
 */
function wildtierhilfe_is_tab($block)
{
	$styles = [
		'core/media-text' => 'is-style-tierart',
		'core/group'      => 'is-style-tab',
	];
	$name = $block['blockName'] ?? '';

	return isset($styles[$name])
		&& str_contains($block['attrs']['className'] ?? '', $styles[$name]);
}

/**
 * Tab-Text und Anker eines Tabs (Tierart oder Gruppe mit Stil "Tab"). Tab-Text:
 * "Tab-Beschriftung" aus dem Editor (editor.js), sonst die erste Überschrift.
 * Anker: "HTML-Anker" aus dem Editor, sonst aus dem Tab-Text gebildet
 * (z. B. "Eichhörnchen" → "eichhoernchen").
 */
function wildtierhilfe_tab($block)
{
	$label = trim($block['attrs']['tabLabel'] ?? '');

	if ($label === '') {
		foreach ($block['innerBlocks'] as $inner) {
			if ($inner['blockName'] === 'core/heading') {
				$label = trim(wp_strip_all_tags($inner['innerHTML']));
				break;
			}
		}
	}

	$anchor = $block['attrs']['anchor'] ?? '';

	return [
		'label'  => $label,
		'anchor' => $anchor !== '' ? $anchor : sanitize_title($label),
	];
}

/**
 * Alle Tabs eines Beitrags (auch in verschachtelten Blöcken).
 */
function wildtierhilfe_post_tabs($post)
{
	$tabs = [];
	$walk = function ($blocks) use (&$walk, &$tabs) {
		foreach ($blocks as $block) {
			// Block "Tierarten-Vorschau": je Tierart ein Tab, der Menüpunkt führt zu ihrer Seite
			if ($block['blockName'] === 'wildtierhilfe/tierarten') {
				foreach (wildtierhilfe_tierarten() as $tierart) {
					$tabs[] = [
						'label'  => wildtierhilfe_tierart_label($tierart),
						'anchor' => $tierart->post_name,
						'url'    => get_permalink($tierart),
					];
				}
				continue;
			}

			if (wildtierhilfe_is_tab($block)) {
				$tab = wildtierhilfe_tab($block);
				if ($tab['label'] !== '') {
					$tabs[] = $tab;
				}
			}
			$walk($block['innerBlocks']);
		}
	};
	$walk(parse_blocks(get_post_field('post_content', $post)));

	return $tabs;
}

/**
 * Tabs (Tierarten und Gruppen mit Stil "Tab"): Tab-Text als data-tab-label und
 * Anker als id ausgeben, tabs.js liest beides aus. Über den Anker (z. B. "#igel")
 * öffnet sich der Tab.
 */
$wildtierhilfe_tab_attributes = function ($content, $block) {
	if (!wildtierhilfe_is_tab($block)) {
		return $content;
	}

	$tab = wildtierhilfe_tab($block);
	$tags = new WP_HTML_Tag_Processor($content);
	if ($tags->next_tag()) {
		if ($tab['label'] !== '') {
			$tags->set_attribute('data-tab-label', $tab['label']);
		}
		if ($tab['anchor'] !== '') {
			$tags->set_attribute('id', $tab['anchor']);
		}
	}
	return $tags->get_updated_html();
};
add_filter('render_block_core/media-text', $wildtierhilfe_tab_attributes, 10, 2);
add_filter('render_block_core/group', $wildtierhilfe_tab_attributes, 10, 2);

/**
 * Hauptmenü: Verweist ein Menüpunkt auf einen Abschnitt mit Tabs (z. B. "Tiere"
 * mit den Tierarten, "Helfen" mit Ehrenamt und Spende), bekommt er jeden Tab als
 * Unterpunkt. Neue Tabs erscheinen so automatisch im Menü. Hat der Menüpunkt
 * schon eigene Unterpunkte, bleibt er unverändert.
 */
add_filter('wp_nav_menu_objects', function ($items, $args) {
	if (($args->theme_location ?? '') !== 'primary') {
		return $items;
	}

	$parents = array_map(function ($item) {
		return (int) $item->menu_item_parent;
	}, $items);

	$result = [];
	foreach ($items as $item) {
		$result[] = $item;

		if ($item->object !== 'abschnitt' || in_array((int) $item->db_id, $parents, true)) {
			continue;
		}

		$tabs = wildtierhilfe_post_tabs($item->object_id);
		if (!$tabs) {
			continue;
		}

		$item->classes[] = 'menu-item-has-children';

		foreach ($tabs as $index => $tab) {
			// Ausgedachte, eindeutige ID: Diese Unterpunkte stehen nicht in der Datenbank
			$id = 'tab-' . $item->db_id . '-' . ($index + 1);

			$result[] = (object) [
				'ID'               => $id,
				'db_id'            => $id,
				'menu_item_parent' => $item->db_id,
				'object_id'        => 0,
				'object'           => 'custom',
				'type'             => 'custom',
				'title'            => $tab['label'],
				'url'              => $tab['url'] ?? home_url('/') . '#' . $tab['anchor'],
				'target'           => '',
				'attr_title'       => '',
				'description'      => '',
				'xfn'              => '',
				'classes'          => ['menu-item', 'menu-item-tab'],
				'current'          => false,
			];
		}
	}

	return $result;
}, 10, 2);

/**
 * CSS und JavaScript einbinden.
 */
add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'wildtierhilfe-style',
		get_stylesheet_uri(),
		[],
		wildtierhilfe_asset_version('style.css')
	);

	wp_enqueue_script(
		'wildtierhilfe-nav',
		get_theme_file_uri('nav.js'),
		[],
		wildtierhilfe_asset_version('nav.js'),
		true
	);

	wp_enqueue_script(
		'wildtierhilfe-tabs',
		get_theme_file_uri('tabs.js'),
		[],
		wildtierhilfe_asset_version('tabs.js'),
		true
	);

	wp_enqueue_script(
		'wildtierhilfe-popup',
		get_theme_file_uri('popup.js'),
		[],
		wildtierhilfe_asset_version('popup.js'),
		true
	);

	if (is_front_page()) {
		wp_enqueue_script(
			'wildtierhilfe-slider',
			get_theme_file_uri('slider.js'),
			[],
			wildtierhilfe_asset_version('slider.js'),
			true
		);
	}
});

/**
 * Anker-Links im Menü (z. B. "#mission") funktionieren auch auf Unterseiten:
 * Sie werden zu "<Startseiten-URL>/#mission". Ein reines "#" (Platzhalter für
 * Menüpunkte mit Untermenü) bleibt unverändert.
 */
add_filter('nav_menu_link_attributes', function ($atts) {
	$href = isset($atts['href']) ? $atts['href'] : '';

	if ($href !== '' && $href !== '#' && strpos($href, '#') === 0) {
		$atts['href'] = home_url('/') . $href;
	}

	return $atts;
});

/**
 * Alle Abschnitte stehen auf der Startseite, jeweils mit ihrem Slug als Anker
 * (siehe front-page.php). Diese URL führt zu einem Abschnitt: "<Startseiten-URL>/#slug".
 */
function wildtierhilfe_post_anchor_url($post)
{
	return home_url('/') . '#' . get_post_field('post_name', $post);
}

/**
 * Backend-Liste "Startseiten-Abschnitte": sortiert nach "Reihenfolge"
 * (kleinere Zahl = weiter oben), solange man nicht selbst auf eine Spalte zum
 * Sortieren klickt.
 */
add_action('pre_get_posts', function ($query) {
	if (is_admin() && $query->is_main_query() && $GLOBALS['pagenow'] === 'edit.php' && $query->get('post_type') === 'abschnitt' && empty($_GET['orderby'])) {
		$query->set('orderby', ['menu_order' => 'ASC', 'date' => 'ASC']);
	}
});

/**
 * Backend-Liste "Startseiten-Abschnitte": Spalte "Reihenfolge".
 */
add_filter('manage_abschnitt_posts_columns', function ($columns) {
	$columns['menu_order'] = __('Reihenfolge', 'wildtierhilfe');
	return $columns;
});

add_action('manage_abschnitt_posts_custom_column', function ($column, $post_id) {
	if ($column === 'menu_order') {
		echo (int) get_post_field('menu_order', $post_id);
	}
}, 10, 2);

/**
 * Menüpunkte, die auf einen Abschnitt zeigen, springen zu ihm auf der
 * Startseite statt die Einzelansicht zu öffnen.
 */
add_filter('nav_menu_link_attributes', function ($atts, $item) {
	if ($item->type === 'post_type' && $item->object === 'abschnitt') {
		$atts['href'] = wildtierhilfe_post_anchor_url($item->object_id);
	}

	return $atts;
}, 10, 2);

/**
 * Die Einzelansicht eines Abschnitts leitet zum Abschnitt auf der Startseite
 * weiter. Vorschauen aus dem Editor bleiben erreichbar.
 * Alte Links aus der Zeit, als die Abschnitte noch Beiträge waren
 * (z. B. ".../2025/10/23/mission/" aus Suchmaschinen), führen ebenfalls dorthin.
 */
add_action('template_redirect', function () {
	if (is_singular('abschnitt') && !is_preview()) {
		wp_safe_redirect(wildtierhilfe_post_anchor_url(get_queried_object_id()), 301);
		exit;
	}

	// Letzter Teil der aufgerufenen Adresse, z. B. "mission" aus ".../2025/10/23/mission/"
	$name = is_404() ? sanitize_title(basename((string) $GLOBALS['wp']->request)) : '';
	if ($name !== '') {
		$abschnitt = get_page_by_path($name, OBJECT, 'abschnitt');
		if ($abschnitt && $abschnitt->post_status === 'publish') {
			wp_safe_redirect(wildtierhilfe_post_anchor_url($abschnitt), 301);
			exit;
		}

		// Alte Beitrags-Links mit Datum (".../2026/11/09/erste-hilfe/") → "/ratgeber/erste-hilfe/"
		$beitrag = get_page_by_path($name, OBJECT, 'post');
		if ($beitrag && $beitrag->post_status === 'publish') {
			wp_safe_redirect(get_permalink($beitrag), 301);
			exit;
		}
	}
});
