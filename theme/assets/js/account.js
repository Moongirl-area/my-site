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
