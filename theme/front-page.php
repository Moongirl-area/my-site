<?php
get_header();
$sp_recipes = sp_recipes();
$sp_ings    = sp_ingredients();
$sp_first   = array_key_first( $sp_recipes );
$sp_wood    = sp_img_url( 'wood' );
?>

<!-- ۱. بالای صفحه -->
<section class="sp-hero">
	<div class="sp-wrap sp-hero__grid">
		<div class="sp-hero__text">
			<p class="sp-script">رفیق حسابگر آشپزخونه</p>
			<h1 class="sp-display">کیکت چند<br>تموم می‌شه؟</h1>
			<p class="sp-lead">دقیق بدون، با سود بفروش. سودپز قیمت تمام‌شده و قیمت فروش هر کیک خانگیت رو حساب می‌کنه؛ هر بار هم کره یا خامه گرون شد، همه‌ی قیمت‌ها با یه کلیک درست می‌شن.</p>

			<ul class="sp-meta">
				<li><?php echo sp_icon( 'clock', 20 ); ?><span><small>زمان</small>۲ دقیقه</span></li>
				<li><?php echo sp_icon( 'user', 20 ); ?><span><small>ثبت‌نام</small>لازم نیست</span></li>
				<li><?php echo sp_icon( 'phone', 20 ); ?><span><small>روی</small>گوشی</span></li>
			</ul>

			<div class="sp-actions">
				<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>"><?php echo sp_icon( 'calc', 20 ); ?>قیمت کیکت رو حساب کن</a>
				<a class="sp-btn sp-btn--line" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار شو</a>
			</div>
		</div>

		<div class="sp-hero__art">
			<div class="sp-plate sp-plate--hero">
				<?php echo sp_img( 'hero-cake', 'کیک شکلاتی خانگی از بالا', 'sp-plate__img', true ); ?>
			</div>

			<div class="sp-widget sp-widget--price">
				<span class="sp-chip">نمونه</span>
				<div class="sp-widget__title"><?php echo sp_icon( 'cake', 18 ); ?> کیک شکلاتی، ۱ کیلویی</div>
				<div class="sp-widget__row"><span>تمام‌شده</span><b>۶۴۲ هزار</b></div>
				<div class="sp-widget__big"><span>بفروش</span><b>۹۲۰ هزار</b></div>
				<div class="sp-widget__good"><?php echo sp_icon( 'up', 16 ); ?> ۳۰٪ سود</div>
			</div>

			<div class="sp-widget sp-widget--alert">
				<span class="sp-widget__ico"><?php echo sp_icon( 'bell', 18 ); ?></span>
				<p><b>کره گرون شد.</b> قیمت ۴ محصولت عوض شد؛ ببین.</p>
			</div>

			<ul class="sp-ingredients">
				<li><?php echo sp_img( 'ing-butter', 'کره', 'sp-ingredients__img' ); ?><span>کره</span></li>
				<li><?php echo sp_img( 'ing-eggs', 'تخم‌مرغ', 'sp-ingredients__img' ); ?><span>تخم‌مرغ</span></li>
				<li><?php echo sp_img( 'ing-flour', 'آرد', 'sp-ingredients__img' ); ?><span>آرد</span></li>
				<li><?php echo sp_img( 'ing-chocolate', 'شکلات', 'sp-ingredients__img' ); ?><span>شکلات</span></li>
			</ul>
		</div>
	</div>
</section>

<!-- ۲. درد -->
<section class="sp-pains">
	<div class="sp-wrap sp-pains__grid">
		<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E03A3E"><?php echo sp_icon( 'tag', 22 ); ?></span><p>دو ماه پیش قیمت بستی، الان کره گرون‌تره.</p></div>
		<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E9A23B"><?php echo sp_icon( 'flame', 22 ); ?></span><p>دستمزد خودت و گاز فر رو حساب کردی؟</p></div>
		<div class="sp-pain"><span class="sp-pain__ico" style="--c:#149C98"><?php echo sp_icon( 'calc', 22 ); ?></span><p>مطمئنی روی همه‌ی کیک‌هات سود می‌کنی؟</p></div>
	</div>
</section>

<!-- ۳. چطور کار می‌کنه (بخش تیره) -->
<section class="sp-dark">
	<div class="sp-dark__grid">
		<div class="sp-dark__text">
			<p class="sp-script sp-script--light">سه قدم تا قیمت درست</p>
			<h2 class="sp-display sp-display--md">چطور کار می‌کنه</h2>
			<ol class="sp-timeline">
				<li>
					<span class="sp-timeline__n">۱</span>
					<div><h3>کیکت رو انتخاب کن</h3><p>از کتابخونه‌ی دستورهای آماده بردار، یا دستور خودت رو با مواد و مقدارش بنویس.</p></div>
				</li>
				<li>
					<span class="sp-timeline__n">۲</span>
					<div><h3>قیمت واقعی رو ببین</h3><p>سودپز با قیمت به‌روز مواد، دستمزد خودت، گاز فر و بسته‌بندی، می‌گه چند تموم می‌شه و چند بفروشی.</p></div>
				</li>
				<li>
					<span class="sp-timeline__n">۳</span>
					<div><h3>لیست قیمتت رو بفرست</h3><p>لیست قیمت شیکت رو برای استوری دانلود کن. کره گرون شد؟ همه‌چی خودش به‌روز می‌شه.</p></div>
				</li>
			</ol>
		</div>
		<div class="sp-dark__photo">
			<?php echo sp_img( 'baking-hands', 'دست‌های قناد در حال تزئین کیک', 'sp-dark__img' ); ?>
		</div>
	</div>
</section>

<!-- ۴. انتخاب کیک روی میز چوبی -->
<section class="sp-table-scene"<?php echo $sp_wood ? ' style="--wood:url(' . esc_url( $sp_wood ) . ')"' : ''; ?> id="cakes">
	<div class="sp-wrap">
		<div class="sp-scene">
			<div class="sp-scene__info">
				<p class="sp-script sp-script--light">کتابخونه‌ی دستورها</p>
				<h2 class="sp-display sp-display--md">امروز چی می‌پزی؟</h2>
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<div class="sp-scene__card" data-cake="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_first ? '' : ' hidden'; ?>>
						<h3><?php echo esc_html( $r['name'] ); ?></h3>
						<ul class="sp-tags">
							<?php foreach ( array_keys( $r['items'] ) as $ik ) : ?>
								<li><?php echo esc_html( $sp_ings[ $ik ]['name'] ); ?></li>
							<?php endforeach; ?>
						</ul>
						<p class="sp-scene__meta"><?php echo sp_icon( 'clock', 18 ); ?> حدود <?php echo esc_html( sp_fa( $r['hours'] ) ); ?> ساعت کار برای ۱ کیلو</p>
						<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( add_query_arg( 'cake', $key, sp_url( 'calculator' ) ) ); ?>">قیمتش رو حساب کن <?php echo sp_icon( 'arrow', 18 ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="sp-scene__plate">
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<div class="sp-plate sp-plate--scene" data-cake="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_first ? '' : ' hidden'; ?>>
						<?php echo sp_img( $r['img'], $r['name'], 'sp-plate__img' ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="sp-flags" role="tablist" aria-label="انتخاب کیک">
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<button class="sp-flag" type="button" role="tab" style="--c:<?php echo esc_attr( $r['color'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $key === $sp_first ? 'true' : 'false'; ?>">
						<?php echo sp_icon( $r['icon'], 26 ); ?><span><?php echo esc_html( $r['name'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ۵. امکانات + پیش‌نمایش اپ -->
<section class="sp-section" id="features">
	<div class="sp-wrap sp-features">
		<div>
			<p class="sp-script">سودپز پرو</p>
			<h2 class="sp-display sp-display--md">هر چی برای ضرر نکردن لازم داری</h2>
			<p class="sp-sub">نسخه‌ی کامل اول برای عضوهای بنیان‌گذار باز می‌شه.</p>
			<div class="sp-feature-grid">
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E8673C"><?php echo sp_icon( 'refresh', 24 ); ?></span><h3>بازقیمت‌گذاری یه‌کلیکی</h3><p>قیمت یه ماده رو عوض کن، قیمت همه‌ی کیک‌هات خودش درست می‌شه.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#149C98"><?php echo sp_icon( 'tag', 24 ); ?></span><h3>قیمت مرجع هفتگی</h3><p>قیمت ۲۵ ماده‌ی پرمصرف هر هفته به‌روز می‌شه؛ قیمت خودت رو هم می‌تونی بذاری.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E9A23B"><?php echo sp_icon( 'book', 24 ); ?></span><h3>دستورهای آماده</h3><p>کیک‌های پرفروش با مقدار مواد آماده‌ان؛ فقط وزن و قیمتت رو عوض کن.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E03A3E"><?php echo sp_icon( 'bell', 24 ); ?></span><h3>هشدار ضرر</h3><p>هر کیکی که زیر قیمت تمام‌شده می‌فروشی، قرمز می‌شه و می‌گه چقدر.</p></div>
			</div>
		</div>

		<div class="sp-phone" aria-label="نمونه‌ی صفحه‌ی اپ سودپز">
			<div class="sp-phone__screen">
				<div class="sp-phone__bar"><b>محصولات من</b><span class="sp-chip">نمونه</span></div>
				<div class="sp-app-alert"><?php echo sp_icon( 'bell', 18 ); ?><span>کره ۱۲٪ گرون شد. ۴ محصولت تغییر کردن.</span></div>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#E8673C"><?php echo sp_icon( 'cake', 18 ); ?></span><div><b>کیک شکلاتی</b><small>تمام‌شده ۶۴۲ هزار</small></div><em class="is-good">۹۲۰ هزار</em></div>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#149C98"><?php echo sp_icon( 'cheese', 18 ); ?></span><div><b>چیزکیک سرد</b><small>تمام‌شده ۵۸۰ هزار</small></div><em class="is-bad">۵۲۰ هزار</em></div>
				<p class="sp-app-loss"><?php echo sp_icon( 'down', 16 ); ?> چیزکیک سرد رو کیلویی ۶۰ هزار تومان زیر قیمت تمام‌شده می‌فروشی.</p>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#E03A3E"><?php echo sp_icon( 'carrot', 18 ); ?></span><div><b>کیک هویج</b><small>تمام‌شده ۴۹۰ هزار</small></div><em class="is-good">۷۰۰ هزار</em></div>
				<div class="sp-phone__nav"><span class="is-on"><?php echo sp_icon( 'cake', 20 ); ?>محصولات</span><span><?php echo sp_icon( 'tag', 20 ); ?>مواد</span><span><?php echo sp_icon( 'story', 20 ); ?>لیست قیمت</span></div>
			</div>
		</div>
	</div>
</section>

<!-- ۶. اعتماد -->
<section class="sp-trust">
	<div class="sp-wrap sp-trust__grid">
		<div><?php echo sp_icon( 'server', 28 ); ?><strong>روی سرور ایرانی</strong><span>موقع قطعی اینترنت هم بازه.</span></div>
		<div><?php echo sp_icon( 'lock', 28 ); ?><strong>دستورهات مال خودته</strong><span>بدون اجازه‌ی تو هیچ‌جا نمی‌ره.</span></div>
		<div><?php echo sp_icon( 'refund', 28 ); ?><strong>۷ روز بازگشت پول</strong><span>بدون سؤال، پولت برمی‌گرده.</span></div>
	</div>
</section>

<!-- ۷. پایان -->
<section class="sp-final">
	<div class="sp-wrap sp-final__inner">
		<div>
			<p class="sp-script sp-script--light">فقط ۵۰ نفر</p>
			<h2 class="sp-display sp-display--md">یه کیکِ کم‌فروخته، گرون‌تر از اشتراک سودپزه.</h2>
			<p>پرو ماهانه ۹۹ هزار یا سالانه ۶۹۰ هزار تومان. عضوهای بنیان‌گذار: سالانه <b>۳۹۰ هزار تومان</b>، برای همیشه.</p>
		</div>
		<div class="sp-final__cta">
			<a class="sp-btn sp-btn--paper" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">جامو نگه دار</a>
			<a class="sp-link-light" href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>">مقایسه‌ی پلن‌ها ←</a>
		</div>
	</div>
</section>

<script>
(function () {
	var flags = document.querySelectorAll('.sp-flag');
	Array.prototype.forEach.call(flags, function (f) {
		f.addEventListener('click', function () {
			var key = f.getAttribute('data-cake');
			Array.prototype.forEach.call(flags, function (x) { x.setAttribute('aria-selected', x === f ? 'true' : 'false'); });
			Array.prototype.forEach.call(document.querySelectorAll('.sp-scene [data-cake]:not(.sp-flag)'), function (el) {
				el.hidden = el.getAttribute('data-cake') !== key;
			});
		});
	});
})();
</script>

<?php get_footer(); ?>
