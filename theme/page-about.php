<?php
/* درباره‌ی سودپز (نامک: about) */
get_header();
?>
<section class="sp-page-head">
	<div class="sp-wrap">
		<p class="sp-script">رفیق حسابگر آشپزخونه</p>
		<h1 class="sp-h1">چرا سودپز رو ساختیم</h1>
	</div>
</section>
<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<div class="sp-prose">
			<p class="sp-lead">خیلی از قنادهای خانگی هر هفته کیک‌های قشنگ می‌پزن و می‌فروشن، ولی آخر ماه نمی‌دونن واقعاً چقدر سود کردن. با تورم این روزها، قیمتی که دو ماه پیش بستی، امروز ممکنه ضرر باشه.</p>
			<p>بیشترشون هم مشکلشون بلد نبودن نیست؛ وقت ندارن. بین سفارش گرفتن، خرید مواد، پختن و تحویل دادن، کی حوصله داره هر هفته برای ده تا کیک دوباره حساب‌کتاب کنه؟</p>
			<p>سودپز برای همین ساخته شده: یه بار دستورت رو وارد می‌کنی، و از اون به بعد هر وقت کره یا خامه گرون شد، قیمت همه‌ی کیک‌هات خودش درست می‌شه. شعارمون هم همینه: <b>بپز، بفروش، ضرر نکن.</b></p>
		</div>
		<div class="sp-values">
			<div class="sp-value"><span class="sp-feature__ico" style="--c:#E8673C"><?php echo sp_icon( 'phone', 24 ); ?></span><h3>ساده، روی گوشی</h3><p>بدون اکسل و فرمول. همه‌چی برای گوشی ساخته شده.</p></div>
			<div class="sp-value"><span class="sp-feature__ico" style="--c:#149C98"><?php echo sp_icon( 'lock', 24 ); ?></span><h3>دستورهات مال خودته</h3><p>دستورها و قیمت‌هات هیچ‌جا بدون اجازه‌ی تو نمی‌ره.</p></div>
			<div class="sp-value"><span class="sp-feature__ico" style="--c:#E9A23B"><?php echo sp_icon( 'server', 24 ); ?></span><h3>همیشه در دسترس</h3><p>روی سرور ایرانی، موقع قطعی اینترنت هم بازه.</p></div>
		</div>
		<div class="sp-prose">
			<h2>با هم می‌سازیمش</h2>
			<p>نسخه‌ی کامل سودپز اول برای ۵۰ عضو بنیان‌گذار باز می‌شه، و هر امکان جدید رو با نظر همین‌ها می‌سازیم. اگه قناد خانگی هستی و حرفی، دردی یا پیشنهادی داری، <a href="<?php echo esc_url( sp_url( 'contact' ) ); ?>">خوشحال می‌شیم بشنویم</a>.</p>
			<?php while ( have_posts() ) : the_post(); the_content(); endwhile; // متنی که در پیشخوان به این صفحه اضافه کنی، اینجا می‌آید ?>
		</div>
		<?php sp_cta_box(); ?>
	</div>
</section>
<?php get_footer(); ?>
