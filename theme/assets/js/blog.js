/* مجله‌ی سودپز: نوار پیشرفت خواندن، فهرست فعال، کپی لینک */
(function () {
	'use strict';

	// نوار پیشرفت برای مرورگرهایی که scroll-timeline ندارند
	var bar = document.querySelector('.sp-progress span');
	if (bar && !(window.CSS && CSS.supports && CSS.supports('animation-timeline: scroll()'))) {
		var tick = false;
		var update = function () {
			var h = document.documentElement;
			var max = h.scrollHeight - h.clientHeight;
			bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(1, h.scrollTop / max) : 0) + ')';
			tick = false;
		};
		window.addEventListener('scroll', function () {
			if (!tick) { tick = true; requestAnimationFrame(update); }
		}, { passive: true });
		update();
	}

	// بخش فعلی در فهرست
	var links = Array.prototype.slice.call(document.querySelectorAll('.sp-toc a'));
	if (links.length && 'IntersectionObserver' in window) {
		var map = {};
		links.forEach(function (a) { map[decodeURIComponent(a.hash.slice(1))] = a; });
		var obs = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting && map[e.target.id]) {
					links.forEach(function (l) { l.classList.remove('is-active'); });
					map[e.target.id].classList.add('is-active');
				}
			});
		}, { rootMargin: '-90px 0px -70% 0px' });
		Object.keys(map).forEach(function (id) {
			var el = document.getElementById(id);
			if (el) { obs.observe(el); }
		});
	}

	// کپی لینک
	var copy = document.querySelector('[data-copy]');
	if (copy) {
		copy.addEventListener('click', function () {
			var label = copy.querySelector('span');
			var done = function () {
				label.textContent = 'کپی شد';
				setTimeout(function () { label.textContent = 'کپی لینک'; }, 2000);
			};
			if (navigator.clipboard) {
				navigator.clipboard.writeText(copy.getAttribute('data-copy')).then(done, function () {});
			}
		});
	}
})();
