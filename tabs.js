// Tabs: Direkt aufeinanderfolgende Tierart-Blöcke bzw. Gruppen mit Stil "Tab"
// werden zusammengefasst. Tab-Text: "Tab-Beschriftung" aus dem Editor, sonst die
// Überschrift des Blocks.
// Ohne JavaScript (und im Editor) stehen die Blöcke wie bisher untereinander.
document.addEventListener('DOMContentLoaded', function () {
	var arten = [
		{ selector: '.wp-block-media-text.is-style-tierart', label: 'Tierarten' },
		{ selector: '.wp-block-group.is-style-tab', label: 'Bereiche' },
	];
	var groupCount = 0;
	var groups = [];

	arten.forEach(function (art) {
		document.querySelectorAll(art.selector).forEach(function (block) {
			// Nur beim ersten Block einer Gruppe starten
			var previous = block.previousElementSibling;
			if (previous && previous.matches(art.selector)) {
				return;
			}

			var panels = [block];
			var next = block.nextElementSibling;
			while (next && next.matches(art.selector)) {
				panels.push(next);
				next = next.nextElementSibling;
			}

			if (panels.length < 2) {
				return;
			}

			groupCount++;
			buildTabs(panels, 'tabs-' + groupCount, art.label);
		});
	});

	function buildTabs(panels, prefix, label) {
		var wrapper = document.createElement('div');
		wrapper.className = 'tabs';

		var list = document.createElement('div');
		list.className = 'tabs__list';
		list.setAttribute('role', 'tablist');
		list.setAttribute('aria-label', label);

		panels[0].parentNode.insertBefore(wrapper, panels[0]);
		wrapper.appendChild(list);

		var tabs = panels.map(function (panel, index) {
			var heading = panel.querySelector('h2, h3, h4');
			var tab = document.createElement('button');

			tab.type = 'button';
			tab.className = 'tabs__tab';
			tab.id = prefix + '-tab-' + (index + 1);
			tab.setAttribute('role', 'tab');
			// Beschriftung aus dem Editor, sonst die Überschrift des Blocks
			tab.textContent = panel.dataset.tabLabel
				|| (heading ? heading.textContent.trim() : 'Tab ' + (index + 1));

			panel.id = panel.id || prefix + '-panel-' + (index + 1);
			tab.setAttribute('aria-controls', panel.id);
			panel.setAttribute('role', 'tabpanel');
			panel.setAttribute('aria-labelledby', tab.id);

			list.appendChild(tab);
			wrapper.appendChild(panel);
			return tab;
		});

		function select(index) {
			tabs.forEach(function (tab, i) {
				var active = i === index;
				tab.setAttribute('aria-selected', String(active));
				tab.tabIndex = active ? 0 : -1;
				panels[i].hidden = !active;
			});
		}

		// Auf dem Smartphone scrollt die Tab-Leiste (nur seitlich) zum gewählten Tab
		function show(index) {
			select(index);
			list.scrollTo({ left: tabs[index].offsetLeft - (list.clientWidth - tabs[index].offsetWidth) / 2 });
		}

		groups.push({ panels: panels, show: show, wrapper: wrapper });

		list.addEventListener('click', function (event) {
			var tab = event.target.closest('.tabs__tab');
			if (tab) {
				show(tabs.indexOf(tab));
			}
		});

		// Pfeiltasten, Pos1 und Ende wechseln zwischen den Tabs
		list.addEventListener('keydown', function (event) {
			var current = tabs.indexOf(document.activeElement);
			var keys = {
				ArrowLeft: current - 1,
				ArrowRight: current + 1,
				Home: 0,
				End: tabs.length - 1,
			};

			if (current === -1 || !(event.key in keys)) {
				return;
			}

			event.preventDefault();
			var index = (keys[event.key] + tabs.length) % tabs.length;
			show(index);
			tabs[index].focus();
		});

		// Beim Laden ohne Scrollen: erster Tab aktiv
		select(0);
	}

	// Anker eines Tabs (z. B. "#igel", Unterpunkte von "Tiere" im Menü):
	// passenden Tab öffnen und zur Tab-Leiste scrollen.
	function openFromHash(hash) {
		var id = decodeURIComponent((hash || '').slice(1));

		return id !== '' && groups.some(function (group) {
			var index = group.panels.findIndex(function (panel) {
				return panel.id === id;
			});

			if (index === -1) {
				return false;
			}

			group.show(index);
			group.wrapper.scrollIntoView({ block: 'start' });
			return true;
		});
	}

	document.addEventListener('click', function (event) {
		var link = event.target.closest('a[href*="#"]');

		if (link && link.host === location.host && link.pathname === location.pathname && openFromHash(link.hash)) {
			event.preventDefault();
			history.pushState(null, '', link.hash);
		}
	});

	// Zurück-Taste des Browsers
	window.addEventListener('hashchange', function () {
		openFromHash(location.hash);
	});

	// Von einer anderen Seite kommend, z. B. Impressum → Menü → "Igel"
	openFromHash(location.hash);
});
