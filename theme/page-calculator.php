<?php
/* صفحه‌ی ماشین‌حساب رایگان (نامک: calculator): شیرینی و غذای خانگی */
get_header();
$sp_s    = sp_settings();
$sp_all  = sp_recipes();
$sp_dish = '';
foreach ( array( 'dish', 'cake' ) as $sp_param ) {
	if ( isset( $_GET[ $sp_param ] ) ) {
		$sp_try = sanitize_key( wp_unslash( $_GET[ $sp_param ] ) );
		if ( isset( $sp_all[ $sp_try ] ) ) {
			$sp_dish = $sp_try;
		}
	}
}
// دستور پیش‌فرض هر آشپزخونه؛ اگر در آدرس دستوری آمده، همان.
$sp_sel = array(
	'sweet' => array_key_first( sp_recipes_of( 'sweet' ) ),
	'food'  => array_key_first( sp_recipes_of( 'food' ) ),
);
if ( $sp_dish ) {
	$sp_sel[ $sp_all[ $sp_dish ]['kitchen'] ] = $sp_dish;
}
?>

<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<div class="sp-calc-hero">
			<div>
				<p class="sp-script">ماشین‌حساب رایگان</p>
				<h1 class="sp-h1">ماشین‌حساب قیمت کیک و غذای خانگی ۱۴۰۵</h1>
				<p class="sp-sub" data-only="sweet">کیکت رو انتخاب کن، وزن و دستمزدت رو بنویس. دو دقیقه بعد می‌دونی واقعاً چند برات تموم می‌شه و چند بفروشی که سود کنی.</p>
				<p class="sp-sub" data-only="food">غذات رو انتخاب کن، تعداد پرس و دستمزدت رو بنویس. دو دقیقه بعد می‌دونی هر پرس واقعاً چند برات تموم می‌شه و چند بفروشی که سود کنی.</p>
				<?php foreach ( sp_kitchens() as $sp_k => $sp_kit ) : ?>
					<?php if ( sp_prices_ready( $sp_k ) && $sp_s['prices_date'] ) : ?>
						<p class="sp-note" data-only="<?php echo esc_attr( $sp_k ); ?>"><?php echo sp_icon( 'tag', 20 ); ?> قیمت مواد: <?php echo esc_html( $sp_s['prices_date'] ); ?>. اگه ارزون‌تر یا گرون‌تر خریدی، قیمت خودت رو توی جدول بنویس.</p>
					<?php elseif ( ! sp_prices_ready( $sp_k ) ) : ?>
						<p class="sp-note sp-note--warn" data-only="<?php echo esc_attr( $sp_k ); ?>"><?php echo sp_icon( 'bell', 20 ); ?> قیمت مرجع مواد <?php echo esc_html( $sp_kit['short'] ); ?> این هفته هنوز نیومده. فعلاً قیمتی که خودت خریدی رو توی جدول مواد بنویس.</p>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $sp_all as $key => $r ) : ?>
				<div class="sp-plate" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" data-cake-photo="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_sel[ $r['kitchen'] ] ? '' : ' hidden'; ?>><?php echo sp_img( $r['img'], $r['name'], 'sp-plate__img' ); ?></div>
			<?php endforeach; ?>
		</div>

<form class="sp-calc-form" id="sp-calc" onsubmit="return false" novalidate>
		<?php sp_kitchen_switch( 'چی می‌پزی؟' ); ?>
		<?php foreach ( sp_kitchens() as $sp_k => $sp_kit ) : ?>
		<fieldset class="sp-cakes" data-only="<?php echo esc_attr( $sp_k ); ?>">
			<legend><?php echo 'food' === $sp_k ? 'کدوم غذا؟' : 'کدوم کیک؟'; ?></legend>
			<?php foreach ( sp_recipes_of( $sp_k ) as $key => $r ) : ?>
				<label class="sp-cake" style="--c:<?php echo esc_attr( $r['color'] ); ?>">
					<input type="radio" name="sp-recipe-<?php echo esc_attr( $sp_k ); ?>" value="<?php echo esc_attr( $key ); ?>"<?php checked( $key, $sp_sel[ $sp_k ] ); ?>>
					<span class="sp-cake__body">
						<span class="sp-cake__ico"><?php echo sp_icon( $r['icon'], 20 ); ?></span>
						<?php echo sp_img( $r['img'], $r['name'], 'sp-cake__img' ); ?>
						<?php echo esc_html( $r['name'] ); ?>
					</span>
				</label>
			<?php endforeach; ?>
		</fieldset>
		<?php endforeach; ?>
		<div class="sp-calc">
			<div class="sp-calc__inputs">
				<div class="sp-field-row">
					<div class="sp-field">
						<label for="sp-weight"><span data-only="sweet">وزن کیک (کیلو)</span><span data-only="food">تعداد پرس این دیگ</span></label>
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
					<small>وقت تو هم هزینه‌ست. اگه حسابش نکنی، یعنی مجانی کار کردی.</small>
				</div>

				<details class="sp-more">
					<summary><?php echo sp_icon( 'flame', 18 ); ?> <span data-only="sweet">فر، بسته‌بندی و درصد سود</span><span data-only="food">گاز اجاق، ظرف و درصد سود</span></summary>
					<div class="sp-field-row">
						<div class="sp-field">
							<label for="sp-oven"><span data-only="sweet">گاز و برق هر ساعت فر (تومان)</span><span data-only="food">گاز هر ساعت اجاق (تومان)</span></label>
							<input id="sp-oven" type="text" inputmode="numeric">
						</div>
						<div class="sp-field">
							<label for="sp-ovenh"><span data-only="sweet">ساعت روشن بودن فر</span><span data-only="food">ساعت پخت روی گاز</span></label>
							<input id="sp-ovenh" type="text" inputmode="decimal">
						</div>
					</div>
					<div class="sp-field-row">
						<div class="sp-field">
							<label for="sp-pack"><span data-only="sweet">جعبه و بسته‌بندی (تومان)</span><span data-only="food">ظرف و بسته‌بندی هر پرس (تومان)</span></label>
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
						<h2><span data-only="sweet">مواد این کیک</span><span data-only="food">مواد این دیگ</span></h2>
						<small><span data-only="food">مقدارها نمونه‌ان. اگه پرس‌هات بزرگ‌تر یا کوچیک‌ترن، مقدار مواد رو مطابقش عوض کن. </span>قیمت هر ماده رو هم می‌تونی عوض کنی.</small>
					</div>
					<div class="sp-table-wrap">
						<table class="sp-table" id="sp-ing-table">
							<thead><tr><th>ماده</th><th>مقدار</th><th>قیمت واحد (تومان)</th><th>هزینه</th></tr></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>

			<aside class="sp-calc__result" id="sp-result" aria-live="polite">
				<div class="sp-result">
					<div class="sp-result__head">
						<?php foreach ( $sp_all as $key => $r ) : ?>
							<div class="sp-plate" data-cake-photo="<?php echo esc_attr( $key ); ?>" hidden><?php echo sp_img( $r['img'], $r['name'], 'sp-plate__img' ); ?></div>
						<?php endforeach; ?>
						<b id="r-name"></b>
					</div>
					<div class="sp-result__row"><span>هزینه‌ی مواد</span><b id="r-mat">—</b></div>
					<div class="sp-result__row"><span>دستمزد</span><b id="r-wage">—</b></div>
					<div class="sp-result__row"><span><span data-only="sweet">فر و بسته‌بندی</span><span data-only="food">گاز و ظرف</span></span><b id="r-other">—</b></div>
					<div class="sp-result__row sp-result__row--total"><span>قیمت تمام‌شده<span data-only="food"> کل دیگ</span></span><b id="r-cost">—</b></div>
					<div class="sp-result__row" data-only="food"><span>تمام‌شده‌ی هر پرس</span><b id="r-cost-unit">—</b></div>
					<div class="sp-split" id="r-split" hidden>
						<div class="sp-split__donut" id="r-donut" role="img" aria-label="سهم هر بخش از قیمت فروش"><span id="r-donut-c"></span></div>
						<ul class="sp-split__legend">
							<li style="--c:#F2A33A"><i></i>مواد <b id="s-mat"></b></li>
							<li style="--c:#7FC8C4"><i></i>دستمزد <b id="s-wage"></b></li>
							<li style="--c:#B9A3D6"><i></i><span data-only="sweet">فر و بسته‌بندی</span><span data-only="food">گاز و ظرف</span> <b id="s-other"></b></li>
							<li style="--c:#5FD08F"><i></i>سود <b id="s-profit"></b></li>
						</ul>
					</div>
					<div class="sp-result__big">
						<span>قیمت پیشنهادی<span data-only="food"> هر پرس</span></span>
						<b id="r-price">—</b>
						<small id="r-perkg"></small>
					</div>
					<div class="sp-field">
						<label for="sp-current"><span data-only="sweet">الان این کیک رو چند می‌فروشی؟ (اختیاری)</span><span data-only="food">الان هر پرسش رو چند می‌فروشی؟ (اختیاری)</span></label>
						<input id="sp-current" type="text" inputmode="numeric" placeholder="مثلاً ۷۵۰٬۰۰۰">
					</div>
					<div id="r-verdict" class="sp-verdict" hidden></div>
					<div class="sp-quote" id="r-quote-box" hidden>
						<div class="sp-quote__head"><b>متن قیمت برای مشتری</b><small>بدون هزینه‌ها و سودت</small></div>
						<textarea id="r-quote" rows="4" aria-label="متن قیمت برای فرستادن به مشتری"></textarea>
						<button class="sp-btn sp-btn--paper sp-btn--sm" type="button" id="r-copy"><?php echo sp_icon( 'story', 18 ); ?><span>کپی برای دایرکت</span></button>
					</div>
					<p class="sp-result__cta">این قیمت مال امروزه. هفته‌ی بعد که <span data-only="sweet">خامه</span><span data-only="food">گوشت</span> گرون شد، باید از اول حساب کنی. سودپز پرو این کار رو خودش برات می‌کنه.</p>
					<a class="sp-btn sp-btn--primary sp-btn--block" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار شو</a>
				</div>
			</aside>
		</div>
		</form>
		<a class="sp-minibar" href="#sp-result" id="sp-minibar" hidden><span>قیمت پیشنهادی</span><b id="r-mini">—</b><em>جزئیات ↓</em></a>
	</div>
</section>

<section class="sp-section sp-section--tint">
	<div class="sp-wrap sp-prose">
		<h2>قیمت کیک خانگی رو چطور حساب کنیم؟</h2>
		<p>قیمت تمام‌شده‌ی یه کیک فقط پول مواد نیست. خیلی از قنادهای خونگی مواد رو حساب می‌کنن و دستمزد خودشون، گاز فر و جعبه یادشون می‌ره. فکر می‌کنن سود کردن، ولی در واقع مجانی کار کردن.</p>
		<p>این ماشین‌حساب با این دو فرمول کار می‌کنه:</p>
		<p class="sp-formula">قیمت تمام‌شده = مواد + (دستمزد ساعتی × ساعت کار) + (هزینه‌ی فر × ساعت فر) + بسته‌بندی</p>
		<p class="sp-formula">قیمت پیشنهادی = قیمت تمام‌شده ÷ (۱ − درصد سود)، گرد شده به نزدیک‌ترین ۵ هزار تومان</p>

		<h3>چرا «۳۰٪ می‌کشم روش» ۳۰٪ سود نمی‌ده؟</h3>
		<p>کیکی که ۷۰۰ هزار تومان برات تموم می‌شه رو در نظر بگیر. با ۳۰٪ اضافه می‌شه ۹۱۰ هزار و سودت ۲۱۰ هزار. ولی ۲۱۰ هزار فقط ۲۳٪ از ۹۱۰ هزاره، نه ۳۰٪. اگه واقعاً ۳۰٪ سود می‌خوای، ۷۰۰ رو تقسیم بر ۰٫۷ کن: می‌شه ۱ میلیون تومان.</p>

		<h3>دستمزد خودم رو چقدر بذارم؟</h3>
		<p>یه سؤال ساده از خودت بپرس: اگه یکی دیگه جای من این کار رو می‌کرد، ساعتی چقدر بهش می‌دادم؟ همون عدد دستمزد توئه. وقت خرید مواد، شستن ظرف و جواب دادن به دایرکت‌ها هم جزو ساعت کارته.</p>

		<h3>هر چند وقت یه بار قیمت‌هام رو چک کنم؟</h3>
		<p>هر بار که یکی از مواد اصلی (کره، خامه، شکلات، تخم‌مرغ) گرون شد. اگه آخرین بار دو ماه پیش قیمت دادی، احتمالاً الان سودت از چیزی که فکر می‌کنی کمتره.</p>

		<h2>قیمت هر پرس غذای خانگی رو چطور حساب کنیم؟</h2>
		<p>غذا رو دیگی می‌پزی و پرسی می‌فروشی. پس اول هزینه‌ی کل دیگ رو حساب کن، بعد تقسیمش کن بر تعداد پرس. گوشت و برنج بیشترین سهم رو دارن. گاز خورشی که چهار ساعت جا می‌افته هم اون‌قدرها که فکر می‌کنی کم نیست.</p>
		<p class="sp-formula">تمام‌شده‌ی هر پرس = (مواد + دستمزد × ساعت کار + گاز × ساعت پخت) ÷ تعداد پرس + ظرف هر پرس</p>
		<p>قیمت پیشنهادی هر پرس هم مثل کیک حساب می‌شه: تمام‌شده ÷ (۱ − درصد سود)، گرد شده به نزدیک‌ترین هزار تومان.</p>
		<h3>اندازه‌ی پرس رو ثابت نگه دار</h3>
		<p>یه روز ۱۲۰ گرم برنج خام توی هر پرس بریزی و یه روز ۱۵۰ گرم، هزینه‌ی برنجت ۲۵٪ بالا و پایین می‌ره. برای برنج و خورش یه پیمانه‌ی ثابت بذار کنار دستت.</p>
		<h3>قیمت رستوران بغلی رو کپی نکن</h3>
		<p>رستوران گوشت و برنج رو عمده می‌خره و هزینه‌هاش با تو فرق داره. اول قیمتت رو از هزینه‌ی خودت دربیار، بعد نگاه کن کجای بازار می‌شینه.</p>

		<h2>سؤال‌های رایج</h2>
		<details class="sp-faq"><summary>باید حسابداری بلد باشم؟</summary><p>نه. تو مواد کیک یا غذات رو می‌دونی، بقیه‌ی حساب‌وکتاب با سودپز.</p></details>
		<details class="sp-faq"><summary>برای غذای خانگی هم کار می‌کنه؟</summary><p>آره. «غذای خانگی» رو بزن و تعداد پرس دیگت رو بنویس. قیمت تمام‌شده و قیمت پیشنهادی هر پرس رو می‌بینی.</p></details>
		<details class="sp-faq"><summary>قیمت مواد از کجا میاد؟</summary><p>هر هفته از گزارش‌های رسمی و قیمت فروشگاه‌ها به‌روز می‌شه. هر وقت خواستی، قیمت خرید خودت رو جاش بنویس.</p></details>
		<details class="sp-faq"><summary>کسی دستور من رو می‌بینه؟</summary><p>نه. این ماشین‌حساب چیزی ذخیره نمی‌کنه. توی سودپز پرو هم دستورها و قیمت‌هات رو فقط خودت می‌بینی.</p></details>
		<details class="sp-faq"><summary>موقع قطعی اینترنت کار می‌کنه؟</summary><p>آره، سرور سودپز داخل ایرانه.</p></details>
	</div>
</section>

<?php get_footer(); ?>
