</main>
<footer class="sp-footer">
	<div class="sp-wrap sp-footer__inner">
		<div>
			<a class="sp-brand sp-brand--light" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo sp_logo_svg( 42 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="sp-brand__name">سودپز</span>
			</a>
			<p class="sp-footer__tag">بپز، بفروش، ضرر نکن.</p>
			<p class="sp-footer__note">قیمت‌گذاری کیک و غذای خانگی. روی سرور ایرانی؛ موقع قطعی اینترنت هم بازه.</p>
		</div>
		<div class="sp-footer__cols sp-footer__links">
			<div>
				<h4>سودپز</h4>
				<nav aria-label="محصول">
					<a href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">ماشین‌حساب رایگان</a>
					<a href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>">قیمت‌ها</a>
					<a href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار</a>
				</nav>
			</div>
			<div>
				<h4>بیشتر بدون</h4>
				<nav aria-label="درباره">
					<a href="<?php echo esc_url( sp_blog_url() ); ?>">مجله‌ی سودپز</a>
					<a href="<?php echo esc_url( sp_blog_url( 'cake-pricing' ) ); ?>">قیمت‌گذاری کیک</a>
					<a href="<?php echo esc_url( sp_blog_url( 'home-food' ) ); ?>">قیمت‌گذاری غذای خانگی</a>
					<a href="<?php echo esc_url( sp_url( 'faq' ) ); ?>">سؤال‌های رایج</a>
					<a href="<?php echo esc_url( sp_url( 'about' ) ); ?>">درباره‌ی سودپز</a>
					<a href="<?php echo esc_url( sp_url( 'contact' ) ); ?>">تماس با ما</a>
				</nav>
			</div>
			<div>
				<h4>قوانین</h4>
				<nav aria-label="قوانین">
					<a href="<?php echo esc_url( sp_url( 'terms' ) ); ?>">قوانین و مقررات</a>
					<a href="<?php echo esc_url( sp_url( 'privacy' ) ); ?>">حریم خصوصی</a>
				</nav>
			</div>
		</div>
	</div>
	<div class="sp-wrap sp-footer__copy">© سودپز</div>
</footer>
<script>
/* سوئیچ شیرینی | غذا */
(function () {
	var root = document.documentElement;
	function sync() {
		var k = root.getAttribute('data-k');
		Array.prototype.forEach.call(document.querySelectorAll('[data-set-k]'), function (b) {
			b.setAttribute('aria-pressed', b.getAttribute('data-set-k') === k ? 'true' : 'false');
		});
	}
	window.spSetKitchen = function (k) {
		k = k === 'food' ? 'food' : 'sweet';
		root.setAttribute('data-k', k);
		try { localStorage.setItem('sp_k', k); } catch (e) {}
		sync();
		document.dispatchEvent(new CustomEvent('sp:kitchen', { detail: k }));
	};
	document.addEventListener('click', function (e) {
		var b = e.target.closest ? e.target.closest('[data-set-k]') : null;
		if (b) { window.spSetKitchen(b.getAttribute('data-set-k')); }
	});
	sync();
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
