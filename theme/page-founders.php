<?php
/* صفحه‌ی عضو بنیان‌گذار (نامک: founders) */
get_header();
$sp_s     = sp_settings();
$sp_state = isset( $_GET['sp'] ) ? sanitize_key( wp_unslash( $_GET['sp'] ) ) : '';
?>

<section class="sp-section">
	<div class="sp-wrap sp-founders">
		<div class="sp-founders__text">
			<p class="sp-script">فقط ۵۰ نفر</p>
			<h1 class="sp-h1">عضو بنیان‌گذار سودپز شو</h1>
			<p class="sp-lead">برای قنادهای خانگی و آشپزهای غذای خانگی. سالانه <b>۳۹۰ هزار تومان</b>، و این قیمت برای تو هیچ‌وقت بالا نمی‌ره. قیمت عادی پرو سالانه ۶۹۰ هزار تومانه.</p>
			<?php if ( '' !== $sp_s['founders_left'] ) : ?>
				<p class="sp-note"><?php echo esc_html( sp_fa( $sp_s['founders_left'] ) ); ?> جا از ۵۰ تا مونده.</p>
			<?php endif; ?>

			<div class="sp-perks">
				<div class="sp-perk"><span class="sp-feature__ico" style="--c:#E8673C"><?php echo sp_icon( 'spark', 22 ); ?></span><b>پرو کامل، قبل از همه</b><small>هر ویژگی جدید اول دست شما می‌رسه.</small></div>
				<div class="sp-perk"><span class="sp-feature__ico" style="--c:#149C98"><?php echo sp_icon( 'book', 22 ); ?></span><b>دستورهات رو ما وارد می‌کنیم</b><small>کیک یا غذا؛ برای ۳۰ نفر اول، رایگان.</small></div>
				<div class="sp-perk"><span class="sp-feature__ico" style="--c:#8A4FBF"><?php echo sp_icon( 'users', 22 ); ?></span><b>گروه بنیان‌گذارها</b><small>بگو چی لازم داری؛ اول برای شما ساخته می‌شه.</small></div>
				<div class="sp-perk"><span class="sp-feature__ico" style="--c:#E9A23B"><?php echo sp_icon( 'lock', 22 ); ?></span><b>قیمت قفل، برای همیشه</b><small>۳۹۰ هزار، تا وقتی تمدید می‌کنی.</small></div>
			</div>

			<h2 class="sp-h3">چطوری</h2>
			<ol class="sp-howto">
				<li>فرم رو پر کن.</li>
				<li>تا ۲۴ ساعت بهت پیام می‌دیم و شماره‌کارت رو می‌فرستیم.</li>
				<li>بعد از واریز، رسید رو بفرست؛ عضویتت تا ۲۴ ساعت فعال می‌شه.</li>
			</ol>
			<p class="sp-small">تا ۷ روز بعد از پرداخت، بدون سؤال پولت برمی‌گرده.</p>
		</div>

		<div class="sp-founders__form" id="join">
			<?php if ( 'sent' === $sp_state ) : ?>
				<div class="sp-verdict sp-verdict--good" role="status">
					<b>رسید، مرسی!</b><br>تا ۲۴ ساعت بهت پیام می‌دیم.
				</div>
			<?php else : ?>
				<?php if ( 'invalid' === $sp_state ) : ?>
					<div class="sp-verdict sp-verdict--bad" role="alert">اسم و شماره‌ی موبایل (مثل ۰۹۱۲۱۲۳۴۵۶۷) رو درست بنویس.</div>
				<?php elseif ( 'expired' === $sp_state ) : ?>
					<div class="sp-verdict sp-verdict--warn" role="alert">صفحه خیلی وقت باز مونده بود؛ یه بار دیگه بفرست.</div>
				<?php endif; ?>
				<h2>جاتو نگه دار</h2>
				<form class="sp-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="sp_founder">
					<input type="hidden" name="sp_nonce" value="<?php echo esc_attr( wp_create_nonce( 'sp_founder' ) ); ?>">
					<div class="sp-hp" aria-hidden="true"><label>وبسایت <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

					<div class="sp-field">
						<label for="f-name">اسمت</label>
						<input id="f-name" name="name" type="text" required autocomplete="name">
					</div>
					<div class="sp-field">
						<label for="f-mobile">موبایل</label>
						<input id="f-mobile" name="mobile" type="tel" required inputmode="tel" autocomplete="tel" placeholder="۰۹۱۲۱۲۳۴۵۶۷" dir="ltr">
					</div>
					<div class="sp-field">
						<label for="f-city">شهر</label>
						<input id="f-city" name="city" type="text" autocomplete="address-level2">
					</div>
					<div class="sp-field">
						<label for="f-insta">پیج اینستاگرام (اختیاری)</label>
						<input id="f-insta" name="instagram" type="text" dir="ltr" placeholder="@">
					</div>
					<div class="sp-field">
						<span class="sp-label" id="f-kitchen-l"><b>چی می‌پزی؟</b></span>
						<div class="sp-radios" role="radiogroup" aria-labelledby="f-kitchen-l">
							<label><input type="radio" name="kitchen" value="sweet"> کیک و شیرینی</label>
							<label><input type="radio" name="kitchen" value="food"> غذای خانگی</label>
							<label><input type="radio" name="kitchen" value="both"> هر دو</label>
						</div>
					</div>
					<div class="sp-field">
						<label for="f-note">چی‌ها می‌پزی و هفته‌ای چند تا سفارش داری؟ (اختیاری)</label>
						<textarea id="f-note" name="note" rows="3"></textarea>
					</div>
					<button class="sp-btn sp-btn--coral sp-btn--block" type="submit">جامو نگه دار</button>
					<p class="sp-small">شماره‌ت فقط برای همین پیام استفاده می‌شه.</p>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>

<script>
(function () {
	// انتخاب پیش‌فرض «چی می‌پزی؟» از سوئیچ شیرینی | غذا
	var k = document.documentElement.getAttribute('data-k');
	var r = document.querySelector('.sp-radios input[value="' + k + '"]');
	if (r && !document.querySelector('.sp-radios input:checked')) { r.checked = true; }
})();
</script>
<?php get_footer(); ?>
