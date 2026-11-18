/*
 * ماشین‌حساب سودپز (نسخه‌ی ۲): دستورهای آماده + «دستور خودم».
 * داده‌ها از SP_DATA (inc/prices.php). دستورها، قیمت‌ها و ماده‌های خودِ کاربر فقط برای عضوها و در حساب کاربری (inc/account.php) ذخیره می‌شود.
 */
(function () {
	'use strict';
	var D = window.SP_DATA;
	var app = document.getElementById('sp-calc');
	if (!D || !app) return;

	/* ---------- ابزارها ---------- */
	var $ = function (id) { return document.getElementById(id); };
	var FA = '۰۱۲۳۴۵۶۷۸۹';
	var root = document.documentElement;
	function toEn(s) {
		return String(s == null ? '' : s)
			.replace(/[۰-۹]/g, function (d) { return FA.indexOf(d); })
			.replace(/[٠-٩]/g, function (d) { return '٠١٢٣٤٥٦٧٨٩'.indexOf(d); })
			.replace(/[٫,،/]/g, function (c) { return c === '٫' || c === '/' ? '.' : ''; })
			.replace(/[^0-9.]/g, '');
	}
	function num(v) { var n = parseFloat(toEn(v)); return isFinite(n) ? n : 0; }
	function faNum(n, digits) { return Number(n).toLocaleString('fa-IR', { maximumFractionDigits: digits == null ? 0 : digits }); }
	function toman(n) { return faNum(Math.round(n)) + ' تومان'; }
	function shortToman(n) {
		n = Math.round(n);
		if (n >= 1e6 && n % 1e4 === 0) return faNum(n / 1e6, 2) + ' میلیون';
		if (n >= 1e4 && n % 1e3 === 0) return faNum(n / 1e3) + ' هزار';
		return faNum(n);
	}
	function priceStep(v) { return v >= 1e6 ? 10000 : v >= 2e5 ? 5000 : v >= 2e4 ? 1000 : 500; }
	function roundTo(n, step) { return Math.round(n / step) * step; }
	function norm(s) { return String(s || '').replace(/ي/g, 'ی').replace(/ك/g, 'ک').replace(/[‌‏\s]+/g, ' ').trim(); }
	function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
	function el(tag, cls, html) { var e = document.createElement(tag); if (cls) e.className = cls; if (html != null) e.innerHTML = html; return e; }
	function icon(n) { return D.icons[n] || ''; }
	function uid(p) { return p + Date.now().toString(36) + Math.random().toString(36).slice(2, 6); }
	function clone(o) { return JSON.parse(JSON.stringify(o)); }

	/* ---------- ذخیره: فقط برای عضوها، در حساب کاربری (نه مرورگر) ---------- */
	var KEY = 'sp_calc_v2', PEND = 'sp_calc_pending', ME = D.user || { in: false };
	var store = { recipes: {}, prices: {}, ings: {}, prefs: {}, last: {}, draft: {} };
	function lsGet(k) { try { return JSON.parse(localStorage.getItem(k) || 'null'); } catch (e) { return null; } }
	function lsDel(k) { try { localStorage.removeItem(k); } catch (e) {} }
	function mergeIn(src, keys) { keys.forEach(function (k) { var o = src && src[k]; if (o && typeof o === 'object') for (var id in o) if (!(id in store[k])) store[k][id] = o[id]; }); }
	var saveTimer = 0;
	function persist() {
		if (!ME.in) return; // مهمان: چیزی نگه داشته نمی‌شود
		clearTimeout(saveTimer);
		saveTimer = setTimeout(function () {
			fetch(ME.rest, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': ME.nonce },
				body: JSON.stringify({ recipes: store.recipes, prices: store.prices, ings: store.ings, prefs: store.prefs, last: store.last }) })
				.then(function (r) { if (!r.ok) throw 0; })
				.catch(function () { status('ذخیره نشد؛ اینترنت رو چک کن'); });
		}, 700);
	}
	var migrated = false;
	if (ME.in) {
		var srv = ME.store || {};
		for (var sk in store) if (srv[sk] && typeof srv[sk] === 'object' && !Array.isArray(srv[sk])) store[sk] = srv[sk];
		var old = lsGet(KEY); // دستورهایی که قبل از عضویت در همین مرورگر مانده بود، به حساب منتقل شود
		if (old) { mergeIn(old, ['recipes', 'ings', 'prices']); lsDel(KEY); migrated = true; }
	}
	/* مهمان «ذخیره» زد: دستور موقتاً نگه داشته می‌شود تا بعد از عضویت/ورود در حسابش ذخیره شود */
	function needAccount() {
		try { localStorage.setItem(PEND, JSON.stringify({ r: R, ings: store.ings, prices: store.prices, t: Date.now() })); } catch (e) {}
		var h = $('sp-hint');
		h.innerHTML = 'برای ذخیره‌ی دستورت <a href="' + esc(ME.register) + '">عضو شو</a> (رایگانه) یا <a href="' + esc(ME.login) + '">وارد شو</a>. دستوری که نوشتی گم نمی‌شه.';
		h.classList.add('is-gate');
	}

	/* ---------- آشپزخونه ---------- */
	function kitchen() { return root.getAttribute('data-k') === 'food' ? 'food' : 'sweet'; }
	function isFood() { return R ? R.kitchen === 'food' : kitchen() === 'food'; }

	/* ---------- مواد و واحدها ---------- */
	var U = D.units;
	function ingOf(k) {
		if (k.indexOf('c:') === 0) {
			var c = store.ings[k.slice(2)];
			return c ? { name: c.name, unit: c.unit, d: 0, each: 0, p: 0, custom: true } : { name: 'ماده‌ی حذف‌شده', unit: 'کیلو', d: 0, each: 0, p: 0, custom: true };
		}
		return D.ingredients[k];
	}
	function refPrice(k) { var i = ingOf(k); return i && !i.custom ? (i.p || 0) : 0; }
	function priceOf(k) {
		if (k.indexOf('c:') === 0) { var c = store.ings[k.slice(2)]; return c ? (c.p || 0) : 0; }
		return Object.prototype.hasOwnProperty.call(store.prices, k) ? store.prices[k] : refPrice(k);
	}
	function unitsFor(ing) {
		var b = U[ing.unit]; if (!b) return [ing.unit];
		return Object.keys(U).filter(function (u) {
			var d = U[u].d;
			if (d === b.d) return true;
			if ((b.d === 'm' && d === 'v') || (b.d === 'v' && d === 'm')) return ing.d > 0;
			if (b.d === 'n' && d === 'm') return ing.each > 0;
			if (b.d === 'm' && d === 'n') return ing.each > 0;
			return false;
		});
	}
	/* مقدار q در واحد u → مقدار به واحد خرید ماده؛ NaN اگر تبدیل‌پذیر نیست. */
	function toBuy(ing, q, u) {
		var a = U[u], b = U[ing.unit];
		if (!a || !b) return u === ing.unit ? q : NaN;
		if (a.d === b.d) return q * a.f / b.f;
		var g; // به گرم
		if (a.d === 'm') g = q * a.f;
		else if (a.d === 'v') g = ing.d > 0 ? q * a.f * ing.d : NaN;
		else g = ing.each > 0 ? q * ing.each : NaN;
		if (!isFinite(g)) return NaN;
		if (b.d === 'm') return g / b.f;
		if (b.d === 'v') return ing.d > 0 ? g / ing.d / b.f : NaN;
		return ing.each > 0 ? g / ing.each : NaN;
	}
	function niceUnit(ing, q) {
		if (ing.unit === 'کیلو' && q < 1) return { q: Math.round(q * 1000), u: 'گرم' };
		if (ing.unit === 'لیتر' && q < 1) return { q: Math.round(q * 1000), u: 'میلی‌لیتر' };
		return { q: ing.unit === 'عدد' ? Math.ceil(q) : Math.round(q * 100) / 100, u: ing.unit };
	}
	function qtyStr(q, u) { return faNum(q, U[u] && (u === 'گرم' || u === 'میلی‌لیتر') ? 0 : 2); }

	/* ---------- مدل دستورِ در حال ویرایش ---------- */
	var R = null;        // دستور فعلی
	var dirty = false;   // دستور آماده عوض شده و ذخیره نشده

	function prefsFor(k) {
		var p = store.prefs || {};
		return {
			wage: p.wage != null ? p.wage : D.defaults.wage,
			margin: p.margin != null ? p.margin : (D.defaults.margin || 30),
			oven: p['oven_' + k] != null ? p['oven_' + k] : (k === 'food' ? D.defaults.stove : D.defaults.oven),
			pack: p['pack_' + k] != null ? p['pack_' + k] : (k === 'food' ? D.defaults.container : D.defaults.packaging)
		};
	}
	function fromLibrary(key) {
		var L = D.recipes[key], pf = prefsFor(L.kitchen);
		return {
			id: null, from: key, kitchen: L.kitchen, name: L.name, img: L.imgUrl || '',
			yq: L.yield[0], yu: L.yield[1], hours: L.hours, ovenh: L.oven,
			oven: pf.oven, pack: pf.pack, packPer: L.kitchen === 'food' ? 'unit' : 'batch', margin: pf.margin,
			wage: pf.wage, current: '',
			items: Object.keys(L.items).map(function (k) { var n = niceUnit(D.ingredients[k], L.items[k]); return { k: k, q: n.q, u: n.u }; }),
			extras: []
		};
	}
	function blank(k) {
		var pf = prefsFor(k);
		return {
			id: null, from: null, kitchen: k, name: '', img: '',
			yq: k === 'food' ? 10 : 1, yu: k === 'food' ? 'پرس' : 'کیلو', hours: '', ovenh: '',
			oven: pf.oven, pack: pf.pack, packPer: k === 'food' ? 'unit' : 'batch', margin: pf.margin,
			wage: pf.wage, current: '', items: [], extras: []
		};
	}

	/* کدام دستور الان باز است: 'lib:key' | 'my:id' | 'new' */
	function currentRef() { return R.id ? 'my:' + R.id : (R.from ? 'lib:' + R.from : 'new'); }

	function open(ref, k) {
		k = k || kitchen();
		if (ref === 'new') {
			R = store.draft[k] ? clone(store.draft[k]) : blank(k);
			R.id = null; R.from = null; R.kitchen = k;
		} else if (ref.indexOf('my:') === 0 && store.recipes[ref.slice(3)]) {
			R = clone(store.recipes[ref.slice(3)]);
		} else {
			var key = ref.indexOf('lib:') === 0 ? ref.slice(4) : ref;
			if (!D.recipes[key] || D.recipes[key].kitchen !== k) {
				key = Object.keys(D.recipes).filter(function (x) { return D.recipes[x].kitchen === k; })[0];
			}
			R = fromLibrary(key);
		}
		dirty = false;
		store.last[k] = currentRef(); persist();
		renderAll();
	}

	/* ---------- ذخیره‌ی خودکار دستورهای من ---------- */
	function touch() {
		if (R.id) {
			R.updated = Date.now();
			store.recipes[R.id] = clone(R);
			persist(); status('ذخیره شد ✓');
		} else if (!R.from) {
			store.draft[R.kitchen] = clone(R); persist();
		} else if (!dirty) {
			dirty = true; renderHead();
		}
	}
	var stTimer = 0;
	function status(t) { var s = $('sp-status'); s.textContent = t; clearTimeout(stTimer); stTimer = setTimeout(function () { s.textContent = ''; }, 1800); }

	/* ---------- انتخاب دستور ---------- */
	function myRecipesOf(k) {
		return Object.keys(store.recipes).map(function (id) { return store.recipes[id]; })
			.filter(function (r) { return r.kitchen === k; })
			.sort(function (a, b) { return (b.updated || 0) - (a.updated || 0); });
	}
	function renderPicker() {
		var k = kitchen(), q = norm($('sp-rsearch').value), grid = $('sp-rgrid'), cur = currentRef();
		grid.innerHTML = '';
		function card(ref, name, sub, imgUrl, iconSvg, color, extraCls) {
			if (q && norm(name).indexOf(q) < 0 && ref !== 'new') return;
			var b = el('button', 'sp-rcard' + (extraCls ? ' ' + extraCls : '') + (ref === cur ? ' is-on' : ''));
			b.type = 'button'; b.setAttribute('role', 'listitem'); b.dataset.ref = ref;
			b.style.setProperty('--c', color || '#E8673C');
			b.setAttribute('aria-pressed', ref === cur ? 'true' : 'false');
			b.innerHTML = (imgUrl ? '<img class="sp-rcard__img" src="' + esc(imgUrl) + '" alt="" loading="lazy" decoding="async">' : '<span class="sp-rcard__ph">' + iconSvg + '</span>') +
				'<span class="sp-rcard__name">' + esc(name) + '</span>' + (sub ? '<span class="sp-rcard__sub">' + sub + '</span>' : '');
			grid.appendChild(b);
		}
		card('new', 'دستور خودم', 'غذا یا کیک دلخواه', '', icon('plus'), '#2E2A6B', 'sp-rcard--new');
		myRecipesOf(k).forEach(function (r) { card('my:' + r.id, r.name || 'بی‌نام', '<i>مال من</i>', r.img || '', icon('user'), '#149C98', 'sp-rcard--mine'); });
		Object.keys(D.recipes).forEach(function (key) {
			var L = D.recipes[key]; if (L.kitchen !== k) return;
			card('lib:' + key, L.name, faNum(L.yield[0]) + ' ' + L.yield[1], L.imgUrl, L.iconSvg, L.color);
		});
		if (q && grid.children.length === 1) grid.appendChild(el('p', 'sp-rgrid__empty', 'دستوری با این اسم نداریم. «دستور خودم» رو بزن و خودت بسازش.'));
	}
	$('sp-rgrid').addEventListener('click', function (e) {
		var b = e.target.closest('.sp-rcard'); if (!b) return;
		open(b.dataset.ref);
		if (window.innerWidth < 900) { var t = document.querySelector('.sp-ed-head'); if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
		if (b.dataset.ref === 'new' && !R.name) setTimeout(function () { var a = document.activeElement; if (!a || a === document.body || a.classList.contains('sp-rcard')) $('sp-name').focus({ preventScroll: true }); }, 350);
	});
	$('sp-rsearch').addEventListener('input', renderPicker);

	/* ---------- سربرگ ویرایشگر ---------- */
	function renderHead() {
		$('sp-name').value = R.name || '';
		var badge = $('sp-badge'), hint = $('sp-hint'), save = $('sp-save');
		hint.classList.remove('is-gate');
		$('sp-del').hidden = !R.id;
		if (R.id) {
			badge.textContent = 'مال من'; badge.className = 'sp-ed-badge is-mine';
			save.hidden = true; hint.textContent = 'تغییرهات خودکار توی حسابت ذخیره می‌شه.';
		} else if (R.from) {
			badge.textContent = 'دستور آماده'; badge.className = 'sp-ed-badge';
			save.hidden = false;
			hint.textContent = dirty ? 'تغییرش دادی. برای اینکه بمونه، «ذخیره در دستورهای من» رو بزن.' : 'مقدارها نمونه‌ان. هر چیزی رو با دستور خودت عوض کن.';
		} else {
			badge.textContent = 'دستور جدید'; badge.className = 'sp-ed-badge is-new';
			save.hidden = false;
			hint.textContent = R.items.length ? 'مواد رو کامل کن و «ذخیره در دستورهای من» رو بزن.' : 'اسم بذار، تعداد پرس یا وزن رو بنویس و مواد رو یکی‌یکی اضافه کن.';
		}
	}
	$('sp-name').addEventListener('input', function () { R.name = this.value; touch(); updateResultHead(); if (R.id) renderPicker(); });

	$('sp-save').addEventListener('click', function () {
		if (!norm(R.name)) { $('sp-name').focus(); $('sp-hint').textContent = 'اول یه اسم برای دستورت بنویس.'; return; }
		if (!ME.in) { needAccount(); return; }
		if (!R.from) delete store.draft[R.kitchen];
		R.id = uid('r'); R.from = R.from || null; R.updated = Date.now();
		store.recipes[R.id] = clone(R); store.last[R.kitchen] = currentRef();
		dirty = false; persist(); renderHead(); renderPicker(); status('ذخیره شد ✓');
	});
	$('sp-dup').addEventListener('click', function () {
		if (!ME.in) { needAccount(); return; }
		var c = clone(R); c.id = uid('r'); c.name = (R.name || 'دستور') + ' (کپی)'; c.updated = Date.now();
		store.recipes[c.id] = c; persist(); open('my:' + c.id); status('کپی ساخته شد ✓');
	});
	var delArmed = 0;
	$('sp-del').addEventListener('click', function () {
		var b = this;
		if (!delArmed) {
			delArmed = setTimeout(function () { delArmed = 0; b.classList.remove('is-armed'); b.innerHTML = icon('trash'); }, 3000);
			b.classList.add('is-armed'); b.textContent = 'حذف بشه؟';
			return;
		}
		clearTimeout(delArmed); delArmed = 0; b.classList.remove('is-armed'); b.innerHTML = icon('trash');
		delete store.recipes[R.id]; store.last[R.kitchen] = ''; persist();
		open('lib:'); status('حذف شد');
	});

	/* ---------- خروجی، ساعت، دستمزد ---------- */
	function fillSelect(sel, opts, val) {
		sel.innerHTML = '';
		opts.forEach(function (o) { var op = document.createElement('option'); op.value = o[0]; op.textContent = o[1]; if (String(o[0]) === String(val)) op.selected = true; sel.appendChild(op); });
	}
	function renderBasics() {
		$('sp-yq').value = R.yq ? faNum(R.yq, 2) : '';
		var yus = D.yieldUnits.slice(); if (yus.indexOf(R.yu) < 0) yus.push(R.yu);
		fillSelect($('sp-yu'), yus.map(function (u) { return [u, u]; }), R.yu);
		$('sp-hours').value = R.hours !== '' && R.hours != null ? faNum(R.hours, 2) : '';
		$('sp-wage').value = R.wage ? faNum(R.wage) : '';
		$('sp-oven').value = R.oven ? faNum(R.oven) : '';
		$('sp-ovenh').value = R.ovenh !== '' && R.ovenh != null ? faNum(R.ovenh, 2) : '';
		$('sp-pack').value = R.pack ? faNum(R.pack) : '';
		$('sp-margin').value = R.margin != null ? faNum(R.margin, 1) : '';
		$('sp-current').value = R.current ? faNum(R.current) : '';
		renderPackPer();
	}
	function renderPackPer() { fillSelect($('sp-packper'), [['batch', 'برای کل'], ['unit', 'برای هر ' + R.yu]], R.packPer); }

	var lastYq = null;
	$('sp-yq').addEventListener('focus', function () { lastYq = num(this.value) || R.yq; });
	$('sp-yq').addEventListener('input', function () {
		var n = num(this.value); if (!(n > 0)) return;
		var old = R.yq || n, s = n / old;
		R.yq = n;
		if (s !== 1 && isFinite(s)) {
			R.items.forEach(function (it) {
				var q = it.q * s;
				it.q = it.u === 'عدد' ? Math.max(1, Math.round(q)) : (q >= 10 ? Math.round(q) : Math.round(q * 100) / 100);
			});
			if (num(R.hours)) R.hours = Math.round(num(R.hours) * (0.5 + 0.5 * s) * 4) / 4;
			if (num(R.ovenh)) R.ovenh = Math.round(num(R.ovenh) * (0.6 + 0.4 * s) * 4) / 4;
			$('sp-hours').value = R.hours !== '' ? faNum(R.hours, 2) : '';
			$('sp-ovenh').value = R.ovenh !== '' ? faNum(R.ovenh, 2) : '';
			renderItems();
		}
		touch(); calc();
	});
	$('sp-yu').addEventListener('change', function () { R.yu = this.value; renderPackPer(); touch(); calc(); });

	function bindNum(id, field, prefKey) {
		$(id).addEventListener('input', function () {
			R[field] = this.value.trim() === '' ? '' : num(this.value);
			if (prefKey) { store.prefs[typeof prefKey === 'function' ? prefKey() : prefKey] = num(this.value); persist(); }
			touch(); calc();
		});
	}
	bindNum('sp-hours', 'hours');
	bindNum('sp-wage', 'wage', 'wage');
	bindNum('sp-oven', 'oven', function () { return 'oven_' + R.kitchen; });
	bindNum('sp-ovenh', 'ovenh');
	bindNum('sp-pack', 'pack', function () { return 'pack_' + R.kitchen; });
	bindNum('sp-margin', 'margin', 'margin');
	$('sp-current').addEventListener('input', function () { R.current = num(this.value); touch(); calc(); });
	$('sp-packper').addEventListener('change', function () { R.packPer = this.value; touch(); calc(); });

	/* ---------- ردیف‌های مواد ---------- */
	function srcNote(it) {
		var ing = ingOf(it.k), ref = refPrice(it.k), mine = priceOf(it.k);
		if (ing.custom) return mine ? '<span class="sp-src is-mine">' + icon('user') + ' ماده‌ی خودت</span>' : '<span class="sp-src is-warn">قیمت خریدت رو بنویس</span>';
		if (!ref) return mine ? '<span class="sp-src is-mine">' + icon('user') + ' قیمت خودت</span>' : '<span class="sp-src is-warn">' + icon('info') + ' قیمت مرجع نداریم؛ قیمت خریدت رو بنویس</span>';
		var refTxt = 'پیشنهاد سودپز: ' + shortToman(ref) + ' تومان هر ' + ing.unit;
		var srcTxt = (ing.src ? ' · ' + esc(ing.src) : '') + (ing.date ? '، ' + esc(ing.date) : '');
		if (Math.abs(mine - ref) > 0.5) return '<span class="sp-src is-mine">' + icon('user') + ' قیمت خودت · ' + refTxt + ' <button type="button" class="sp-src__reset" data-reset="' + esc(it.k) + '">برگرد به پیشنهاد</button></span>';
		return '<span class="sp-src">' + icon('tag') + ' ' + refTxt + srcTxt + '</span>';
	}
	function rowCost(it) {
		var ing = ingOf(it.k), b = toBuy(ing, num(it.q), it.u), p = priceOf(it.k);
		return { ok: isFinite(b) && p > 0, v: isFinite(b) ? b * p : 0, conv: isFinite(b) };
	}
	function renderItems() {
		var list = $('sp-ilist'); list.innerHTML = '';
		if (!R.items.length) {
			list.appendChild(el('p', 'sp-ilist__empty', icon('list') + ' هنوز ماده‌ای نداری. از کادر پایین اولین ماده رو اضافه کن.'));
			return;
		}
		R.items.forEach(function (it, i) {
			var ing = ingOf(it.k), row = el('div', 'sp-irow' + (ing.custom ? ' is-custom' : '')); row.dataset.i = i;
			var units = ing.custom ? Object.keys(U).filter(function (u) { return U[u].d === (U[ing.unit] || {}).d; }) : unitsFor(ing);
			if (units.indexOf(it.u) < 0) units.unshift(it.u);
			var p = priceOf(it.k);
			row.innerHTML =
				'<div class="sp-irow__name">' + (ing.custom ? '<input class="sp-irow__nm" type="text" value="' + esc(ing.name) + '" aria-label="اسم ماده">' : '<b>' + esc(ing.name) + '</b>') + '</div>' +
				'<div class="sp-irow__qty"><label>مقدار</label><div class="sp-qty"><input class="sp-irow__q" type="text" inputmode="decimal" value="' + (it.q !== '' ? qtyStr(it.q, it.u) : '') + '" aria-label="مقدار ' + esc(ing.name) + '"><select class="sp-irow__u" aria-label="واحد مقدار"></select></div></div>' +
				'<div class="sp-irow__price"><label>قیمت هر ' + (ing.custom ? '' : esc(ing.unit)) + '</label><div class="sp-qty' + (ing.custom ? '' : ' sp-qty--solo') + '"><input class="sp-irow__p" type="text" inputmode="numeric" value="' + (p ? faNum(p) : '') + '" placeholder="تومان" aria-label="قیمت هر ' + esc(ing.unit) + ' ' + esc(ing.name) + '">' + (ing.custom ? '<select class="sp-irow__bu" aria-label="واحد خرید"></select>' : '') + '</div></div>' +
				'<div class="sp-irow__cost"><label>هزینه</label><b>—</b></div>' +
				'<button type="button" class="sp-iconbtn sp-irow__del" aria-label="حذف ' + esc(ing.name) + '">' + icon('x') + '</button>' +
				'<div class="sp-irow__src">' + srcNote(it) + '</div>';
			fillSelect(row.querySelector('.sp-irow__u'), units.map(function (u) { return [u, u]; }), it.u);
			if (ing.custom) fillSelect(row.querySelector('.sp-irow__bu'), ['کیلو', 'گرم', 'لیتر', 'میلی‌لیتر', 'عدد'].map(function (u) { return [u, u]; }), ing.unit);
			list.appendChild(row);
			updateRow(row, it);
		});
	}
	function updateRow(row, it) {
		var c = rowCost(it), cell = row.querySelector('.sp-irow__cost b');
		cell.textContent = c.ok ? shortToman(c.v) : '—';
		row.classList.toggle('is-missing', !c.ok);
		row.querySelector('.sp-irow__src').innerHTML = !c.conv ? '<span class="sp-src is-warn">' + icon('info') + ' این واحد برای این ماده قابل تبدیل نیست</span>' : srcNote(it);
	}
	function rowCtx(e) { var row = e.target.closest('.sp-irow'); return row ? { row: row, it: R.items[+row.dataset.i] } : null; }
	var ilist = $('sp-ilist');
	ilist.addEventListener('input', function (e) {
		var c = rowCtx(e); if (!c) return; var t = e.target, it = c.it;
		if (t.classList.contains('sp-irow__q')) it.q = t.value.trim() === '' ? '' : num(t.value);
		else if (t.classList.contains('sp-irow__p')) {
			var v = num(t.value);
			if (it.k.indexOf('c:') === 0) { store.ings[it.k.slice(2)].p = v; }
			else if (t.value.trim() === '' || Math.abs(v - refPrice(it.k)) < 0.5) delete store.prices[it.k];
			else store.prices[it.k] = v;
			persist();
			// همان ماده در ردیف‌های دیگر
			Array.prototype.forEach.call(ilist.querySelectorAll('.sp-irow'), function (r) { var o = R.items[+r.dataset.i]; if (o.k === it.k) updateRow(r, o); });
		} else if (t.classList.contains('sp-irow__nm')) { store.ings[it.k.slice(2)].name = t.value; persist(); }
		updateRow(c.row, it); touch(); calc();
	});
	ilist.addEventListener('change', function (e) {
		var c = rowCtx(e); if (!c) return; var t = e.target;
		if (t.classList.contains('sp-irow__u')) { c.it.u = t.value; updateRow(c.row, c.it); }
		else if (t.classList.contains('sp-irow__bu')) {
			var ci = store.ings[c.it.k.slice(2)]; ci.unit = t.value; persist();
			if ((U[c.it.u] || {}).d !== U[t.value].d) c.it.u = t.value;
			renderItems();
		}
		touch(); calc();
	});
	ilist.addEventListener('click', function (e) {
		var rs = e.target.closest('.sp-src__reset');
		if (rs) { delete store.prices[rs.dataset.reset]; persist(); renderItems(); calc(); return; }
		var d = e.target.closest('.sp-irow__del'); if (!d) return;
		var c = rowCtx(e); R.items.splice(+c.row.dataset.i, 1); renderItems(); touch(); calc(); renderHead();
	});

	/* ---------- افزودن ماده (جست‌وجو) ---------- */
	var addIn = $('sp-iadd'), pop = $('sp-ilist-pop'), opts = [], act = -1;
	var popular = {
		sweet: ['flour', 'sugar', 'egg', 'butter', 'cream', 'milk', 'oil', 'cocoa', 'chocolate', 'bakingpowder', 'vanilla', 'creamcheese'],
		food: ['rice', 'meat', 'chicken', 'mince', 'onion', 'oil', 'tomatopaste', 'potato', 'saffron', 'splitpea', 'herbs', 'spice']
	};
	function searchIngs(q) {
		var nq = norm(q), out = [];
		var all = Object.keys(D.ingredients).map(function (k) { return { k: k, ing: D.ingredients[k] }; })
			.concat(Object.keys(store.ings).map(function (id) { return { k: 'c:' + id, ing: ingOf('c:' + id) }; }));
		if (!nq) {
			var mine = Object.keys(store.ings).slice(-3).map(function (id) { return { k: 'c:' + id, ing: ingOf('c:' + id) }; });
			return mine.concat(popular[R.kitchen].map(function (k) { return { k: k, ing: D.ingredients[k] }; }));
		}
		all.forEach(function (x) {
			var hay = norm(x.ing.name + ' ' + (x.ing.alias || ''));
			var i = hay.indexOf(nq); if (i < 0) return;
			out.push({ k: x.k, ing: x.ing, score: (norm(x.ing.name).indexOf(nq) === 0 ? 0 : 1) + (i > 0 ? 0.5 : 0) });
		});
		out.sort(function (a, b) { return a.score - b.score; });
		return out.slice(0, 8);
	}
	function openPop() {
		var q = addIn.value; opts = searchIngs(q); act = opts.length ? 0 : -1;
		var nq = norm(q);
		if (nq && !opts.some(function (o) { return norm(o.ing.name) === nq; })) opts.push({ k: '__new', name: q.trim() });
		if (act < 0 && opts.length) act = 0;
		pop.innerHTML = '';
		if (!nq) pop.appendChild(el('li', 'sp-combo__label', 'پرکاربردها'));
		opts.forEach(function (o, i) {
			var li = el('li', 'sp-combo__opt' + (i === act ? ' is-act' : '')); li.setAttribute('role', 'option'); li.id = 'sp-opt-' + i; li.dataset.i = i;
			if (o.k === '__new') {
				li.classList.add('is-new');
				li.innerHTML = icon('plus') + '<span>ماده‌ی جدید: «<b>' + esc(o.name) + '</b>»</span><small>با واحد و قیمت خودت</small>';
			} else {
				var p = priceOf(o.k), used = R.items.some(function (it) { return it.k === o.k; });
				li.innerHTML = '<span>' + esc(o.ing.name) + (used ? ' <em>(توی لیست هست)</em>' : '') + '</span><small>' + (o.ing.custom ? 'ماده‌ی خودت' : esc(D.groups[o.ing.group] || '')) + (p ? ' · ' + shortToman(p) + ' هر ' + esc(o.ing.unit) : '') + '</small>';
			}
			pop.appendChild(li);
		});
		pop.hidden = !opts.length; addIn.setAttribute('aria-expanded', opts.length ? 'true' : 'false');
		if (act >= 0) addIn.setAttribute('aria-activedescendant', 'sp-opt-' + act);
	}
	function closePop() { pop.hidden = true; addIn.setAttribute('aria-expanded', 'false'); addIn.removeAttribute('aria-activedescendant'); }
	function choose(i) {
		var o = opts[i]; if (!o) return;
		var k = o.k;
		if (k === '__new') {
			var id = uid('i'); store.ings[id] = { name: o.name, unit: 'کیلو', p: 0 }; persist(); k = 'c:' + id;
		}
		var idx = -1; R.items.forEach(function (it, j) { if (it.k === k) idx = j; });
		if (idx < 0) {
			var ing = ingOf(k);
			var u = ing.unit === 'کیلو' ? 'گرم' : ing.unit === 'لیتر' ? 'میلی‌لیتر' : ing.unit;
			R.items.push({ k: k, q: '', u: u }); idx = R.items.length - 1;
			renderItems(); touch(); calc(); renderHead();
		}
		addIn.value = ''; closePop();
		var row = ilist.querySelector('.sp-irow[data-i="' + idx + '"]');
		if (row) { row.classList.add('is-flash'); setTimeout(function () { row.classList.remove('is-flash'); }, 900); var f = row.querySelector(k.indexOf('c:') === 0 && !num(row.querySelector('.sp-irow__q').value) ? '.sp-irow__q' : '.sp-irow__q'); f.focus(); }
	}
	addIn.addEventListener('input', openPop);
	addIn.addEventListener('focus', openPop);
	addIn.addEventListener('keydown', function (e) {
		if (pop.hidden && (e.key === 'ArrowDown' || e.key === 'Enter')) { openPop(); }
		if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
			e.preventDefault(); act = (act + (e.key === 'ArrowDown' ? 1 : -1) + opts.length) % opts.length;
			Array.prototype.forEach.call(pop.querySelectorAll('.sp-combo__opt'), function (li, i) { li.classList.toggle('is-act', i === act); });
			addIn.setAttribute('aria-activedescendant', 'sp-opt-' + act);
		} else if (e.key === 'Enter') { e.preventDefault(); if (act >= 0) choose(act); }
		else if (e.key === 'Escape') closePop();
	});
	pop.addEventListener('mousedown', function (e) { e.preventDefault(); });
	pop.addEventListener('click', function (e) { var li = e.target.closest('.sp-combo__opt'); if (li) choose(+li.dataset.i); });
	addIn.addEventListener('blur', function () { setTimeout(closePop, 120); });

	/* ---------- هزینه‌های دیگر ---------- */
	function renderExtras() {
		var box = $('sp-extras'); box.innerHTML = '';
		R.extras.forEach(function (x, i) {
			var row = el('div', 'sp-xrow'); row.dataset.i = i;
			row.innerHTML = '<input class="sp-xrow__n" type="text" value="' + esc(x.n) + '" placeholder="مثلاً پیک" aria-label="اسم هزینه">' +
				'<div class="sp-qty"><input class="sp-xrow__a" type="text" inputmode="numeric" value="' + (x.a ? faNum(x.a) : '') + '" placeholder="تومان" aria-label="مبلغ"><select class="sp-xrow__per" aria-label="برای"></select></div>' +
				'<button type="button" class="sp-iconbtn sp-xrow__del" aria-label="حذف این هزینه">' + icon('x') + '</button>';
			fillSelect(row.querySelector('.sp-xrow__per'), [['batch', 'برای کل'], ['unit', 'برای هر ' + R.yu]], x.per);
			box.appendChild(row);
		});
	}
	$('sp-xadd').addEventListener('click', function () { R.extras.push({ n: '', a: '', per: 'batch' }); renderExtras(); touch(); $('sp-extras').lastChild.querySelector('input').focus(); });
	$('sp-extras').addEventListener('input', function (e) {
		var row = e.target.closest('.sp-xrow'); if (!row) return; var x = R.extras[+row.dataset.i];
		if (e.target.classList.contains('sp-xrow__n')) x.n = e.target.value; else if (e.target.classList.contains('sp-xrow__a')) x.a = num(e.target.value);
		touch(); calc();
	});
	$('sp-extras').addEventListener('change', function (e) { var row = e.target.closest('.sp-xrow'); if (row && e.target.classList.contains('sp-xrow__per')) { R.extras[+row.dataset.i].per = e.target.value; touch(); calc(); } });
	$('sp-extras').addEventListener('click', function (e) { var d = e.target.closest('.sp-xrow__del'); if (!d) return; R.extras.splice(+d.closest('.sp-xrow').dataset.i, 1); renderExtras(); touch(); calc(); });

	/* ---------- محاسبه و نتیجه ---------- */
	var resultVisible = false;
	function updateResultHead() {
		var n = R.yq || 1;
		$('r-name').textContent = (R.name || (R.from ? '' : 'دستور خودم')) + '، ' + faNum(n, 2) + ' ' + R.yu;
		[['r-plate', 'r-img'], ['sp-hero-plate', 'sp-hero-img']].forEach(function (p) { $(p[0]).hidden = !R.img; if (R.img) $(p[1]).src = R.img; });
	}
	function calc() {
		var n = num(R.yq) || 1, per = function (v, p) { return num(v) * (p === 'unit' ? n : 1); };
		var mat = 0, missing = 0;
		R.items.forEach(function (it) { if (it.q === '' || !num(it.q)) return; var c = rowCost(it); if (!c.ok) missing++; mat += c.v; });
		var wage = num(R.wage) * num(R.hours);
		var oven = num(R.oven) * num(R.ovenh);
		var other = oven + per(R.pack, R.packPer) + R.extras.reduce(function (s, x) { return s + per(x.a, x.per); }, 0);
		var margin = Math.min(num(R.margin), 90);
		var cost = mat + wage + other, unitCost = cost / n;
		var raw = unitCost / (1 - margin / 100), price = cost > 0 ? roundTo(raw, priceStep(raw)) : 0;
		var uu = R.yu, one = n === 1;

		updateResultHead();
		$('r-mat').textContent = mat ? toman(mat) : '—';
		$('r-wage').textContent = wage ? toman(wage) : '—';
		$('r-other').textContent = other ? toman(other) : '—';
		$('r-cost').textContent = cost ? toman(cost) : '—';
		$('r-cu-row').hidden = one;
		$('r-cu-label').textContent = 'تمام‌شده‌ی هر ' + uu;
		$('r-cost-unit').textContent = cost ? toman(unitCost) : '—';
		$('r-price-label').textContent = 'قیمت پیشنهادی ' + (one && uu === 'کیلو' ? '(کیلویی)' : 'هر ' + uu);
		$('r-price').textContent = price ? toman(price) : '—';
		$('r-perkg').textContent = price && !one ? 'کل ' + faNum(n, 2) + ' ' + uu + ': ' + toman(price * n) : '';
		$('r-current-label').textContent = 'الان هر ' + uu + ' رو چند می‌فروشی؟ (اختیاری)';
		var mini = $('r-mini'); if (mini) { mini.textContent = price ? toman(price) : '—'; $('sp-minibar').hidden = !price || resultVisible; }

		var split = $('r-split'), profitAmt = Math.max(price - unitCost, 0);
		split.hidden = !(price > 0 && !missing);
		if (!split.hidden) {
			var parts = [mat / n, wage / n, other / n, profitAmt], colors = ['#F2A33A', '#7FC8C4', '#B9A3D6', '#5FD08F'], ids = ['s-mat', 's-wage', 's-other', 's-profit'], acc = 0, stops = [];
			parts.forEach(function (p, i) { var pct = p / price * 100; stops.push(colors[i] + ' ' + acc + '% ' + (acc + pct) + '%'); acc += pct; $(ids[i]).textContent = faNum(Math.round(pct)) + '٪'; });
			$('r-donut').style.background = 'conic-gradient(' + stops.join(',') + ')';
			$('r-donut-c').textContent = faNum(Math.round(profitAmt / price * 100)) + '٪ سود';
		}

		var qBox = $('r-quote-box'); qBox.hidden = !(price > 0 && !missing);
		if (!qBox.hidden) {
			var nm = R.name || 'سفارش شما';
			$('r-quote').value = 'سلام 🌸\n' + nm + (one && uu === 'کیلو' ? ' کیلویی: ' : '، هر ' + uu + ': ') + toman(price) +
				'\nبرای ثبت سفارش، ' + (R.kitchen === 'food' ? 'تعداد و روز تحویل' : 'تاریخ تحویل') + ' رو بفرستید.';
		}

		var v = $('r-verdict'), current = num(R.current), what = 'هر ' + uu;
		if (!R.items.length) verdict(v, 'info', 'thinking', 'مواد دستورت رو اضافه کن تا حساب کنم.');
		else if (missing) verdict(v, 'info', 'thinking', 'قیمت ' + faNum(missing) + ' تا از مواد رو هنوز ننوشتی. تا ننویسی، حساب کامل نیست.');
		else if (current > 0 && unitCost > 0) {
			var profit = current - unitCost, pct = Math.round(profit / current * 100);
			if (profit < 0) verdict(v, 'bad', 'sad', '▼ با این قیمت، سر ' + what + ' ' + toman(-profit) + ' ضرر می‌کنی' + (!one ? '. یعنی سر کل این ' + faNum(n) + ' ' + uu + ' ' + toman(-profit * n) + '.' : '.'));
			else if (pct < margin) verdict(v, 'warn', 'worried', '! سودت از ' + what + ' ' + toman(profit) + 'ه، یعنی ' + faNum(pct) + '٪. از ' + faNum(margin) + '٪ که می‌خواستی کمتره.');
			else verdict(v, 'good', 'celebrating', '▲ از ' + what + ' ' + toman(profit) + ' سود می‌بری، یعنی ' + faNum(pct) + '٪. دمت گرم!');
		} else v.hidden = true;
	}
	function verdict(v, kind, pose, text) {
		v.hidden = false; v.className = 'sp-verdict sp-verdict--' + kind; v.textContent = '';
		if (D.soodi) { var img = document.createElement('img'); img.className = 'sp-verdict__soodi'; img.src = D.soodi + pose + '.webp'; img.alt = ''; img.width = 56; v.appendChild(img); }
		var span = document.createElement('span'); span.textContent = text; v.appendChild(span);
	}

	function renderAll() { renderPicker(); renderHead(); renderBasics(); renderItems(); renderExtras(); calc(); }

	/* ---------- کپی متن مشتری ---------- */
	var copyBtn = $('r-copy');
	copyBtn.addEventListener('click', function () {
		var ta = $('r-quote'), label = copyBtn.querySelector('span');
		var done = function () { label.textContent = 'کپی شد ✓'; setTimeout(function () { label.textContent = 'کپی برای دایرکت'; }, 2000); };
		if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(ta.value).then(done, function () { ta.select(); document.execCommand('copy'); done(); });
		else { ta.select(); document.execCommand('copy'); done(); }
	});
	if ('IntersectionObserver' in window) new IntersectionObserver(function (en) { resultVisible = en[0].isIntersecting; if (R) calc(); }, { threshold: 0.25 }).observe($('sp-result'));

	/* ---------- شروع ---------- */
	document.addEventListener('sp:kitchen', function () { open(store.last[kitchen()] || 'lib:'); });
	var qs = new URLSearchParams(location.search), want = qs.get('dish') || qs.get('cake');
	var pend = ME.in ? lsGet(PEND) : null;
	if (pend && pend.r && Date.now() - (pend.t || 0) < 7 * 864e5) {
		lsDel(PEND);
		mergeIn(pend, ['ings', 'prices']);
		var pr = pend.r; pr.id = uid('r'); pr.updated = Date.now(); if (!D.recipes[pr.from]) pr.from = null;
		store.recipes[pr.id] = pr; root.setAttribute('data-k', pr.kitchen === 'food' ? 'food' : 'sweet');
		persist(); open('my:' + pr.id, pr.kitchen); status('دستورت توی حسابت ذخیره شد ✓');
	} else if (want && D.recipes[want]) { root.setAttribute('data-k', D.recipes[want].kitchen); open('lib:' + want, D.recipes[want].kitchen); }
	else if (qs.get('new')) open('new');
	else open(store.last[kitchen()] || 'lib:');
	if (migrated) persist();
	if (ME.in && qs.get('welcome')) status('خوش اومدی! حالا دستورهات ذخیره می‌شه ✓');
})();
