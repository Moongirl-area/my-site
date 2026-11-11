/* ماشین‌حساب رایگان سودپز. داده‌ها از SP_DATA (functions.php) می‌آیند. */
(function () {
	'use strict';
	var D = window.SP_DATA;
	var form = document.getElementById('sp-calc');
	if (!D || !form) return;

	var $ = function (id) { return document.getElementById(id); };
	var FA = '۰۱۲۳۴۵۶۷۸۹';

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
	function round5k(n) { return Math.round(n / 5000) * 5000; }

	// قیمت‌هایی که کاربر توی جدول عوض کرده (فقط در همین صفحه)
	var userPrices = {};
	var touched = {};

	function setIfEmpty(id, val, decimals) {
		var el = $(id);
		if (!touched[id]) el.value = val ? faNum(val, decimals) : '';
	}

	function recipe() { return D.recipes[$('sp-recipe').value]; }
	function weight() { var w = num($('sp-weight').value); return w > 0 ? w : 1; }

	function fillDefaults() {
		var r = recipe(), w = weight();
		// ساعت کار با وزن کامل خطی بالا نمی‌رود؛ نصفش ثابت است (آماده‌سازی و تزئین).
		setIfEmpty('sp-hours', Math.round(r.hours * (0.5 + 0.5 * w) * 4) / 4, 2);
		setIfEmpty('sp-ovenh', Math.round(r.oven * (0.6 + 0.4 * w) * 4) / 4, 2);
		setIfEmpty('sp-wage', D.defaults.wage);
		setIfEmpty('sp-oven', D.defaults.oven);
		setIfEmpty('sp-pack', D.defaults.packaging);
		setIfEmpty('sp-margin', D.defaults.margin || 30);
	}

	function priceOf(key) {
		return Object.prototype.hasOwnProperty.call(userPrices, key) ? userPrices[key] : (D.prices[key] || 0);
	}

	function buildTable() {
		var r = recipe(), w = weight();
		var body = $('sp-ing-table').tBodies[0];
		body.innerHTML = '';
		Object.keys(r.items).forEach(function (key) {
			var ing = D.ingredients[key];
			var qty = r.items[key] * w;
			var tr = document.createElement('tr');
			var p = priceOf(key);
			var qtyText = ing.unit === 'عدد' ? faNum(Math.ceil(qty)) + ' عدد'
				: (qty < 1 ? faNum(qty * 1000) + (ing.unit === 'لیتر' ? ' میلی‌لیتر' : ' گرم') : faNum(qty, 2) + ' ' + ing.unit);
			tr.innerHTML =
				'<td></td><td></td>' +
				'<td><input type="text" inputmode="numeric" aria-label=""></td>' +
				'<td class="sp-table__cost"></td>';
			tr.cells[0].textContent = ing.name;
			tr.cells[1].textContent = qtyText;
			var input = tr.cells[2].firstChild;
			input.setAttribute('aria-label', 'قیمت هر ' + ing.unit + ' ' + ing.name);
			input.placeholder = 'هر ' + ing.unit;
			input.value = p ? faNum(p) : '';
			input.dataset.key = key;
			tr.dataset.key = key;
			body.appendChild(tr);
		});
	}

	function calc() {
		var r = recipe(), w = weight();
		var mat = 0, missing = 0;
		Array.prototype.forEach.call($('sp-ing-table').tBodies[0].rows, function (tr) {
			var key = tr.dataset.key;
			var qty = D.ingredients[key].unit === 'عدد' ? Math.ceil(r.items[key] * w) : r.items[key] * w;
			var p = priceOf(key);
			var c = qty * p;
			if (!p) missing++;
			tr.cells[3].textContent = p ? faNum(Math.round(c)) : '—';
			mat += c;
		});

		var wage = num($('sp-wage').value) * num($('sp-hours').value);
		var other = num($('sp-oven').value) * num($('sp-ovenh').value) + num($('sp-pack').value);
		var marginPct = Math.min(num($('sp-margin').value), 90);
		var cost = mat + wage + other;
		var price = cost > 0 ? round5k(cost / (1 - marginPct / 100)) : 0;

		$('r-mat').textContent = mat ? toman(mat) : '—';
		$('r-wage').textContent = wage ? toman(wage) : '—';
		$('r-other').textContent = other ? toman(other) : '—';
		$('r-cost').textContent = cost ? toman(cost) : '—';
		$('r-price').textContent = price ? toman(price) : '—';
		$('r-perkg').textContent = price && w !== 1 ? 'کیلویی ' + toman(round5k(price / w)) : '';

		var v = $('r-verdict');
		var current = num($('sp-current').value);
		if (missing) {
			v.hidden = false;
			v.className = 'sp-verdict sp-verdict--info';
			v.textContent = 'قیمت ' + faNum(missing) + ' ماده رو هنوز ننوشتی؛ تا ننویسی، عددها کامل نیست.';
		} else if (current > 0 && cost > 0) {
			var profit = current - cost;
			var pct = Math.round(profit / current * 100);
			v.hidden = false;
			if (profit < 0) {
				v.className = 'sp-verdict sp-verdict--bad';
				v.textContent = '▼ با این قیمت، از هر کیک ' + toman(-profit) + ' ضرر می‌کنی.';
			} else if (pct < marginPct) {
				v.className = 'sp-verdict sp-verdict--warn';
				v.textContent = '! سودت ' + toman(profit) + ' است، یعنی ' + faNum(pct) + '٪؛ کمتر از ' + faNum(marginPct) + '٪ که می‌خواستی.';
			} else {
				v.className = 'sp-verdict sp-verdict--good';
				v.textContent = '▲ از هر کیک ' + toman(profit) + ' سود می‌بری، یعنی ' + faNum(pct) + '٪. آفرین.';
			}
		} else {
			v.hidden = true;
		}
	}

	$('sp-recipe').addEventListener('change', function () {
		touched['sp-hours'] = touched['sp-ovenh'] = false;
		fillDefaults(); buildTable(); calc();
	});
	$('sp-weight').addEventListener('input', function () {
		fillDefaults(); buildTable(); calc();
	});
	form.addEventListener('input', function (e) {
		var t = e.target;
		if (t.dataset && t.dataset.key) {
			userPrices[t.dataset.key] = num(t.value);
		} else if (t.id && t.id !== 'sp-weight') {
			touched[t.id] = true;
		}
		calc();
	});

	fillDefaults(); buildTable(); calc();
})();
