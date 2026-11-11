<?php
/* صفحه‌ی ماشین‌حساب رایگان (نامک: calculator) */
get_header();
$sp_s     = sp_settings();
$sp_ready = sp_prices_ready();
?>

<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<h1 class="sp-h1">ماشین‌حساب قیمت کیک خانگی ۱۴۰۵</h1>
		<p class="sp-sub">یه کیک انتخاب کن، وزن و دستمزدت رو بنویس، ببین واقعاً چند تموم می‌شه و چند باید بفروشی.</p>

		<?php if ( $sp_ready && $sp_s['prices_date'] ) : ?>
			<p class="sp-note">قیمت مواد: <?php echo esc_html( $sp_s['prices_date'] ); ?>. همیشه می‌تونی قیمت خودت رو توی جدول بذاری.</p>
		<?php elseif ( ! $sp_ready ) : ?>
			<p class="sp-note sp-note--warn">قیمت مرجع مواد این هفته هنوز وارد نشده. فعلاً قیمتی که خودت خریدی رو توی جدول مواد بنویس.</p>
		<?php endif; ?>

		<form class="sp-calc" id="sp-calc" onsubmit="return false" novalidate>
			<div class="sp-calc__inputs">
				<div class="sp-field">
					<label for="sp-recipe">کدوم کیک؟</label>
					<select id="sp-recipe">
						<?php foreach ( sp_recipes() as $key => $r ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $r['name'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="sp-field-row">
					<div class="sp-field">
						<label for="sp-weight">وزن کیک (کیلو)</label>
						<input id="sp-weight" type="text" inputmode="decimal" value="۱">
					</div>
					<div class="sp-field">
						<label for="sp-hours">ساعت کار خودت</label>
						<input id="sp-hours" type="text" inputmode="decimal">
					</div>
				</div>
				<div class="sp-field">
					<label for="sp-wage">دستمزد هر ساعتت (تومان)</label>
					<input id="sp-wage" type="text" inputmode="numeric" placeholder="مثلاً ۱۰۰٬۰۰۰">
					<small>دستمزد خودت هم جزو هزینه‌ست. اگه حسابش نکنی، مجانی کار کردی.</small>
				</div>

				<details class="sp-more">
					<summary>فر، بسته‌بندی و درصد سود</summary>
					<div class="sp-field-row">
						<div class="sp-field">
							<label for="sp-oven">گاز و برق هر ساعت فر (تومان)</label>
							<input id="sp-oven" type="text" inputmode="numeric">
						</div>
						<div class="sp-field">
							<label for="sp-ovenh">ساعت روشن بودن فر</label>
							<input id="sp-ovenh" type="text" inputmode="decimal">
						</div>
					</div>
					<div class="sp-field-row">
						<div class="sp-field">
							<label for="sp-pack">جعبه و بسته‌بندی (تومان)</label>
							<input id="sp-pack" type="text" inputmode="numeric">
						</div>
						<div class="sp-field">
							<label for="sp-margin">سود هدف (٪ از قیمت فروش)</label>
							<input id="sp-margin" type="text" inputmode="decimal">
						</div>
					</div>
				</details>

				<div class="sp-ing">
					<div class="sp-ing__head">
						<h2>مواد این کیک</h2>
						<small>قیمت هر واحد رو می‌تونی عوض کنی.</small>
					</div>
					<div class="sp-table-wrap">
						<table class="sp-table" id="sp-ing-table">
							<thead><tr><th>ماده</th><th>مقدار</th><th>قیمت واحد (تومان)</th><th>هزینه</th></tr></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>

			<aside class="sp-calc__result" aria-live="polite">
				<div class="sp-result">
					<div class="sp-result__row"><span>هزینه‌ی مواد</span><b id="r-mat">—</b></div>
					<div class="sp-result__row"><span>دستمزد</span><b id="r-wage">—</b></div>
					<div class="sp-result__row"><span>فر و بسته‌بندی</span><b id="r-other">—</b></div>
					<div class="sp-result__row sp-result__row--total"><span>قیمت تمام‌شده</span><b id="r-cost">—</b></div>
					<div class="sp-result__big">
						<span>قیمت پیشنهادی</span>
						<b id="r-price">—</b>
						<small id="r-perkg"></small>
					</div>
					<div class="sp-field">
						<label for="sp-current">الان این کیک رو چند می‌فروشی؟ (اختیاری)</label>
						<input id="sp-current" type="text" inputmode="numeric" placeholder="مثلاً ۷۵۰٬۰۰۰">
					</div>
					<div id="r-verdict" class="sp-verdict" hidden></div>
					<p class="sp-result__cta">این قیمت با قیمت مواد امروز حساب شده. هفته‌ی بعد که کره گرون‌تر شد، دوباره باید حساب کنی؛ سودپز پرو این کار رو خودش انجام می‌ده.</p>
					<a class="sp-btn sp-btn--primary sp-btn--block" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار شو</a>
				</div>
			</aside>
		</form>
	</div>
</section>

<section class="sp-section sp-section--tint">
	<div class="sp-wrap sp-prose">
		<h2>قیمت کیک خانگی رو چطور حساب کنیم؟</h2>
		<p>قیمت تمام‌شده‌ی یه کیک فقط پول مواد نیست. خیلی از قنادهای خانگی فقط مواد رو حساب می‌کنن و دستمزد خودشون، گاز فر و جعبه یادشون می‌ره؛ برای همین فکر می‌کنن سود می‌کنن، در حالی که ممکنه مجانی کار کرده باشن.</p>
		<p>فرمولی که این ماشین‌حساب استفاده می‌کنه:</p>
		<p class="sp-formula">قیمت تمام‌شده = مواد + (دستمزد ساعتی × ساعت کار) + (هزینه‌ی فر × ساعت فر) + بسته‌بندی</p>
		<p class="sp-formula">قیمت پیشنهادی = قیمت تمام‌شده ÷ (۱ − درصد سود)، گرد شده به نزدیک‌ترین ۵ هزار تومان</p>

		<h3>۳۰٪ سود با ۳۰٪ اضافه فرق داره</h3>
		<p>اگه روی هزینه‌ت ۳۰٪ اضافه بکشی، سود واقعیت از قیمت فروش حدود ۲۳٪ می‌شه، نه ۳۰٪. مثلاً کیکی که ۷۰۰ هزار تومان برات تموم می‌شه، با ۳۰٪ اضافه می‌شه ۹۱۰ هزار و سودت ۲۱۰ هزار، یعنی ۲۳٪ قیمت فروش. برای اینکه واقعاً ۳۰٪ سود ببری، باید ۷۰۰ رو تقسیم بر ۰٫۷ کنی: ۱ میلیون تومان.</p>

		<h3>دستمزد خودم رو چقدر بذارم؟</h3>
		<p>یه عدد منطقی، مبلغیه که اگه کس دیگه‌ای جای تو کار می‌کرد، باید بهش می‌دادی. زمان خرید مواد، شستن ظرف و جواب دادن به مشتری هم جزو ساعت کارته.</p>

		<h3>هر چند وقت یه بار قیمت‌هام رو چک کنم؟</h3>
		<p>با تورم این روزها، هر بار که یکی از مواد اصلی (کره، خامه، شکلات، تخم‌مرغ) گرون شد. اگه دو ماه پیش قیمت بستی، احتمالاً الان سودت کمتر از چیزیه که فکر می‌کنی.</p>

		<h2>سؤال‌های رایج</h2>
		<details class="sp-faq"><summary>باید حسابداری بلد باشم؟</summary><p>نه؛ فقط مواد کیکت رو می‌دونی، بقیه رو سودپز حساب می‌کنه.</p></details>
		<details class="sp-faq"><summary>قیمت مواد از کجا میاد؟</summary><p>از گزارش‌های رسمی و قیمت فروشگاه‌ها، هر هفته؛ همیشه می‌تونی قیمت خودت رو بذاری.</p></details>
		<details class="sp-faq"><summary>کسی دستور کیک من رو می‌بینه؟</summary><p>نه؛ این ماشین‌حساب چیزی ذخیره نمی‌کنه، و توی سودپز پرو هم دستورها و قیمت‌هات فقط مال خودته.</p></details>
		<details class="sp-faq"><summary>موقع قطعی اینترنت کار می‌کنه؟</summary><p>بله، سرور سودپز داخل ایرانه.</p></details>
	</div>
</section>

<?php get_footer(); ?>
