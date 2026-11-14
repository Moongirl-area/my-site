/* سودپز: حرکت‌های صفحه‌ی اول (نمایش با اسکرول، چرخش بشقاب، پارالاکس، شمارش عدد).
   اگر کاربر «کاهش حرکت» را روشن کرده باشد هیچ‌کدام اجرا نمی‌شود. */
(function () {
	var root = document.documentElement;
	if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
	root.classList.add('sp-motion');

	function $$(s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); }

	/* ۱. نمایش با اسکرول، با تأخیر پله‌ای بین هم‌خانواده‌ها */
	var sel = ['.sp-pain', '.sp-section .sp-script', '.sp-section .sp-display--md', '.sp-section .sp-sub', '.sp-compare__card',
		'.sp-section .sp-actions', '.sp-how__step',
		'.sp-table-scene .sp-scene', '.sp-feature', '.sp-phone', '.sp-trust__grid > div', '.sp-final__inner > *', '.sp-marquee'];
	var els = [];
	sel.forEach(function (s) { $$(s).forEach(function (e) { if (!e.classList.contains('sp-rv')) { e.classList.add('sp-rv'); els.push(e); } }); });
	els.forEach(function (e) {
		var sib = Array.prototype.filter.call(e.parentNode.children, function (c) { return c.classList.contains('sp-rv'); });
		e.style.setProperty('--rv-d', Math.min(sib.indexOf(e), 6) * 90 + 'ms');
	});
	var io = new IntersectionObserver(function (es) {
		es.forEach(function (en) {
			if (!en.isIntersecting) return;
			en.target.classList.add('is-in');
			io.unobserve(en.target);
			$$('[data-count]', en.target).forEach(countUp);
		});
	}, { rootMargin: '0px 0px -6% 0px', threshold: 0.1 });
	els.forEach(function (e) { io.observe(e); });

	/* ۲. خط زعفرانی زیر تیترها که موقع دیده شدن کشیده می‌شود */
	$$('.sp-section .sp-display--md').forEach(function (h) {
		var s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
		s.setAttribute('class', 'sp-squiggle'); s.setAttribute('viewBox', '0 0 168 16'); s.setAttribute('aria-hidden', 'true');
		s.innerHTML = '<path d="M3 9 Q 23 1 43 9 T 83 9 T 123 9 T 165 9" pathLength="1"/>';
		h.appendChild(s);
	});

	/* ۳. شمارش عددها (عدد فارسی) */
	var fa = '۰۱۲۳۴۵۶۷۸۹';
	function toEn(t) { return t.replace(/[۰-۹]/g, function (d) { return fa.indexOf(d); }); }
	function toFa(n) { return String(n).replace(/\d/g, function (d) { return fa[d]; }); }
	$$('.sp-widget__big b, .sp-widget__row b, .sp-app-row em').forEach(function (b) {
		var m = toEn(b.textContent).match(/\d+/);
		if (m) { b.setAttribute('data-count', m[0]); b.setAttribute('data-tpl', b.textContent); }
	});
	function countUp(b) {
		var to = +b.getAttribute('data-count'), tpl = b.getAttribute('data-tpl'), t0 = null, dur = 1100;
		if (b._done) return; b._done = true;
		function step(t) {
			if (!t0) t0 = t;
			var p = Math.min((t - t0) / dur, 1), v = Math.round(to * (1 - Math.pow(1 - p, 3)));
			b.textContent = tpl.replace(/[۰-۹0-9]+/, toFa(v));
			if (p < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	var hero = document.querySelector('.sp-hero__art');
	if (hero) setTimeout(function () { $$('[data-count]', hero).forEach(countUp); }, 450);

	/* ۴. چرخش بشقاب‌ها و پارالاکس عکس با اسکرول */
	$$('.sp-plate--hero, .sp-plate--hero2, .sp-plate--scene').forEach(function (p, i) { p.setAttribute('data-spin', p.classList.contains('sp-plate--hero2') ? -50 : 70); });
	$$('.sp-dark__img').forEach(function (i) { i.setAttribute('data-parallax', 60); });
	var spin = $$('[data-spin]'), par = $$('[data-parallax]'), busy = false;
	function update() {
		busy = false;
		var vh = window.innerHeight;
		spin.forEach(function (e) {
			var r = e.getBoundingClientRect();
			if (!r.width || r.bottom < -100 || r.top > vh + 100) return;
			e.style.setProperty('--spin', (((r.top + r.height / 2) - vh / 2) / vh * e.getAttribute('data-spin')).toFixed(2) + 'deg');
		});
		par.forEach(function (e) {
			var r = e.parentNode.getBoundingClientRect();
			if (r.bottom < 0 || r.top > vh) return;
			e.style.setProperty('--py', (((r.top + r.height / 2) - vh / 2) / vh * -e.getAttribute('data-parallax')).toFixed(1) + 'px');
		});
	}
	window.addEventListener('scroll', function () { if (!busy) { busy = true; requestAnimationFrame(update); } }, { passive: true });
	window.addEventListener('resize', update);
	document.addEventListener('sp:kitchen', function () { setTimeout(update, 50); });
	update();
})();
