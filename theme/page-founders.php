<?php
/* صفحه‌ی عضو بنیان‌گذار (نامک: founders) */
get_header();
$sp_s     = sp_settings();
$sp_state = isset( $_GET['sp'] ) ? sanitize_key( wp_unslash( $_GET['sp'] ) ) : '';
?>

<section class="sp-section">
	<div class="sp-wrap sp-founders">
		<div class="sp-founders__text">
			<span class="sp-badge sp-badge--accent">فقط ۵۰ نفر</span>
			<h1 class="sp-h1">عضو بنیان‌گذار سودپز شو</h1>
			<p class="sp-lead">سالانه <b>۳۹۰ هزار تومان</b>، و این قیمت برای تو هیچ‌وقت بالا نمی‌ره. قیمت عادی پرو سالانه ۶۹۰ هزار تومانه.</p>
			<?php if ( '' !== $sp_s['founders_left'] ) : ?>
				<p class="sp-note"><?php echo esc_html( sp_fa( $sp_s['founders_left'] ) ); ?> جا از ۵۰ تا مونده.</p>
			<?php endif; ?>

			<h2 class="sp-h3">چی می‌گیری</h2>
			<ul class="sp-checks">
				<li>سودپز پرو کامل، قبل از همه</li>
				<li>دستورهات رو خودمون برات وارد می‌کنیم (برای ۳۰ نفر اول)</li>
				<li>گروه بنیان‌گذارها: هر ویژگی‌ای که لازم داری رو بگو، اول برای شما ساخته می‌شه</li>
				<li>قیمت ۳۹۰ هزار، تا وقتی تمدید می‌کنی، برای همیشه</li>
			</ul>

			<h2 class="sp-h3">چطوری</h2>
			<ol class="sp-numbered">
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
						<label for="f-note">چی می‌پزی و هفته‌ای چند تا سفارش داری؟ (اختیاری)</label>
						<textarea id="f-note" name="note" rows="3"></textarea>
					</div>
					<button class="sp-btn sp-btn--primary sp-btn--block" type="submit">جامو نگه دار</button>
					<p class="sp-small">شماره‌ت فقط برای همین پیام استفاده می‌شه.</p>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
