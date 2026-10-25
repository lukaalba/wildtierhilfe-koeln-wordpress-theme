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
})(window.wp);
