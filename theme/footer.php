</main>
<footer class="sp-footer">
	<div class="sp-wrap sp-footer__inner">
		<div>
			<a class="sp-brand sp-brand--light" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo sp_logo_svg( 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="sp-brand__name">سودپز</span>
			</a>
			<p class="sp-footer__tag">بپز، بفروش، ضرر نکن.</p>
			<p class="sp-footer__note">روی سرور ایرانی؛ موقع قطعی اینترنت هم بازه.</p>
		</div>
		<nav class="sp-footer__links" aria-label="پیوندهای پایین صفحه">
			<a href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">ماشین‌حساب رایگان</a>
			<a href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>">قیمت‌ها</a>
			<a href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار</a>
			<a href="<?php echo esc_url( sp_url( 'founders' ) . '#join' ); ?>">تماس</a>
		</nav>
	</div>
	<div class="sp-wrap sp-footer__copy">© سودپز</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
