<?php
/* صفحه‌ی ماشین‌حساب رایگان (نامک: calculator): دستورهای آماده + «دستور خودم» */
get_header();
$sp_date = sp_prices_latest_date();
?>

<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<div class="sp-calc-hero">
			<div>
				<p class="sp-script">ماشین‌حساب رایگان</p>
				<h1 class="sp-h1">ماشین‌حساب قیمت کیک و غذای خانگی ۱۴۰۵</h1>
				<p class="sp-sub">یه دستور آماده انتخاب کن یا <b>دستور خودت</b> رو بساز: مواد، مقدار، دستمزد، گاز و بسته‌بندی. همون لحظه می‌بینی چند برات تموم می‌شه و چند بفروشی که سود کنی.</p>
				<?php if ( $sp_date ) : ?>
					<p class="sp-note"><?php echo sp_icon( 'tag', 20 ); ?> قیمت پیشنهادی مواد به‌روز تا <?php echo esc_html( $sp_date ); ?>. اگه ارزون‌تر یا گرون‌تر خریدی، قیمت خودت رو بنویس؛ یادش می‌مونه.</p>
				<?php else : ?>
					<p class="sp-note sp-note--warn"><?php echo sp_icon( 'bell', 20 ); ?> قیمت پیشنهادی مواد این هفته هنوز نیومده. فعلاً قیمتی که خودت خریدی رو بنویس.</p>
				<?php endif; ?>
			</div>
			<div class="sp-plate" id="sp-hero-plate" hidden><img class="sp-plate__img" id="sp-hero-img" alt="" decoding="async"></div>
		</div>

		<noscript><p class="sp-note sp-note--warn">ماشین‌حساب برای کار کردن به جاوااسکریپت نیاز داره. لطفاً جاوااسکریپت مرورگرت رو روشن کن.</p></noscript>

		<div class="sp-calc-form" id="sp-calc">
			<?php sp_kitchen_switch( 'چی می‌پزی؟' ); ?>

			<div class="sp-picker">
				<div class="sp-picker__head">
					<h2 class="sp-picker__title"><span data-only="sweet">کدوم کیک یا شیرینی؟</span><span data-only="food">کدوم غذا؟</span></h2>
					<label class="sp-picker__search"><?php echo sp_icon( 'search', 18 ); ?><input type="search" id="sp-rsearch" placeholder="جست‌وجوی دستور…" aria-label="جست‌وجوی دستور"></label>
				</div>
				<div class="sp-rgrid" id="sp-rgrid" role="list"></div>
			</div>

			<div class="sp-calc">
				<div class="sp-calc__inputs">
					<div class="sp-ed-head">
						<div class="sp-ed-head__name">
							<label class="sp-sr" for="sp-name">اسم دستور</label>
							<input id="sp-name" type="text" maxlength="60" placeholder="اسم کیک یا غذات">
							<span class="sp-ed-badge" id="sp-badge"></span>
						</div>
						<div class="sp-ed-actions">
							<span class="sp-ed-status" id="sp-status" aria-live="polite"></span>
							<button type="button" class="sp-btn sp-btn--coral sp-btn--sm" id="sp-save"><?php echo sp_icon( 'save', 18 ); ?><span>ذخیره در دستورهای من</span></button>
							<button type="button" class="sp-iconbtn" id="sp-dup" title="یه کپی بساز" aria-label="یه کپی بساز"><?php echo sp_icon( 'copy', 18 ); ?></button>
							<button type="button" class="sp-iconbtn sp-iconbtn--danger" id="sp-del" title="حذف این دستور" aria-label="حذف این دستور" hidden><?php echo sp_icon( 'trash', 18 ); ?></button>
						</div>
					</div>
					<p class="sp-ed-hint" id="sp-hint"></p>

					<div class="sp-field-row sp-field-row--3">
						<div class="sp-field">
							<label for="sp-yq">این دستور برای چند؟</label>
							<div class="sp-qty">
								<input id="sp-yq" type="text" inputmode="decimal">
								<select id="sp-yu" aria-label="واحد"></select>
							</div>
							<small>مقدار مواد خودکار به همین نسبت عوض می‌شه.</small>
						</div>
						<div class="sp-field">
							<label for="sp-hours">ساعت کار خودت</label>
							<input id="sp-hours" type="text" inputmode="decimal">
							<small>خرید، پخت، تزئین و شستن.</small>
						</div>
						<div class="sp-field">
							<label for="sp-wage">دستمزد هر ساعتت (تومان)</label>
							<input id="sp-wage" type="text" inputmode="numeric" placeholder="مثلاً ۱۰۰٬۰۰۰">
							<small>اگه حسابش نکنی، یعنی مجانی کار کردی.</small>
						</div>
					</div>

					<div class="sp-ing">
						<div class="sp-ing__head">
							<h2>مواد</h2>
							<small>مقدار رو با هر واحدی بنویس (گرم، پیمانه، قاشق، عدد…). قیمت برای «واحد خرید» هر ماده‌ست.</small>
						</div>
						<div class="sp-ilist" id="sp-ilist"></div>
						<div class="sp-iadd">
							<div class="sp-combo" id="sp-combo">
								<span class="sp-combo__ico"><?php echo sp_icon( 'plus', 18 ); ?></span>
								<input id="sp-iadd" type="text" autocomplete="off" placeholder="افزودن ماده: اسمش رو بنویس، مثلاً «خامه» یا «گوشت»" role="combobox" aria-expanded="false" aria-controls="sp-ilist-pop" aria-autocomplete="list">
								<ul class="sp-combo__pop" id="sp-ilist-pop" role="listbox" hidden></ul>
							</div>
						</div>
					</div>

					<div class="sp-costs">
						<h2><?php echo sp_icon( 'flame', 20 ); ?> هزینه‌های جانبی و سود</h2>
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
								<label for="sp-pack">بسته‌بندی (جعبه، ظرف، روبان…)</label>
								<div class="sp-qty">
									<input id="sp-pack" type="text" inputmode="numeric" placeholder="تومان">
									<select id="sp-packper" aria-label="بسته‌بندی برای"></select>
								</div>
							</div>
							<div class="sp-field">
								<label for="sp-margin">سود هدف (٪ از قیمت فروش)</label>
								<input id="sp-margin" type="text" inputmode="decimal">
							</div>
						</div>
						<div class="sp-extras" id="sp-extras"></div>
						<button type="button" class="sp-linkbtn" id="sp-xadd"><?php echo sp_icon( 'plus', 18 ); ?> هزینه‌ی دیگه (پیک، تبلیغ، اجاره‌ی وسیله، کمیسیون…)</button>
					</div>
				</div>

				<aside class="sp-calc__result" id="sp-result" aria-live="polite">
					<div class="sp-result">
						<div class="sp-result__head">
							<div class="sp-plate" id="r-plate" hidden><img class="sp-plate__img" id="r-img" alt="" decoding="async"></div>
							<b id="r-name"></b>
						</div>
						<div class="sp-result__row"><span>هزینه‌ی مواد</span><b id="r-mat">—</b></div>
						<div class="sp-result__row"><span>دستمزد</span><b id="r-wage">—</b></div>
						<div class="sp-result__row"><span>گاز، بسته‌بندی و بقیه</span><b id="r-other">—</b></div>
						<div class="sp-result__row sp-result__row--total"><span>قیمت تمام‌شده‌ی کل</span><b id="r-cost">—</b></div>
						<div class="sp-result__row" id="r-cu-row"><span id="r-cu-label">تمام‌شده‌ی هر واحد</span><b id="r-cost-unit">—</b></div>
						<div class="sp-split" id="r-split" hidden>
							<div class="sp-split__donut" id="r-donut" role="img" aria-label="سهم هر بخش از قیمت فروش"><span id="r-donut-c"></span></div>
							<ul class="sp-split__legend">
								<li style="--c:#F2A33A"><i></i>مواد <b id="s-mat"></b></li>
								<li style="--c:#7FC8C4"><i></i>دستمزد <b id="s-wage"></b></li>
								<li style="--c:#B9A3D6"><i></i>گاز و بقیه <b id="s-other"></b></li>
								<li style="--c:#5FD08F"><i></i>سود <b id="s-profit"></b></li>
							</ul>
						</div>
						<div class="sp-result__big">
							<span id="r-price-label">قیمت پیشنهادی</span>
							<b id="r-price">—</b>
							<small id="r-perkg"></small>
						</div>
						<div class="sp-field">
							<label for="sp-current" id="r-current-label">الان چند می‌فروشی؟ (اختیاری)</label>
							<input id="sp-current" type="text" inputmode="numeric" placeholder="مثلاً ۷۵۰٬۰۰۰">
						</div>
						<div id="r-verdict" class="sp-verdict" hidden></div>
						<div class="sp-quote" id="r-quote-box" hidden>
							<div class="sp-quote__head"><b>متن قیمت برای مشتری</b><small>بدون هزینه‌ها و سودت</small></div>
							<textarea id="r-quote" rows="4" aria-label="متن قیمت برای فرستادن به مشتری"></textarea>
							<button class="sp-btn sp-btn--paper sp-btn--sm" type="button" id="r-copy"><?php echo sp_icon( 'story', 18 ); ?><span>کپی برای دایرکت</span></button>
						</div>
						<p class="sp-result__cta">این قیمت مال امروزه. هفته‌ی بعد که مواد گرون شد، باید از اول حساب کنی. سودپز پرو این کار رو خودش برات می‌کنه.</p>
						<a class="sp-btn sp-btn--primary sp-btn--block" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار شو</a>
					</div>
				</aside>
			</div>
		</div>
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
		<details class="sp-faq"><summary>قیمت پیشنهادی مواد از کجا میاد؟</summary><p>هر هفته از نرخ‌نامه‌ی میادین میوه و تره‌بار تهران، گزارش‌های مرکز آمار و میانگین قیمت فروشگاه‌های آنلاین جمع می‌شه و بعد از بازبینی روی سایت میاد. منبع و تاریخ هر قیمت کنارش نوشته شده. هر وقت خواستی، قیمت خرید خودت رو جاش بنویس.</p></details>
		<details class="sp-faq"><summary>می‌تونم غذا یا کیکی که توی لیست نیست رو حساب کنم؟</summary><p>آره. «دستور خودم» رو بزن، اسمش رو بنویس و مواد رو یکی‌یکی اضافه کن. اگه ماده‌ای توی لیست نبود، اسمش رو بنویس و «ماده‌ی جدید» رو بزن تا با واحد و قیمت خودت اضافه بشه.</p></details>
		<details class="sp-faq"><summary>دستورهام کجا ذخیره می‌شن؟</summary><p>توی همین مرورگر، روی گوشی یا کامپیوتر خودت. ثبت‌نام لازم نیست و کسی جز خودت نمی‌بینه‌شون. اگه مرورگر رو پاک کنی یا گوشی رو عوض کنی، پاک می‌شن.</p></details>
		<details class="sp-faq"><summary>کسی دستور من رو می‌بینه؟</summary><p>نه. دستورها و قیمت‌هات فقط توی مرورگر خودت ذخیره می‌شن و به سرور سودپز فرستاده نمی‌شن.</p></details>
		<details class="sp-faq"><summary>موقع قطعی اینترنت کار می‌کنه؟</summary><p>آره، سرور سودپز داخل ایرانه.</p></details>
	</div>
</section>

<?php get_footer(); ?>
