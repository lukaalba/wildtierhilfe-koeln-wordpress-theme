// Popups: Gruppen mit Stil "Popup" und HTML-Anker (z. B. "spenden") werden zu
// einem Fenster (<dialog>), das sich bei Klick auf einen Link zu "#spenden"
// öffnet. Schließen: ✕, Esc oder Klick neben das Fenster.
// Gruppen mit Stil "Bankangabe" darin bekommen einen "Kopieren"-Knopf,
// von den ausklappbaren Abschnitten (Block "Details") ist immer nur einer offen.
// Ohne JavaScript (und im Editor) steht der Inhalt ganz normal auf der Seite.
// Läuft sofort (Skript am Ende der Seite), damit das Popup nicht kurz im
// Inhalt aufblitzt und schon herausgelöst ist, bevor tabs.js die Tabs baut.
(function () {
	var popups = {};

	document.querySelectorAll('.wp-block-group.is-style-popup[id]').forEach(function (block) {
		var dialog = document.createElement('dialog');
		var heading = block.querySelector('h2, h3, h4');
		var close = document.createElement('button');

		dialog.className = 'popup';
		dialog.id = block.id;
		block.removeAttribute('id');

		if (heading) {
			heading.id = heading.id || dialog.id + '-titel';
			dialog.setAttribute('aria-labelledby', heading.id);
		}

		close.type = 'button';
		close.className = 'popup__close';
		close.setAttribute('aria-label', 'Schließen');
		close.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
		close.addEventListener('click', function () {
			dialog.close();
		});

		dialog.appendChild(close);
		dialog.appendChild(block);
		document.body.appendChild(dialog);

		// Klick auf den abgedunkelten Hintergrund schließt das Fenster
		dialog.addEventListener('click', function (event) {
			if (event.target === dialog) {
				dialog.close();
			}
		});

		// Ausklappbare Abschnitte (Block "Details"): immer nur einer offen.
		// "toggle" steigt nicht auf, daher Capture-Phase.
		dialog.addEventListener('toggle', function (event) {
			var details = event.target;
			if (details.tagName !== 'DETAILS' || !details.open) {
				return;
			}
			dialog.querySelectorAll('details[open]').forEach(function (other) {
				if (other !== details) {
					other.open = false;
				}
			});
		}, true);

		block.querySelectorAll('.wp-block-group.is-style-bankangabe').forEach(addCopyButton);

		popups[dialog.id] = dialog;
	});

	function addCopyButton(row) {
		var value = row.querySelector('p:last-of-type');

		if (!value || !navigator.clipboard) {
			return;
		}

		var button = document.createElement('button');
		button.type = 'button';
		button.className = 'bankangabe__copy';
		button.textContent = 'Kopieren';

		button.addEventListener('click', function () {
			var text = value.textContent.trim().replace(/\s+/g, ' ');
			// IBAN ohne Leerzeichen kopieren, das akzeptiert jede Banking-App
			if (/^[A-Z]{2}\d{2}[A-Z0-9 ]+$/.test(text)) {
				text = text.replace(/ /g, '');
			}

			navigator.clipboard.writeText(text).then(function () {
				button.textContent = 'Kopiert ✓';
				button.classList.add('is-copied');
				setTimeout(function () {
					button.textContent = 'Kopieren';
					button.classList.remove('is-copied');
				}, 2000);
			});
		});

		// neben die Absätze, also in den inneren Container, falls vorhanden
		value.parentNode.appendChild(button);
	}

	function open(hash) {
		var dialog = popups[decodeURIComponent((hash || '').slice(1))];

		if (!dialog) {
			return false;
		}
		if (!dialog.open) {
			dialog.showModal();
		}
		return true;
	}

	// Links auf "#spenden" öffnen das Popup statt zu springen. Capture-Phase,
	// damit andere Klick-Handler (tabs.js, Menü) den Link nicht vorher verarbeiten.
	document.addEventListener('click', function (event) {
		var link = event.target.closest('a[href*="#"]');

		if (link && link.host === location.host && link.pathname === location.pathname && open(link.hash)) {
			event.preventDefault();
		}
	}, true);

	// Von einer anderen Seite kommend, z. B. Link auf "/#spenden": Popup öffnen
	// und den Anker entfernen, damit es beim Neuladen nicht wieder aufgeht.
	if (open(location.hash)) {
		history.replaceState(null, '', location.pathname + location.search);
	}
})();
