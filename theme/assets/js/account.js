/* دکمه‌ی «ذخیره برای بعد» روی مطلب‌های مجله */
(function () {
	var A = window.SP_ACC || {};
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-bm]');
		if (!b) return;
		if (!A.in) { location.href = A.login; return; }
		var on = b.getAttribute('aria-pressed') !== 'true';
		b.setAttribute('aria-pressed', on ? 'true' : 'false');
		b.classList.toggle('is-on', on);
		var t = b.querySelector('.sp-bm__t');
		if (t) t.textContent = on ? 'ذخیره شد' : 'ذخیره برای بعد';
		fetch(A.rest, {
			method: 'POST', credentials: 'same-origin',
			headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': A.nonce },
			body: JSON.stringify({ id: +b.dataset.bm, on: on })
		}).then(function (r) { if (!r.ok) throw 0; }).catch(function () {
			b.setAttribute('aria-pressed', on ? 'false' : 'true');
			b.classList.toggle('is-on', !on);
			if (t) t.textContent = !on ? 'ذخیره شد' : 'ذخیره برای بعد';
		});
		if (b.dataset.row) { // در میز کار: حذف از فهرست
			var card = b.closest('.sp-dcard');
			if (card && !on) { card.style.opacity = .4; }
		}
	});
})();

/* تب‌های میز کار */
(function () {
	var rail = document.querySelector('.sp-rail');
	if (!rail) return;
	var btns = rail.querySelectorAll('[data-tab]');
	var panels = document.querySelectorAll('[data-panel]');
	function show(name, push) {
		var found = false;
		Array.prototype.forEach.call(panels, function (p) {
			var on = p.dataset.panel === name;
			if (on) found = true;
			p.classList.toggle('is-on', on);
			p.hidden = !on;
		});
		if (!found) return false;
		Array.prototype.forEach.call(btns, function (b) {
			var on = b.dataset.tab === name;
			b.classList.toggle('is-on', on);
			b.setAttribute('aria-selected', on ? 'true' : 'false');
		});
		try { localStorage.setItem('sp_dash_tab', name); } catch (e) {}
		replay(document.querySelector('[data-panel="' + name + '"]'));
		if (push && history.replaceState) history.replaceState(null, '', '#' + name);
		return true;
	}
	Array.prototype.forEach.call(btns, function (b) {
		b.addEventListener('click', function () { show(b.dataset.tab, true); window.scrollTo({ top: 0, behavior: 'smooth' }); });
	});
	function replay(panel) { // انیمیشن نمودارها با هر بار باز شدن تب دوباره اجرا شود
		if (!panel) return;
		var els = panel.querySelectorAll('.sp-col__t i, .sp-col__v, .sp-ring__s, .sp-dpie__key li, .sp-ring__c');
		Array.prototype.forEach.call(els, function (el) {
			el.style.animation = 'none'; void el.offsetWidth; el.style.animation = '';
		});
	}
	var start = (location.hash || '').replace('#', '');
	if (!start) { try { start = localStorage.getItem('sp_dash_tab') || ''; } catch (e) {} }
	if (start) show(start, false);
	window.addEventListener('hashchange', function () { show((location.hash || '').replace('#', ''), false); });
})();

/* میز کار: کپی شماره کارت، هماهنگی مبلغ با پلن، دکمه‌های رفتن به تب */
(function () {
	document.addEventListener('click', function (e) {
		var c = e.target.closest('[data-copy]');
		if (c) {
			var t = c.dataset.copy, done = function () {
				var s = c.querySelector('span') || c, old = c.textContent;
				c.textContent = 'کپی شد ✓';
				setTimeout(function () { c.textContent = old; }, 1600);
			};
			if (navigator.clipboard) navigator.clipboard.writeText(t).then(done, done);
			else {
				var i = document.createElement('input');
				i.value = t; document.body.appendChild(i); i.select();
				try { document.execCommand('copy'); } catch (err) {}
				document.body.removeChild(i); done();
			}
			return;
		}
		var g = e.target.closest('[data-tab-go]');
		if (g) {
			var b = document.querySelector('.sp-rail [data-tab="' + g.dataset.tabGo + '"]');
			if (b) b.click();
		}
	});
	var amt = document.getElementById('sp-pay-amount');
	if (amt) {
		Array.prototype.forEach.call(document.querySelectorAll('input[name="plan"]'), function (r) {
			r.addEventListener('change', function () {
				amt.value = (+r.dataset.price || 0).toLocaleString('fa-IR');
			});
		});
	}
})();
