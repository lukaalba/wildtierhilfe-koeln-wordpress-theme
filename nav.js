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
