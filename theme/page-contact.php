<?php
/* تماس با ما (نامک: contact) */
get_header();
$sp_state = isset( $_GET['sp'] ) ? sanitize_key( wp_unslash( $_GET['sp'] ) ) : '';
?>
<section class="sp-page-head">
	<div class="sp-wrap">
		<p class="sp-script">تماس</p>
		<h1 class="sp-h1">بهمون بگو</h1>
		<p class="sp-sub">سؤال، پیشنهاد یا همکاری؛ معمولاً تا ۲۴ ساعت جواب می‌دیم.</p>
	</div>
</section>
<section class="sp-section sp-section--tight">
	<div class="sp-wrap sp-contact">
		<div class="sp-prose">
			<h2 style="margin-top:0">قبل از نوشتن</h2>
			<ul class="sp-contact__list">
				<li><?php echo sp_icon( 'book', 22 ); ?><div><b>جواب سؤال‌های رایج</b><br><a href="<?php echo esc_url( sp_url( 'faq' ) ); ?>">صفحه‌ی سؤال‌های رایج</a> رو ببین؛ شاید جوابت اونجا باشه.</div></li>
				<li><?php echo sp_icon( 'users', 22 ); ?><div><b>عضویت بنیان‌گذار</b><br>برای پیش‌خرید، <a href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">فرم بنیان‌گذار</a> رو پر کن.</div></li>
				<li><?php echo sp_icon( 'spark', 22 ); ?><div><b>آموزشگاه یا مربی قنادی هستی؟</b><br>برای همکاری و بسته‌ی هنرجوها، موضوع «همکاری» رو انتخاب کن.</div></li>
			</ul>
			<?php while ( have_posts() ) : the_post(); the_content(); endwhile; // اطلاعات تماس اضافه (اینستاگرام، تلفن) را در پیشخوان به این صفحه اضافه کن ?>
		</div>

		<div class="sp-contact__card" id="form">
			<?php if ( 'sent' === $sp_state ) : ?>
				<div class="sp-verdict sp-verdict--good" role="status"><b>پیامت رسید، مرسی!</b><br>به‌زودی جواب می‌دیم.</div>
			<?php else : ?>
				<?php if ( 'invalid' === $sp_state ) : ?>
					<div class="sp-verdict sp-verdict--bad" role="alert">اسم، راه ارتباط و پیامت رو کامل بنویس.</div>
				<?php elseif ( 'expired' === $sp_state ) : ?>
					<div class="sp-verdict sp-verdict--warn" role="alert">صفحه خیلی وقت باز مونده بود؛ یه بار دیگه بفرست.</div>
				<?php endif; ?>
				<form class="sp-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="sp_contact">
					<input type="hidden" name="sp_nonce" value="<?php echo esc_attr( wp_create_nonce( 'sp_contact' ) ); ?>">
					<div class="sp-hp" aria-hidden="true"><label>وبسایت <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
					<div class="sp-field">
						<label for="c-name">اسمت</label>
						<input id="c-name" name="name" type="text" required autocomplete="name">
					</div>
					<div class="sp-field">
						<label for="c-reach">موبایل یا ایمیل</label>
						<input id="c-reach" name="reach" type="text" required dir="ltr" autocomplete="tel">
					</div>
					<div class="sp-field">
						<label for="c-topic">موضوع</label>
						<select id="c-topic" name="topic">
							<?php foreach ( sp_contact_topics() as $sp_k => $sp_l ) : ?>
								<option value="<?php echo esc_attr( $sp_k ); ?>"><?php echo esc_html( $sp_l ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="sp-field">
						<label for="c-msg">پیامت</label>
						<textarea id="c-msg" name="message" rows="5" required></textarea>
					</div>
					<button class="sp-btn sp-btn--coral sp-btn--block" type="submit">بفرست</button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>
