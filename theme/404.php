<?php get_header(); ?>
<section class="sp-404">
	<div class="sp-wrap">
		<p class="sp-404__num">۴۰۴</p>
		<h1 class="sp-h1">این صفحه سوخت!</h1>
		<p class="sp-sub">صفحه‌ای که دنبالش بودی پیدا نشد. شاید آدرسش عوض شده.</p>
		<div class="sp-actions">
			<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>"><?php echo sp_icon( 'calc', 20 ); ?>ماشین‌حساب رایگان</a>
			<a class="sp-btn sp-btn--line" href="<?php echo esc_url( home_url( '/' ) ); ?>">صفحه‌ی اصلی</a>
		</div>
	</div>
</section>
<?php get_footer(); ?>
