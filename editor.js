/**
 * Block-Editor: Schalter in der Seitenleiste von Karten (Gruppen-Block mit
 * Stil "Karte"). Jeder Schalter setzt eine CSS-Klasse; das Aussehen dazu
 * steht in blocks.css.
 */
(function (wp) {
	var el = wp.element.createElement;

	var SCHALTER = [
		{
			className: 'is-karte-breit',
			label: 'Volle Breite',
			help: 'Aus: zwei Karten nebeneinander. An: Karte nimmt die ganze Zeile ein.',
		},
		{
			className: 'is-karte-schatten',
			label: 'Schatten statt Rahmen',
			help: 'Karte ohne grünen Rahmen, dafür mit Schatten.',
		},
	];

	function classList(className) {
		return (className || '').split(/\s+/).filter(Boolean);
	}

	var withKartenSchalter = wp.compose.createHigherOrderComponent(function (BlockEdit) {
		return function (props) {
			var classes = classList(props.attributes.className);

			if (props.name !== 'core/group' || classes.indexOf('is-style-karte') === -1) {
				return el(BlockEdit, props);
			}

			function setClass(className, on) {
				var next = classes.filter(function (c) {
					return c !== className;
				});
				if (on) {
					next.push(className);
				}
				props.setAttributes({ className: next.join(' ') || undefined });
			}

			return el(wp.element.Fragment, null,
				el(BlockEdit, props),
				el(wp.blockEditor.InspectorControls, null,
					el(wp.components.PanelBody, { title: 'Karte' },
						SCHALTER.map(function (schalter) {
							return el(wp.components.ToggleControl, {
								key: schalter.className,
								label: schalter.label,
								help: schalter.help,
								checked: classes.indexOf(schalter.className) !== -1,
								onChange: function (on) {
									setClass(schalter.className, on);
								},
							});
						})
					)
				)
			);
		};
	}, 'withKartenSchalter');

	wp.hooks.addFilter('editor.BlockEdit', 'wildtierhilfe/karten-schalter', withKartenSchalter);

	/**
	 * Tierart ("Medien und Text" mit Stil "Tierart"): eigene Beschriftung für den
	 * Tab (tabs.js). Leer gelassen wird die Überschrift des Blocks verwendet.
	 * Ausgegeben wird sie in functions.php als data-tab-label.
	 */
	wp.hooks.addFilter('blocks.registerBlockType', 'wildtierhilfe/tab-beschriftung', function (settings, name) {
		if (name !== 'core/media-text') {
			return settings;
		}
		return Object.assign({}, settings, {
			attributes: Object.assign({}, settings.attributes, {
				tabLabel: { type: 'string' },
			}),
		});
	});

	var withTabBeschriftung = wp.compose.createHigherOrderComponent(function (BlockEdit) {
		return function (props) {
			if (props.name !== 'core/media-text' || classList(props.attributes.className).indexOf('is-style-tierart') === -1) {
				return el(BlockEdit, props);
			}

			return el(wp.element.Fragment, null,
				el(BlockEdit, props),
				el(wp.blockEditor.InspectorControls, null,
					el(wp.components.PanelBody, { title: 'Tierart' },
						el(wp.components.TextControl, {
							label: 'Tab-Beschriftung',
							help: 'Kurzer Text für den Tab. Leer: Überschrift des Blocks.',
							value: props.attributes.tabLabel || '',
							onChange: function (value) {
								props.setAttributes({ tabLabel: value || undefined });
							},
						})
					)
				)
			);
		};
	}, 'withTabBeschriftung');

	wp.hooks.addFilter('editor.BlockEdit', 'wildtierhilfe/tab-beschriftung', withTabBeschriftung);
})(window.wp);
