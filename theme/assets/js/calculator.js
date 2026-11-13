/* ماشین‌حساب رایگان سودپز (شیرینی و غذای خانگی). داده‌ها از SP_DATA (inc/prices.php) می‌آیند. */
(function () {
	'use strict';
	var D = window.SP_DATA;
	var form = document.getElementById('sp-calc');
	if (!D || !form) return;

	var $ = function (id) { return document.getElementById(id); };
	var FA = '۰۱۲۳۴۵۶۷۸۹';
	var root = document.documentElement;

	function toEn(s) {
		return String(s == null ? '' : s)
			.replace(/[۰-۹]/g, function (d) { return FA.indexOf(d); })
			.replace(/[٠-٩]/g, function (d) { return '٠١٢٣٤٥٦٧٨٩'.indexOf(d); })
			.replace(/[٫]/g, '.')
			.replace(/[^0-9.]/g, '');
	}
	function num(v) { var n = parseFloat(toEn(v)); return isFinite(n) ? n : 0; }
	function faNum(n, digits) {
		return Number(n).toLocaleString('fa-IR', { maximumFractionDigits: digits == null ? 0 : digits });
	}
	function toman(n) { return faNum(Math.round(n)) + ' تومان'; }
	function roundTo(n, step) { return Math.round(n / step) * step; }

	// قیمت‌هایی که کاربر توی جدول عوض کرده (فقط در همین صفحه، مشترک بین دو آشپزخونه)
	var userPrices = {};
	var touched = {};

	function kitchen() { return root.getAttribute('data-k') === 'food' ? 'food' : 'sweet'; }
	function isFood() { return kitchen() === 'food'; }
	function baseQty() { return isFood() ? 10 : 1; } // دستورها: کیک برای ۱ کیلو، غذا برای ۱۰ پرس

	function setIfEmpty(id, val, decimals) {
		var el = $(id);
		if (!touched[id]) el.value = val ? faNum(val, decimals) : '';
	}

	function recipeKey() {
		var k = kitchen();
		var r = form.querySelector('input[name="sp-recipe-' + k + '"]:checked');
		if (r) return r.value;
		var keys = Object.keys(D.recipes).filter(function (x) { return D.recipes[x].kitchen === k; });
		return keys[0];
	}
	function recipe() { return D.recipes[recipeKey()]; }

	function amount() { var w = num($('sp-weight').value); return w > 0 ? w : baseQty(); }
	function scale() { return amount() / baseQty(); }

	function showPhotos() {
		var k = recipeKey();
		Array.prototype.forEach.call(document.querySelectorAll('[data-cake-photo]'), function (el) { el.hidden = el.getAttribute('data-cake-photo') !== k; });
		$('r-name').textContent = recipe().name + '، ' + faNum(amount(), 2) + (isFood() ? ' پرس' : ' کیلو');
	}

	function fillDefaults() {
		var r = recipe(), s = scale(), food = isFood();
		// ساعت کار با مقدار کامل خطی بالا نمی‌رود؛ نصفش ثابت است (آماده‌سازی، تزئین، شستن).
		setIfEmpty('sp-hours', Math.round(r.hours * (0.5 + 0.5 * s) * 4) / 4, 2);
		setIfEmpty('sp-ovenh', Math.round(r.oven * (0.6 + 0.4 * s) * 4) / 4, 2);
		setIfEmpty('sp-wage', D.defaults.wage);
		setIfEmpty('sp-oven', food ? D.defaults.stove : D.defaults.oven);
		setIfEmpty('sp-pack', food ? D.defaults.container : D.defaults.packaging);
		setIfEmpty('sp-margin', D.defaults.margin || 30);
	}

	function priceOf(key) {
		return Object.prototype.hasOwnProperty.call(userPrices, key) ? userPrices[key] : (D.prices[key] || 0);
	}
	function qtyOf(key) {
		var q = recipe().items[key] * scale();
		return D.ingredients[key].unit === 'عدد' ? Math.ceil(q) : q;
	}
	function qtyText(key) {
		var ing = D.ingredients[key], q = qtyOf(key);
		if (ing.unit === 'عدد') return faNum(q) + ' عدد';
		if (ing.unit === 'گرم') return faNum(q, 1) + ' گرم';
		if (q < 1) return faNum(q * 1000) + (ing.unit === 'لیتر' ? ' میلی‌لیتر' : ' گرم');
		return faNum(q, 2) + ' ' + ing.unit;
	}

	function buildTable() {
		var r = recipe();
		var body = $('sp-ing-table').tBodies[0];
		body.innerHTML = '';
		Object.keys(r.items).forEach(function (key) {
			var ing = D.ingredients[key];
			var tr = document.createElement('tr');
			var p = priceOf(key);
			tr.innerHTML =
				'<td></td><td></td>' +
				'<td><input type="text" inputmode="numeric" aria-label=""></td>' +
				'<td class="sp-table__cost"></td>';
			tr.cells[0].textContent = ing.name;
			tr.cells[1].textContent = qtyText(key);
			tr.cells[3].setAttribute('data-label', 'هزینه');
			var input = tr.cells[2].firstChild;
			input.setAttribute('aria-label', 'قیمت هر ' + ing.unit + ' ' + ing.name);
			input.placeholder = 'هر ' + ing.unit;
			input.value = p ? faNum(p) : '';
			input.dataset.key = key;
			tr.dataset.key = key;
			body.appendChild(tr);
		});
	}

	var resultVisible = false;

	function calc() {
		var r = recipe(), food = isFood(), n = amount();
		var mat = 0, missing = 0;
		Array.prototype.forEach.call($('sp-ing-table').tBodies[0].rows, function (tr) {
			var key = tr.dataset.key;
			var p = priceOf(key);
			var c = qtyOf(key) * p;
			if (!p) missing++;
			tr.cells[3].textContent = p ? faNum(Math.round(c)) : '—';
			mat += c;
		});

		var wage = num($('sp-wage').value) * num($('sp-hours').value);
		var pack = num($('sp-pack').value) * (food ? n : 1); // غذا: ظرف هر پرس
		var other = num($('sp-oven').value) * num($('sp-ovenh').value) + pack;
		var marginPct = Math.min(num($('sp-margin').value), 90);
		var cost = mat + wage + other;
		// غذا: قیمت برای هر پرس، گرد به هزار تومان. کیک: قیمت کل کیک، گرد به ۵ هزار تومان.
		var div = food ? n : 1;
		var unitCost = cost / div;
		var price = cost > 0 ? roundTo(unitCost / (1 - marginPct / 100), food ? 1000 : 5000) : 0;

		$('r-mat').textContent = mat ? toman(mat) : '—';
		$('r-wage').textContent = wage ? toman(wage) : '—';
		$('r-other').textContent = other ? toman(other) : '—';
		$('r-cost').textContent = cost ? toman(cost) : '—';
		if ($('r-cost-unit')) $('r-cost-unit').textContent = food && cost ? toman(unitCost) : '—';
		$('r-price').textContent = price ? toman(price) : '—';
		var mini = $('r-mini');
		if (mini) { mini.textContent = price ? toman(price) : '—'; $('sp-minibar').hidden = !price || resultVisible; }
		if (food) {
			$('r-perkg').textContent = price ? 'کل ' + faNum(n) + ' پرس: ' + toman(price * n) : '';
		} else {
			$('r-perkg').textContent = price && n !== 1 ? 'کیلویی ' + toman(roundTo(price / n, 5000)) : '';
		}

		// نمودار دایره‌ای: سهم هر بخش از قیمت فروش (برای غذا: هر پرس)
		var split = $('r-split');
		if (split) {
			var profitAmt = Math.max(price - unitCost, 0);
			split.hidden = !(price > 0 && !missing);
			if (!split.hidden) {
				var parts = [mat / div, wage / div, other / div, profitAmt];
				var colors = ['#F2A33A', '#7FC8C4', '#B9A3D6', '#5FD08F'];
				var ids = ['s-mat', 's-wage', 's-other', 's-profit'];
				var acc = 0, stops = [];
				parts.forEach(function (p, i) {
					var pct = p / price * 100;
					stops.push(colors[i] + ' ' + acc + '% ' + (acc + pct) + '%');
					acc += pct;
					$(ids[i]).textContent = faNum(Math.round(pct)) + '٪';
				});
				$('r-donut').style.background = 'conic-gradient(' + stops.join(',') + ')';
				$('r-donut-c').textContent = faNum(Math.round(profitAmt / price * 100)) + '٪ سود';
			}
		}

		// متن آماده برای مشتری (هزینه و سود داخلش نیست)
		var qBox = $('r-quote-box');
		if (qBox) {
			qBox.hidden = !(price > 0 && !missing);
			if (!qBox.hidden) {
				$('r-quote').value = food
					? 'سلام 🌸\n' + r.name + '، هر پرس: ' + toman(price) + '\nبرای ثبت سفارش، تعداد پرس و روز تحویل رو بفرستید.'
					: 'سلام 🌸\n' + r.name + ' ' + faNum(n, 2) + ' کیلویی: ' + toman(price) + '\nبرای ثبت سفارش، تاریخ تحویل رو بفرستید.';
			}
		}

		var v = $('r-verdict');
		var current = num($('sp-current').value);
		var what = food ? 'هر پرس' : 'هر کیک';
		if (missing) {
			verdict(v, 'info', 'thinking', 'قیمت ' + faNum(missing) + ' ماده رو هنوز ننوشتی؛ تا ننویسی، عددها کامل نیست.');
		} else if (current > 0 && unitCost > 0) {
			var profit = current - unitCost;
			var pct = Math.round(profit / current * 100);
			if (profit < 0) {
				verdict(v, 'bad', 'sad', '▼ با این قیمت، از ' + what + ' ' + toman(-profit) + ' ضرر می‌کنی' + (food ? '؛ یعنی کل این دیگ ' + toman(-profit * n) + '.' : '.'));
			} else if (pct < marginPct) {
				verdict(v, 'warn', 'worried', '! سودت از ' + what + ' ' + toman(profit) + ' است، یعنی ' + faNum(pct) + '٪؛ کمتر از ' + faNum(marginPct) + '٪ که می‌خواستی.');
			} else {
				verdict(v, 'good', 'celebrating', '▲ از ' + what + ' ' + toman(profit) + ' سود می‌بری، یعنی ' + faNum(pct) + '٪. آفرین.');
			}
		} else {
			v.hidden = true;
		}
	}

	/* پیام نتیجه، با حالت مناسب سودی کنارش. */
	function verdict(v, kind, pose, text) {
		v.hidden = false;
		v.className = 'sp-verdict sp-verdict--' + kind;
		v.textContent = '';
		if (D.soodi) {
			var img = document.createElement('img');
			img.className = 'sp-verdict__soodi';
			img.src = D.soodi + pose + '.webp';
			img.alt = '';
			img.width = 56;
			v.appendChild(img);
		}
		var span = document.createElement('span');
		span.textContent = text;
		v.appendChild(span);
	}

	function refresh() { fillDefaults(); buildTable(); showPhotos(); calc(); }

	// عوض شدن آشپزخونه: مقدار، ساعت‌ها و هزینه‌های مخصوص هر آشپزخونه از نو.
	function onKitchen() {
		touched['sp-hours'] = touched['sp-ovenh'] = touched['sp-oven'] = touched['sp-pack'] = false;
		$('sp-weight').value = faNum(baseQty());
		$('sp-current').value = '';
		refresh();
	}
	document.addEventListener('sp:kitchen', onKitchen);

	form.addEventListener('change', function (e) {
		if (!e.target.name || e.target.name.indexOf('sp-recipe') !== 0) return;
		touched['sp-hours'] = touched['sp-ovenh'] = false;
		refresh();
	});
	$('sp-weight').addEventListener('input', refresh);
	form.addEventListener('input', function (e) {
		var t = e.target;
		if (t.name && t.name.indexOf('sp-recipe') === 0) return;
		if (t.dataset && t.dataset.key) {
			userPrices[t.dataset.key] = num(t.value);
		} else if (t.id && t.id !== 'sp-weight') {
			touched[t.id] = true;
		}
		calc();
	});

	var copyBtn = $('r-copy');
	if (copyBtn) {
		copyBtn.addEventListener('click', function () {
			var ta = $('r-quote'), label = copyBtn.querySelector('span');
			var done = function () { label.textContent = 'کپی شد ✓'; setTimeout(function () { label.textContent = 'کپی برای دایرکت'; }, 2000); };
			if (navigator.clipboard && window.isSecureContext) {
				navigator.clipboard.writeText(ta.value).then(done, function () { ta.select(); document.execCommand('copy'); done(); });
			} else { ta.select(); document.execCommand('copy'); done(); }
		});
	}

	// وقتی خود کارت نتیجه دیده می‌شود، نوار پایین لازم نیست.
	if ('IntersectionObserver' in window && $('sp-result')) {
		new IntersectionObserver(function (en) { resultVisible = en[0].isIntersecting; calc(); }, { threshold: 0.25 }).observe($('sp-result'));
	}

	$('sp-weight').value = faNum(baseQty());
	refresh();
})();
