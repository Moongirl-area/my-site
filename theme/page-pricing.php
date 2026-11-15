<?php
/* صفحه‌ی قیمت‌ها (نامک: pricing) */
get_header();
$sp_s = sp_settings();
?>

<div class="sp-banner">
	<div class="sp-wrap">
		فقط برای ۵۰ نفر: پرو سالی ۳۹۰ هزار تومان، برای همیشه.
		<?php if ( '' !== $sp_s['founders_left'] ) : ?>
			<?php echo esc_html( sp_fa( $sp_s['founders_left'] ) ); ?> جا مونده.
		<?php endif; ?>
		<a href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">جات رو بگیر ←</a>
	</div>
</div>

<section class="sp-section">
	<div class="sp-wrap">
		<p class="sp-script sp-center">قیمت‌ها</p>
		<h1 class="sp-h1 sp-center">ضرر یه کیک ارزون‌فروخته، از اشتراک سودپز بیشتره.</h1>
		<p class="sp-sub sp-center">یه قیمت ساده، چه کیک بپزی چه غذای خانگی.</p>

		<div class="sp-toggle" role="group" aria-label="دوره‌ی پرداخت">
			<button type="button" data-period="yearly" aria-pressed="true">سالانه <span class="sp-pill">۵ ماه مجانی</span></button>
			<button type="button" data-period="monthly" aria-pressed="false">ماهانه</button>
		</div>

		<div class="sp-plans">
			<div class="sp-plan">
				<span class="sp-plan__ico"><?php echo sp_icon( 'calc', 26 ); ?></span>
				<h2>رایگان</h2>
				<p class="sp-plan__for">برای اینکه ببینی به کارت میاد</p>
				<p class="sp-plan__price">مجانی <small>برای همیشه</small></p>
				<ul>
					<li>ماشین‌حساب عمومی قیمت کیک و غذا</li>
					<li>۱۴ روز پرو رایگان بعد از ثبت‌نام</li>
					<li>بعدش یه محصول، برای همیشه رایگان</li>
				</ul>
				<a class="sp-btn sp-btn--line sp-btn--block" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">همین الان حساب کن</a>
			</div>

			<div class="sp-plan sp-plan--pro">
				<span class="sp-badge">پیشنهاد ما</span>
				<span class="sp-plan__ico"><?php echo sp_icon( 'cake', 26 ); ?></span>
				<h2>پرو</h2>
				<p class="sp-plan__for">برای وقتی هر هفته سفارش داری</p>
				<p class="sp-plan__price" data-yearly="۶۹۰ هزار <small>تومان در سال</small>" data-monthly="۹۹ هزار <small>تومان در ماه</small>">۶۹۰ هزار <small>تومان در سال</small></p>
				<ul>
					<li>هر چند تا کیک و غذا که بخوای</li>
					<li>قیمت مرجع هفتگی حدود ۴۰ ماده، از خامه تا گوشت و برنج</li>
					<li>یه ماده گرون شد؟ قیمت همه‌چی با یه کلیک درست می‌شه</li>
					<li>قیمت هر کیلو، هر عدد یا هر پرس</li>
					<li>هشدار وقتی زیر قیمت تمام‌شده می‌فروشی</li>
					<li>دستورهای آماده‌ی کیک و غذای خونگی، با مقدار مواد</li>
					<li>عکس لیست قیمت و منوی هفتگی برای استوری (به‌زودی)</li>
				</ul>
				<a class="sp-btn sp-btn--coral sp-btn--block" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">اول بنیان‌گذار شو: ۳۹۰ هزار</a>
				<p class="sp-small sp-center">پرو اول برای این ۵۰ نفر باز می‌شه.</p>
			</div>
		</div>

		<div class="sp-worth">
			<div class="sp-worth__card"><span class="sp-feature__ico" style="--c:#E8673C"><?php echo sp_icon( 'cake', 22 ); ?></span><p><b>اگه کیک می‌پزی:</b> هر کیک فقط ۶۰ هزار تومان زیر قیمت تمام‌شده؟ با ۱۲ سفارش، پول یه سال اشتراک از جیبت رفته.</p></div>
			<div class="sp-worth__card"><span class="sp-feature__ico" style="--c:#3F8A4E"><?php echo sp_icon( 'pot', 22 ); ?></span><p><b>اگه غذا می‌پزی:</b> هر پرس فقط ۲۰ هزار تومان کم؟ با ۵۰ پرس در هفته، هر هفته ۱ میلیون تومان از جیبت می‌ره.</p></div>
		</div>
		<p class="sp-small sp-center">عددها نمونه‌ان. عدد واقعی خودت رو با ماشین‌حساب رایگان ببین.</p>

		<p class="sp-refund"><?php echo sp_icon( 'refund', 22 ); ?>تا ۷ روز بعد از پرداخت، بدون هیچ سؤالی پولت برمی‌گرده.</p>
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
