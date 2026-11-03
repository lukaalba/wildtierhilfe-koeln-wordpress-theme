// Eingeloggt auf dem Smartphone: Die Admin-Leiste scrollt mit der Seite weg.
// Der sichtbare Rest ihrer Höhe bestimmt, wie weit der Header nach unten rückt.
document.addEventListener('DOMContentLoaded', function () {
	var adminBar = document.getElementById('wpadminbar');

	if (!adminBar) {
		return;
	}

	function update() {
		var offset = Math.max(0, adminBar.getBoundingClientRect().bottom);
		document.documentElement.style.setProperty('--admin-bar-offset', offset + 'px');
	}

	window.addEventListener('scroll', update, { passive: true });
	window.addEventListener('resize', update);
	update();
});

// Startseite: Header transparent, solange das Karussell hinter ihm zu sehen ist;
// sobald es nach oben aus dem Bild gescrollt ist, normaler Header.
document.addEventListener('DOMContentLoaded', function () {
	var header = document.getElementById('site-header');
	var hero = document.querySelector('.hero');

	if (!header || !hero || !('IntersectionObserver' in window)) {
		if (header) {
			header.classList.remove('is-transparent');
		}
		return;
	}

	// Beobachtet wird nur der Bereich unterhalb des Headers.
	var headerBottom = Math.round(header.getBoundingClientRect().bottom);

	new IntersectionObserver(function (entries) {
		header.classList.toggle('is-transparent', entries[0].isIntersecting);
	}, {
		rootMargin: '-' + headerBottom + 'px 0px 0px 0px',
	}).observe(hero);
});

document.addEventListener('DOMContentLoaded', function () {
	var toggle = document.getElementById('nav-toggle');
	var nav = document.getElementById('primary-nav');

	if (!toggle || !nav) {
		return;
	}

	function setOpen(open) {
		nav.classList.toggle('is-open', open);
		toggle.setAttribute('aria-expanded', String(open));
		toggle.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
	}

	// Alle Menüpunkte ausschreiben, wenn sie neben das Logo passen – sonst alle
	// im Hamburger-Menü. Gemessen wird mit ausgeschriebenem Menü.
	var header = document.getElementById('site-header');
	var inner = header && header.querySelector('.site-header__inner');

	function updateLayout() {
		header.classList.add('is-expanded');
		var fits = inner.scrollWidth <= inner.clientWidth;
		header.classList.toggle('is-expanded', fits);

		if (fits && nav.classList.contains('is-open')) {
			setOpen(false);
		}
	}

	if (inner) {
		updateLayout();
		window.addEventListener('resize', updateLayout);
		// Mit der Webschrift ändern sich die Textbreiten
		if (document.fonts) {
			document.fonts.ready.then(updateLayout);
		}
	}

	toggle.addEventListener('click', function () {
		setOpen(toggle.getAttribute('aria-expanded') !== 'true');
	});

	// Platzhalter-Links ("#") von Menüpunkten mit Untermenü sollen nicht nach oben springen.
	nav.querySelectorAll('a[href="#"]').forEach(function (link) {
		link.addEventListener('click', function (event) {
			event.preventDefault();
		});
	});

	// Menü schließen, sobald ein echter Link angeklickt wurde.
	nav.addEventListener('click', function (event) {
		var link = event.target.closest('a');
		if (link && link.getAttribute('href') !== '#') {
			setOpen(false);
		}
	});

	// Mit Escape schließen.
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			setOpen(false);
			toggle.focus();
		}
	});
});
