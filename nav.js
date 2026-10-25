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
