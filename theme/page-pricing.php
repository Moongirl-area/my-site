<?php
/* صفحه‌ی قیمت‌ها (نامک: pricing) */
get_header();
$sp_s = sp_settings();
?>

<div class="sp-banner">
	<div class="sp-wrap">
		۵۰ عضو بنیان‌گذار: سالانه ۳۹۰ هزار تومان، همیشگی.
		<?php if ( '' !== $sp_s['founders_left'] ) : ?>
			<?php echo esc_html( sp_fa( $sp_s['founders_left'] ) ); ?> جا مونده.
		<?php endif; ?>
		<a href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">جاتو بگیر ←</a>
	</div>
</div>

<section class="sp-section">
	<div class="sp-wrap">
		<p class="sp-script sp-center">قیمت‌ها</p>
		<h1 class="sp-h1 sp-center">یه کیکِ کم‌فروخته، گرون‌تر از اشتراک سودپزه.</h1>

		<div class="sp-toggle" role="group" aria-label="دوره‌ی پرداخت">
			<button type="button" data-period="yearly" aria-pressed="true">سالانه <span class="sp-pill">۵ ماه مجانی</span></button>
			<button type="button" data-period="monthly" aria-pressed="false">ماهانه</button>
		</div>

		<div class="sp-plans">
			<div class="sp-plan">
				<span class="sp-plan__ico"><?php echo sp_icon( 'calc', 26 ); ?></span>
				<h2>رایگان</h2>
				<p class="sp-plan__for">برای شروع</p>
				<p class="sp-plan__price">مجانی <small>برای همیشه</small></p>
				<ul>
					<li>ماشین‌حساب عمومی قیمت کیک</li>
					<li>۱۴ روز پرو رایگان بعد از ثبت‌نام</li>
					<li>بعدش ۱ محصول رایگان، برای همیشه</li>
				</ul>
				<a class="sp-btn sp-btn--line sp-btn--block" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">همین الان حساب کن</a>
			</div>

			<div class="sp-plan sp-plan--pro">
				<span class="sp-badge">پیشنهاد ما</span>
				<span class="sp-plan__ico"><?php echo sp_icon( 'cake', 26 ); ?></span>
				<h2>پرو</h2>
				<p class="sp-plan__for">برای کسی که هر هفته سفارش داره</p>
				<p class="sp-plan__price" data-yearly="۶۹۰ هزار <small>تومان در سال</small>" data-monthly="۹۹ هزار <small>تومان در ماه</small>">۶۹۰ هزار <small>تومان در سال</small></p>
				<ul>
					<li>محصول و دستور نامحدود</li>
					<li>قیمت مرجع هفتگی ۲۵ ماده‌ی پرمصرف</li>
					<li>بازقیمت‌گذاری همه‌ی محصولات با یه کلیک</li>
					<li>هشدار ضرر</li>
					<li>دستورهای آماده</li>
					<li>لیست قیمت تصویری برای استوری (به‌زودی)</li>
				</ul>
				<a class="sp-btn sp-btn--coral sp-btn--block" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">اول بنیان‌گذار شو: ۳۹۰ هزار</a>
				<p class="sp-small sp-center">پرو اول برای بنیان‌گذارها باز می‌شه.</p>
			</div>
		</div>

		<p class="sp-refund"><?php echo sp_icon( 'refund', 22 ); ?>تا ۷ روز بعد از پرداخت، بدون سؤال پولت برمی‌گرده.</p>
	</div>
</section>

<script>
(function () {
	var btns = document.querySelectorAll('.sp-toggle button');
	var price = document.querySelector('.sp-plan--pro .sp-plan__price');
	Array.prototype.forEach.call(btns, function (b) {
		b.addEventListener('click', function () {
			Array.prototype.forEach.call(btns, function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
			price.innerHTML = price.getAttribute('data-' + b.getAttribute('data-period'));
		});
	});
})();
</script>

<?php get_footer(); ?>
