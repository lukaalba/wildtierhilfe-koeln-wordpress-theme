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
	 * Tierart ("Medien und Text" mit Stil "Tierart") und Gruppe mit Stil "Tab":
	 * eigene Beschriftung für den Tab (tabs.js). Leer gelassen wird die
	 * Überschrift des Blocks verwendet. Ausgegeben wird sie in functions.php
	 * als data-tab-label.
	 */
	var TAB_BLOECKE = {
		'core/media-text': { className: 'is-style-tierart', title: 'Tierart' },
		'core/group': { className: 'is-style-tab', title: 'Tab' },
	};

	wp.hooks.addFilter('blocks.registerBlockType', 'wildtierhilfe/tab-beschriftung', function (settings, name) {
		if (!TAB_BLOECKE[name]) {
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
			var block = TAB_BLOECKE[props.name];

			if (!block || classList(props.attributes.className).indexOf(block.className) === -1) {
				return el(BlockEdit, props);
			}

			return el(wp.element.Fragment, null,
				el(BlockEdit, props),
				el(wp.blockEditor.InspectorControls, null,
					el(wp.components.PanelBody, { title: block.title },
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

	/**
	 * Block "Tierarten-Vorschau": zeigt alle Tierarten (Backend → Tierarten) als
	 * Tabs. Inhalt und Aussehen kommen aus functions.php; im Editor stehen die
	 * Tierarten untereinander.
	 */
	wp.blocks.registerBlockType('wildtierhilfe/tierarten', {
		apiVersion: 3,
		title: 'Tierarten-Vorschau',
		description: 'Alle Tierarten als Tabs mit Bild, Textanfang und „Weiter lesen …“. Bearbeiten unter Tierarten.',
		category: 'widgets',
		icon: 'pets',
		supports: { html: false },
		edit: function () {
			return el('div', wp.blockEditor.useBlockProps(),
				el(wp.serverSideRender, { block: 'wildtierhilfe/tierarten' })
			);
		},
		save: function () {
			return null;
		},
	});

	/**
	 * Block "Beitrags-Übersicht": Beiträge als Karten. In der Seitenleiste
	 * werden Beiträge hinzugefügt (Suche), sortiert (↑/↓) und entfernt (×).
	 * Ohne Auswahl zeigt der Block die neuesten Beiträge (Anzahl einstellbar).
	 * Ausgabe: functions.php (wildtierhilfe_render_beitraege).
	 */
	function BeitraegeAuswahl(props) {
		var ausgewaehlt = props.attributes.beitraege || [];

		var beitraege = wp.data.useSelect(function (select) {
			return select('core').getEntityRecords('postType', 'post', {
				per_page: 100,
				status: 'publish',
				orderby: 'title',
				order: 'asc',
				_fields: 'id,title',
			});
		}, []) || [];

		function titel(id) {
			var beitrag = beitraege.find(function (b) {
				return b.id === id;
			});
			return beitrag ? (beitrag.title.rendered || '(ohne Titel)') : '…';
		}

		function setzen(ids) {
			props.setAttributes({ beitraege: ids });
		}

		function verschieben(index, richtung) {
			var ids = ausgewaehlt.slice();
			var ziel = index + richtung;
			ids.splice(ziel, 0, ids.splice(index, 1)[0]);
			setzen(ids);
		}

		var optionen = beitraege
			.filter(function (b) {
				return ausgewaehlt.indexOf(b.id) === -1;
			})
			.map(function (b) {
				return { value: String(b.id), label: b.title.rendered || '(ohne Titel)' };
			});

		return el(wp.components.PanelBody, { title: 'Beiträge' },
			ausgewaehlt.length
				? el('ol', { style: { margin: '0 0 16px', paddingLeft: '20px' } },
					ausgewaehlt.map(function (id, index) {
						return el('li', { key: id, style: { marginBottom: '4px' } },
							el('div', { style: { display: 'flex', alignItems: 'center', gap: '4px' } },
								el('span', { style: { flex: 1 } }, titel(id)),
								el(wp.components.Button, {
									icon: 'arrow-up-alt2', label: 'Nach oben', size: 'small',
									disabled: index === 0,
									onClick: function () { verschieben(index, -1); },
								}),
								el(wp.components.Button, {
									icon: 'arrow-down-alt2', label: 'Nach unten', size: 'small',
									disabled: index === ausgewaehlt.length - 1,
									onClick: function () { verschieben(index, 1); },
								}),
								el(wp.components.Button, {
									icon: 'no-alt', label: 'Entfernen', size: 'small', isDestructive: true,
									onClick: function () {
										setzen(ausgewaehlt.filter(function (other) { return other !== id; }));
									},
								})
							)
						);
					})
				)
				: el('p', null, 'Keine Beiträge ausgewählt – es erscheinen die neuesten.'),
			el(wp.components.ComboboxControl, {
				label: 'Beitrag hinzufügen',
				value: null,
				options: optionen,
				onChange: function (value) {
					if (value) {
						setzen(ausgewaehlt.concat(parseInt(value, 10)));
					}
				},
			}),
			ausgewaehlt.length ? null : el(wp.components.RangeControl, {
				label: 'Anzahl neueste Beiträge',
				min: 1,
				max: 12,
				value: props.attributes.anzahl,
				onChange: function (value) {
					props.setAttributes({ anzahl: value || 4 });
				},
			})
		);
	}

	wp.blocks.registerBlockType('wildtierhilfe/beitraege', {
		apiVersion: 3,
		title: 'Beitrags-Übersicht',
		description: 'Beiträge als Karten mit Titel und Kurztext. Auswahl und Reihenfolge in der Seitenleiste.',
		category: 'widgets',
		icon: 'grid-view',
		supports: { html: false },
		attributes: {
			beitraege: { type: 'array', items: { type: 'integer' }, default: [] },
			anzahl: { type: 'integer', default: 4 },
		},
		edit: function (props) {
			return el('div', wp.blockEditor.useBlockProps(),
				el(wp.blockEditor.InspectorControls, null, el(BeitraegeAuswahl, props)),
				el(wp.serverSideRender, {
					block: 'wildtierhilfe/beitraege',
					attributes: props.attributes,
					EmptyResponsePlaceholder: function () {
						return el('p', null, 'Beitrags-Übersicht: noch keine veröffentlichten Beiträge.');
					},
				})
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp);
