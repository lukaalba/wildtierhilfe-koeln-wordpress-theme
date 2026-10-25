document.addEventListener('DOMContentLoaded', function () {
	var hero = document.querySelector('.hero');

	if (!hero) {
		return;
	}

	var slides = hero.querySelectorAll('.hero__slide');
	var dots = hero.querySelectorAll('.hero__dot');

	if (slides.length < 2) {
		return;
	}

	var INTERVAL = 6000; // ms pro Bild
	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var current = 0;
	var timer = null;

	function show(index) {
		current = (index + slides.length) % slides.length;

		slides.forEach(function (slide, i) {
			slide.classList.toggle('is-active', i === current);
		});
		dots.forEach(function (dot, i) {
			dot.setAttribute('aria-current', String(i === current));
		});
	}

	// Automatischer Wechsel – nicht bei "Bewegung reduzieren" im Betriebssystem.
	function start() {
		stop();
		if (!reduceMotion) {
			timer = setInterval(function () {
				show(current + 1);
			}, INTERVAL);
		}
	}

	function stop() {
		clearInterval(timer);
	}

	// Nach manuellem Wechsel startet der Timer neu, damit das Bild nicht sofort weiterspringt.
	function go(index) {
		show(index);
		start();
	}

	hero.querySelector('.hero__arrow--prev').addEventListener('click', function () {
		go(current - 1);
	});

	hero.querySelector('.hero__arrow--next').addEventListener('click', function () {
		go(current + 1);
	});

	dots.forEach(function (dot, i) {
		dot.addEventListener('click', function () {
			go(i);
		});
	});

	// Pause, solange die Maus über dem Slider ist oder ein Button den Fokus hat.
	hero.addEventListener('mouseenter', stop);
	hero.addEventListener('mouseleave', start);
	hero.addEventListener('focusin', stop);
	hero.addEventListener('focusout', start);

	// Wischen auf dem Smartphone (dort sind die Pfeile ausgeblendet).
	var touchStartX = null;

	hero.addEventListener('touchstart', function (event) {
		touchStartX = event.touches[0].clientX;
	}, { passive: true });

	hero.addEventListener('touchend', function (event) {
		if (touchStartX === null) {
			return;
		}

		var deltaX = event.changedTouches[0].clientX - touchStartX;
		touchStartX = null;

		if (Math.abs(deltaX) > 50) {
			go(deltaX < 0 ? current + 1 : current - 1);
		}
	});

	start();
});
