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
});

/**
 * ACF: Darstellung des Beitrags-Abschnitts auf der Startseite (Seitenleiste im
 * Beitrag): Hintergrundfarbe ("Automatisch" = abwechselnd weiß/mint) und
 * Inhaltsbreite (Lesebreite 56rem oder volle Breite).
 */
add_action('acf/init', function () {
	acf_add_local_field_group([
		'key'      => 'group_wildtierhilfe_beitrag',
		'title'    => __('Darstellung auf der Startseite', 'wildtierhilfe'),
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
				['param' => 'post_type', 'operator' => '==', 'value' => 'post'],
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
	if (is_home()) {
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
		['wp-block-editor', 'wp-components', 'wp-compose', 'wp-element', 'wp-hooks'],
		wildtierhilfe_asset_version('editor.js'),
		true
	);
});

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

	if (is_home()) {
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
 * Alle Beiträge stehen auf der Startseite, jeweils mit ihrem Slug als Anker
 * (siehe index.php). Diese URL führt zu einem Beitrag: "<Startseiten-URL>/#slug".
 */
function wildtierhilfe_post_anchor_url($post)
{
	return home_url('/') . '#' . get_post_field('post_name', $post);
}

/**
 * Feste Reihenfolge der Beiträge: Feld "Reihenfolge" im Editor
 * (Seitenleiste → Beitrag), kleinere Zahl = weiter oben.
 */
add_action('init', function () {
	add_post_type_support('post', 'page-attributes');
});

/**
 * Die REST-Schnittstelle (über sie speichert der Block-Editor) hat für "post"
 * eine feste Feldliste ohne "menu_order" und verwirft den Wert sonst stillschweigend.
 * Deshalb hier: Feld ausgeben (Editor zeigt den gespeicherten Wert) und beim
 * Speichern übernehmen.
 */
add_action('rest_api_init', function () {
	register_rest_field('post', 'menu_order', [
		'get_callback' => function ($post) {
			return (int) get_post_field('menu_order', $post['id']);
		},
		'schema'       => [
			'description' => __('Reihenfolge auf der Startseite.', 'wildtierhilfe'),
			'type'        => 'integer',
			'context'     => ['view', 'edit'],
		],
	]);
});

add_filter('rest_pre_insert_post', function ($prepared_post, $request) {
	if (isset($request['menu_order'])) {
		$prepared_post->menu_order = (int) $request['menu_order'];
	}

	return $prepared_post;
}, 10, 2);

/**
 * Startseite: alle Beiträge ohne Seitenumbruch, sortiert nach "Reihenfolge".
 * Backend-Liste "Beiträge": dieselbe Sortierung, solange man nicht selbst
 * auf eine Spalte zum Sortieren klickt.
 */
add_action('pre_get_posts', function ($query) {
	if (!$query->is_main_query()) {
		return;
	}

	if (!is_admin() && $query->is_home()) {
		$query->set('posts_per_page', -1);
		$query->set('orderby', ['menu_order' => 'ASC', 'date' => 'ASC']);
		$query->set('ignore_sticky_posts', true);
	}

	if (is_admin() && $GLOBALS['pagenow'] === 'edit.php' && $query->get('post_type') === 'post' && empty($_GET['orderby'])) {
		$query->set('orderby', ['menu_order' => 'ASC', 'date' => 'ASC']);
	}
});

/**
 * Backend-Liste "Beiträge": Spalte "Reihenfolge".
 */
add_filter('manage_post_posts_columns', function ($columns) {
	$columns['menu_order'] = __('Reihenfolge', 'wildtierhilfe');
	return $columns;
});

add_action('manage_post_posts_custom_column', function ($column, $post_id) {
	if ($column === 'menu_order') {
		echo (int) get_post_field('menu_order', $post_id);
	}
}, 10, 2);

/**
 * Menüpunkte, die auf einen Beitrag zeigen, springen zu dessen Abschnitt
 * auf der Startseite statt die Einzelansicht zu öffnen.
 */
add_filter('nav_menu_link_attributes', function ($atts, $item) {
	if ($item->type === 'post_type' && $item->object === 'post') {
		$atts['href'] = wildtierhilfe_post_anchor_url($item->object_id);
	}

	return $atts;
}, 10, 2);

/**
 * Die Einzelansicht eines Beitrags (z. B. aus Suchmaschinen oder alten Links)
 * leitet ebenfalls zum Abschnitt auf der Startseite weiter. Vorschauen aus dem
 * Editor bleiben erreichbar.
 */
add_action('template_redirect', function () {
	if (is_singular('post') && !is_preview()) {
		wp_safe_redirect(wildtierhilfe_post_anchor_url(get_queried_object_id()));
		exit;
	}
});
