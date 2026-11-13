<?php
get_header();
$sp_recipes = sp_recipes();
$sp_ings    = sp_ingredients();
$sp_wood    = sp_img_url( 'wood' );
$sp_firsts  = array(
	'sweet' => array_key_first( sp_recipes_of( 'sweet' ) ),
	'food'  => array_key_first( sp_recipes_of( 'food' ) ),
);
?>

<!-- ۱. بالای صفحه: هر دو آشپزخونه -->
<section class="sp-hero">
	<div class="sp-wrap sp-hero__grid">
		<div class="sp-hero__text">
			<p class="sp-script">رفیق حسابگر آشپزخونه</p>
			<h1 class="sp-display">قورمه‌سبزی و کیکت چند تموم می‌شه؟</h1>
			<p class="sp-lead">دقیق بدون، با سود بفروش. سودپز قیمت تمام‌شده و قیمت فروش هر کیک و هر پرس غذای خانگیت رو حساب می‌کنه؛ گوشت یا خامه هم که گرون شد، همه‌ی قیمت‌ها با یه کلیک درست می‌شن.</p>

			<div class="sp-doors">
				<a class="sp-door" href="<?php echo esc_url( add_query_arg( 'k', 'sweet', sp_url( 'calculator' ) ) ); ?>" style="--c:#E8673C">
					<span class="sp-door__ico"><?php echo sp_icon( 'cake', 26 ); ?></span>
					<span><small>قناد خانگی هستم</small><b>قیمت کیکم رو حساب کن</b></span>
					<?php echo sp_icon( 'arrow', 20 ); ?>
				</a>
				<a class="sp-door" href="<?php echo esc_url( add_query_arg( 'k', 'food', sp_url( 'calculator' ) ) ); ?>" style="--c:#3F8A4E">
					<span class="sp-door__ico"><?php echo sp_icon( 'pot', 26 ); ?></span>
					<span><small>غذای خانگی می‌فروشم</small><b>قیمت هر پرس رو حساب کن</b></span>
					<?php echo sp_icon( 'arrow', 20 ); ?>
				</a>
			</div>

			<ul class="sp-meta">
				<li><?php echo sp_icon( 'clock', 20 ); ?><span><small>زمان</small>۲ دقیقه</span></li>
				<li><?php echo sp_icon( 'user', 20 ); ?><span><small>ثبت‌نام</small>لازم نیست</span></li>
				<li><?php echo sp_icon( 'phone', 20 ); ?><span><small>روی</small>گوشی</span></li>
			</ul>
		</div>

		<div class="sp-hero__art">
			<div class="sp-plate sp-plate--hero">
				<?php echo sp_img( 'hero-cake', 'کیک شکلاتی خانگی از بالا', 'sp-plate__img', true ); ?>
			</div>
			<div class="sp-plate sp-plate--hero2">
				<?php echo sp_img( 'food-ghormeh', 'قورمه‌سبزی با برنج', 'sp-plate__img', true ); ?>
			</div>

			<div class="sp-widget sp-widget--price">
				<span class="sp-chip">نمونه</span>
				<div class="sp-widget__title"><?php echo sp_icon( 'pot', 18 ); ?> قورمه‌سبزی، هر پرس</div>
				<div class="sp-widget__row"><span>تمام‌شده</span><b>۲۷۰ هزار</b></div>
				<div class="sp-widget__big"><span>بفروش</span><b>۳۸۶ هزار</b></div>
				<div class="sp-widget__good"><?php echo sp_icon( 'up', 16 ); ?> ۳۰٪ سود</div>
			</div>

			<div class="sp-widget sp-widget--alert">
				<span class="sp-widget__ico"><?php echo sp_icon( 'bell', 18 ); ?></span>
				<p><b>گوشت گرون شد.</b> قیمت ۳ غذات عوض شد؛ ببین.</p>
			</div>
		</div>
	</div>
</section>

<!-- ۲. سوئیچ آشپزخونه + دردها -->
<section class="sp-pains">
	<div class="sp-wrap">
		<?php sp_kitchen_switch(); ?>
		<div class="sp-pains__grid" data-only="sweet">
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E03A3E"><?php echo sp_icon( 'tag', 22 ); ?></span><p>دو ماه پیش قیمت بستی، الان خامه و شکلات گرون‌ترن.</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E9A23B"><?php echo sp_icon( 'flame', 22 ); ?></span><p>دستمزد خودت و گاز فر رو حساب کردی؟</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#149C98"><?php echo sp_icon( 'calc', 22 ); ?></span><p>مطمئنی روی همه‌ی کیک‌هات سود می‌کنی؟</p></div>
		</div>
		<div class="sp-pains__grid" data-only="food">
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E03A3E"><?php echo sp_icon( 'tag', 22 ); ?></span><p>منوی این هفته رو با قیمت گوشت ماه پیش بستی؟</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E9A23B"><?php echo sp_icon( 'box', 22 ); ?></span><p>ظرف، گاز چهارساعته‌ی اجاق و دستمزدت رو حساب کردی؟</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#149C98"><?php echo sp_icon( 'calc', 22 ); ?></span><p>قیمت رستوران بغلی رو کپی کردی؟ هزینه‌ی اون با تو فرق داره.</p></div>
		</div>
	</div>
</section>

<!-- ۳. حساب سرانگشتی در برابر سودپز -->
<section class="sp-section">
	<div class="sp-wrap">
		<p class="sp-script sp-center">یه محصول، دو حساب</p>
		<h2 class="sp-display sp-display--md sp-center">حساب سرانگشتی کجا ضرر می‌ده؟</h2>

		<div data-only="sweet">
			<p class="sp-sub sp-center">کیک شکلاتی ۱ کیلویی (عددها نمونه‌ان).</p>
			<div class="sp-compare">
				<div class="sp-compare__card sp-compare__card--bad">
					<h3><?php echo sp_icon( 'calc', 24 ); ?> حساب سرانگشتی</h3>
					<div class="sp-compare__row"><span>مواد</span><b>۴۲۰ هزار</b></div>
					<div class="sp-compare__row sp-compare__row--miss"><span>دستمزد ۲٫۵ ساعت</span><b>حساب نشد</b></div>
					<div class="sp-compare__row sp-compare__row--miss"><span>گاز فر و جعبه</span><b>حساب نشد</b></div>
					<div class="sp-compare__row"><span>«یه کم بذارم روش»</span><b>+ ۱۳۰ هزار</b></div>
					<div class="sp-compare__row"><span>قیمت فروش</span><b>۵۵۰ هزار</b></div>
					<p class="sp-compare__end"><?php echo sp_icon( 'down', 18 ); ?> هزینه‌ی واقعی ۶۴۲ هزاره؛ از هر کیک ۹۲ هزار تومان ضرر.</p>
				</div>
				<div class="sp-compare__card sp-compare__card--good">
					<h3><?php echo sp_icon( 'check', 24 ); ?> با سودپز</h3>
					<div class="sp-compare__row"><span>مواد با قیمت امروز</span><b>۴۲۰ هزار</b></div>
					<div class="sp-compare__row"><span>دستمزد ۲٫۵ ساعت</span><b>۱۸۷ هزار</b></div>
					<div class="sp-compare__row"><span>گاز فر و جعبه</span><b>۳۵ هزار</b></div>
					<div class="sp-compare__row"><span>قیمت تمام‌شده</span><b>۶۴۲ هزار</b></div>
					<div class="sp-compare__row"><span>قیمت فروش با ۳۰٪ سود</span><b>۹۲۰ هزار</b></div>
					<p class="sp-compare__end"><?php echo sp_icon( 'up', 18 ); ?> هم دستمزدت رو گرفتی، هم ۲۷۸ هزار تومان سود.</p>
				</div>
			</div>
		</div>

		<div data-only="food">
			<p class="sp-sub sp-center">قورمه‌سبزی با برنج، هر پرس (از یه دیگ ۱۰ پرسی؛ عددها نمونه‌ان).</p>
			<div class="sp-compare">
				<div class="sp-compare__card sp-compare__card--bad">
					<h3><?php echo sp_icon( 'calc', 24 ); ?> حساب سرانگشتی</h3>
					<div class="sp-compare__row"><span>مواد هر پرس</span><b>۲۲۰ هزار</b></div>
					<div class="sp-compare__row sp-compare__row--miss"><span>دستمزد (۳ ساعت برای ۱۰ پرس)</span><b>حساب نشد</b></div>
					<div class="sp-compare__row sp-compare__row--miss"><span>گاز ۴ ساعته و ظرف</span><b>حساب نشد</b></div>
					<div class="sp-compare__row"><span>«رستوران بغلی چند می‌ده؟»</span><b>۲۵۰ هزار</b></div>
					<p class="sp-compare__end"><?php echo sp_icon( 'down', 18 ); ?> هزینه‌ی واقعی ۲۷۰ هزاره؛ هر پرس ۲۰ هزار ضرر. با ۵۰ پرس در هفته، یعنی ۱ میلیون تومان.</p>
				</div>
				<div class="sp-compare__card sp-compare__card--good">
					<h3><?php echo sp_icon( 'check', 24 ); ?> با سودپز</h3>
					<div class="sp-compare__row"><span>مواد با قیمت امروز</span><b>۲۲۰ هزار</b></div>
					<div class="sp-compare__row"><span>سهم دستمزد</span><b>۳۰ هزار</b></div>
					<div class="sp-compare__row"><span>سهم گاز و ظرف</span><b>۲۰ هزار</b></div>
					<div class="sp-compare__row"><span>قیمت تمام‌شده‌ی هر پرس</span><b>۲۷۰ هزار</b></div>
					<div class="sp-compare__row"><span>قیمت فروش با ۳۰٪ سود</span><b>۳۸۶ هزار</b></div>
					<p class="sp-compare__end"><?php echo sp_icon( 'up', 18 ); ?> هم دستمزدت رو گرفتی، هم از هر پرس ۱۱۶ هزار تومان سود.</p>
				</div>
			</div>
		</div>

		<div class="sp-actions" style="justify-content:center;margin-top:32px">
			<a class="sp-btn sp-btn--coral" data-only="sweet" href="<?php echo esc_url( add_query_arg( 'k', 'sweet', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'calc', 20 ); ?>کیک خودت رو حساب کن</a>
			<a class="sp-btn sp-btn--coral" data-only="food" href="<?php echo esc_url( add_query_arg( 'k', 'food', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'calc', 20 ); ?>غذای خودت رو حساب کن</a>
		</div>
	</div>
</section>

<!-- ۴. چطور کار می‌کنه (بخش تیره) -->
<section class="sp-dark">
	<div class="sp-dark__grid">
		<div class="sp-dark__text">
			<p class="sp-script sp-script--light">سه قدم تا قیمت درست</p>
			<h2 class="sp-display sp-display--md">چطور کار می‌کنه</h2>
			<ol class="sp-timeline">
				<li>
					<span class="sp-timeline__n">۱</span>
					<div><h3>دستورت رو انتخاب کن</h3><p>از کتابخونه‌ی کیک‌ها و غذاهای آماده بردار، یا دستور خودت رو با مواد و مقدارش بنویس.</p></div>
				</li>
				<li>
					<span class="sp-timeline__n">۲</span>
					<div><h3>قیمت واقعی رو ببین</h3><p>با قیمت به‌روز مواد، دستمزد خودت، گاز فر یا اجاق و بسته‌بندی؛ برای کیک کیلویی، برای غذا هر پرس.</p></div>
				</li>
				<li>
					<span class="sp-timeline__n">۳</span>
					<div><h3>لیست قیمت یا منوت رو بفرست</h3><p>لیست قیمت یا منوی هفتگیت رو برای استوری بگیر. گوشت یا خامه گرون شد؟ همه‌چی خودش به‌روز می‌شه.</p></div>
				</li>
			</ol>
		</div>
		<div class="sp-dark__photo">
			<?php echo sp_img( 'baking-hands', 'دست‌های آشپز خانگی در حال کار', 'sp-dark__img' ); ?>
		</div>
	</div>
</section>

<!-- ۵. کتابخونه روی میز چوبی -->
<section class="sp-table-scene"<?php echo $sp_wood ? ' style="--wood:url(' . esc_url( $sp_wood ) . ')"' : ''; ?> id="recipes">
	<div class="sp-wrap">
		<?php sp_kitchen_switch( 'کدوم آشپزخونه؟', true ); ?>
		<div class="sp-scene">
			<div class="sp-scene__info">
				<p class="sp-script sp-script--light">کتابخونه‌ی دستورها</p>
				<h2 class="sp-display sp-display--md">امروز چی می‌پزی؟</h2>
				<?php foreach ( $sp_recipes as $key => $r ) : $sp_food = 'food' === $r['kitchen']; ?>
					<div class="sp-scene__card" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_firsts[ $r['kitchen'] ] ? '' : ' hidden'; ?>>
						<h3><?php echo esc_html( $r['name'] ); ?></h3>
						<ul class="sp-tags">
							<?php foreach ( array_keys( $r['items'] ) as $ik ) : ?>
								<li><?php echo esc_html( $sp_ings[ $ik ]['name'] ); ?></li>
							<?php endforeach; ?>
						</ul>
						<p class="sp-scene__meta"><?php echo sp_icon( 'clock', 18 ); ?> حدود <?php echo esc_html( sp_fa( $r['hours'] ) ); ?> ساعت کار برای <?php echo $sp_food ? '۱۰ پرس' : '۱ کیلو'; ?></p>
						<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( add_query_arg( 'dish', $key, sp_url( 'calculator' ) ) ); ?>">قیمتش رو حساب کن <?php echo sp_icon( 'arrow', 18 ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="sp-scene__plate">
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<div class="sp-plate sp-plate--scene" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_firsts[ $r['kitchen'] ] ? '' : ' hidden'; ?>>
						<?php echo sp_img( $r['img'], $r['name'], 'sp-plate__img' ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="sp-flags" role="tablist" aria-label="انتخاب دستور">
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<button class="sp-flag" type="button" role="tab" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" style="--c:<?php echo esc_attr( $r['color'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $key === $sp_firsts[ $r['kitchen'] ] ? 'true' : 'false'; ?>">
						<?php echo sp_icon( $r['icon'], 26 ); ?><span><?php echo esc_html( $r['name'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ۶. امکانات + پیش‌نمایش اپ -->
<section class="sp-section" id="features">
	<div class="sp-wrap sp-features">
		<div>
			<p class="sp-script">سودپز پرو</p>
			<h2 class="sp-display sp-display--md">هر چی برای ضرر نکردن لازم داری</h2>
			<p class="sp-sub">برای کیک و شیرینی، و برای غذای خانگی. نسخه‌ی کامل اول برای عضوهای بنیان‌گذار باز می‌شه.</p>
			<div class="sp-feature-grid">
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E8673C"><?php echo sp_icon( 'refresh', 24 ); ?></span><h3>بازقیمت‌گذاری یه‌کلیکی</h3><p>قیمت یه ماده رو عوض کن، قیمت همه‌ی کیک‌ها و غذاهات خودش درست می‌شه.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#149C98"><?php echo sp_icon( 'tag', 24 ); ?></span><h3>قیمت مرجع هفتگی</h3><p>قیمت حدود ۴۰ ماده‌ی پرمصرف، از خامه تا گوشت و برنج، هر هفته به‌روز می‌شه.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E9A23B"><?php echo sp_icon( 'book', 24 ); ?></span><h3>دستورهای آماده</h3><p>کیک‌های پرفروش و غذاهای خونگی رایج با مقدار مواد؛ فقط وزن یا تعداد پرس رو عوض کن.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E03A3E"><?php echo sp_icon( 'bell', 24 ); ?></span><h3>هشدار ضرر</h3><p>هر کیک یا غذایی که زیر قیمت تمام‌شده می‌فروشی، قرمز می‌شه و می‌گه چقدر.</p></div>
			</div>
		</div>

		<div class="sp-phone" aria-label="نمونه‌ی صفحه‌ی اپ سودپز">
			<div class="sp-phone__screen">
				<div class="sp-phone__bar"><b>محصولات من</b><span class="sp-chip">نمونه</span></div>
				<div class="sp-app-alert"><?php echo sp_icon( 'bell', 18 ); ?><span>گوشت ۸٪ گرون شد. ۳ غذات تغییر کردن.</span></div>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#3F8A4E"><?php echo sp_icon( 'pot', 18 ); ?></span><div><b>قورمه‌سبزی</b><small>هر پرس، تمام‌شده ۲۷۰ هزار</small></div><em class="is-good">۳۸۶ هزار</em></div>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#D9822B"><?php echo sp_icon( 'bowl', 18 ); ?></span><div><b>قیمه</b><small>هر پرس، تمام‌شده ۲۴۵ هزار</small></div><em class="is-bad">۲۳۰ هزار</em></div>
				<p class="sp-app-loss"><?php echo sp_icon( 'down', 16 ); ?> قیمه رو هر پرس ۱۵ هزار تومان زیر قیمت تمام‌شده می‌فروشی.</p>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#E8673C"><?php echo sp_icon( 'cake', 18 ); ?></span><div><b>کیک شکلاتی</b><small>کیلویی، تمام‌شده ۶۴۲ هزار</small></div><em class="is-good">۹۲۰ هزار</em></div>
				<div class="sp-phone__nav"><span class="is-on"><?php echo sp_icon( 'pot', 20 ); ?>محصولات</span><span><?php echo sp_icon( 'tag', 20 ); ?>مواد</span><span><?php echo sp_icon( 'story', 20 ); ?>لیست قیمت</span></div>
			</div>
		</div>
	</div>
</section>

<!-- ۷. اعتماد -->
<section class="sp-trust">
	<div class="sp-wrap sp-trust__grid">
		<div><?php echo sp_icon( 'server', 28 ); ?><strong>روی سرور ایرانی</strong><span>موقع قطعی اینترنت هم بازه.</span></div>
		<div><?php echo sp_icon( 'lock', 28 ); ?><strong>دستورهات مال خودته</strong><span>بدون اجازه‌ی تو هیچ‌جا نمی‌ره.</span></div>
		<div><?php echo sp_icon( 'refund', 28 ); ?><strong>۷ روز بازگشت پول</strong><span>بدون سؤال، پولت برمی‌گرده.</span></div>
	</div>
</section>

<!-- ۸. پایان -->
<section class="sp-final">
	<div class="sp-wrap sp-final__inner">
		<div>
			<p class="sp-script sp-script--light">فقط ۵۰ نفر</p>
			<h2 class="sp-display sp-display--md">یه کیک یا یه دیگ غذای کم‌فروخته، گرون‌تر از اشتراک سودپزه.</h2>
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
	// انتخاب دستور روی میز چوبی؛ هر آشپزخونه انتخاب خودش رو نگه می‌داره.
	var scene = document.querySelector('.sp-scene');
	if (!scene) return;
	scene.addEventListener('click', function (e) {
		var f = e.target.closest('.sp-flag');
		if (!f) return;
		var k = f.getAttribute('data-only'), key = f.getAttribute('data-cake');
		Array.prototype.forEach.call(scene.querySelectorAll('[data-only="' + k + '"][data-cake]'), function (el) {
			if (el.classList.contains('sp-flag')) {
				el.setAttribute('aria-selected', el === f ? 'true' : 'false');
			} else {
				el.hidden = el.getAttribute('data-cake') !== key;
			}
		});
	});
})();
</script>

<?php get_footer(); ?>
