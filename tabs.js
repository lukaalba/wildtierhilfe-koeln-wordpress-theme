// Tierart-Blöcke als Tabs: Direkt aufeinanderfolgende Blöcke mit Stil "Tierart"
// werden zusammengefasst. Tab-Text: "Tab-Beschriftung" aus dem Editor, sonst die
// Überschrift des Blocks.
// Ohne JavaScript (und im Editor) stehen die Blöcke wie bisher untereinander.
document.addEventListener('DOMContentLoaded', function () {
	var selector = '.wp-block-media-text.is-style-tierart';
	var groupCount = 0;

	document.querySelectorAll(selector).forEach(function (block) {
		// Nur beim ersten Block einer Gruppe starten
		var previous = block.previousElementSibling;
		if (previous && previous.matches(selector)) {
			return;
		}

		var panels = [block];
		var next = block.nextElementSibling;
		while (next && next.matches(selector)) {
			panels.push(next);
			next = next.nextElementSibling;
		}

		if (panels.length < 2) {
			return;
		}

		groupCount++;
		buildTabs(panels, 'tierart-' + groupCount);
	});

	function buildTabs(panels, prefix) {
		var wrapper = document.createElement('div');
		wrapper.className = 'tierart-tabs';

		var list = document.createElement('div');
		list.className = 'tierart-tabs__list';
		list.setAttribute('role', 'tablist');
		list.setAttribute('aria-label', 'Tierarten');

		panels[0].parentNode.insertBefore(wrapper, panels[0]);
		wrapper.appendChild(list);

		var tabs = panels.map(function (panel, index) {
			var heading = panel.querySelector('.wp-block-media-text__content :is(h2, h3, h4)');
			var tab = document.createElement('button');

			tab.type = 'button';
			tab.className = 'tierart-tabs__tab';
			tab.id = prefix + '-tab-' + (index + 1);
			tab.setAttribute('role', 'tab');
			// Beschriftung aus dem Editor, sonst die Überschrift des Blocks
			tab.textContent = panel.dataset.tabLabel
				|| (heading ? heading.textContent.trim() : 'Tierart ' + (index + 1));

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

		// Auf dem Smartphone scrollt die Tab-Leiste zum gewählten Tab
		function show(index) {
			select(index);
			tabs[index].scrollIntoView({ block: 'nearest', inline: 'nearest' });
		}

		list.addEventListener('click', function (event) {
			var tab = event.target.closest('.tierart-tabs__tab');
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
});
