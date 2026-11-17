</main>
<footer class="sp-footer">
	<div class="sp-wrap">
		<div class="sp-fhelp">
			<?php echo sp_soodi( 'waving', 'sp-fhelp__soodi', 'سودی دست تکان می‌دهد' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="sp-fhelp__txt">
				<p class="sp-fhelp__h">سؤال داری؟</p>
				<p class="sp-fhelp__p">هر چی درباره‌ی قیمت‌گذاری می‌خوای بدونی، بپرس.</p>
			</div>
			<div class="sp-fhelp__btns">
				<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>"><?php echo sp_icon( 'calc', 18 ); ?>ماشین‌حساب رایگان</a>
				<a class="sp-btn sp-fbtn--ghost" href="<?php echo esc_url( sp_url( 'contact' ) ); ?>"><?php echo sp_icon( 'send', 18 ); ?>پیام بده</a>
			</div>
		</div>

		<div class="sp-footer__inner">
			<div class="sp-footer__about">
				<a class="sp-brand sp-brand--light" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo sp_logo_svg( 48 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="sp-brand__name">سودپز</span>
				</a>
				<p class="sp-footer__tag">بپز، بفروش، ضرر نکن.</p>
				<p class="sp-footer__note">قیمت درست برای کیک و غذای خانگی؛ با قیمت امروز مواد و دستمزد خودت.</p>
				<ul class="sp-fbadges">
					<li><?php echo sp_icon( 'server', 16 ); ?>سرور ایرانی</li>
					<li><?php echo sp_icon( 'lock', 16 ); ?>همیشه در دسترس</li>
					<li><?php echo sp_icon( 'check', 16 ); ?>ماشین‌حساب رایگان</li>
				</ul>
			</div>
			<div class="sp-footer__cols sp-footer__links">
				<div>
					<h4>سودپز</h4>
					<nav aria-label="محصول">
						<a href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">ماشین‌حساب رایگان</a>
						<a href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>">قیمت‌ها</a>
						<a href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار</a>
						<a href="<?php echo esc_url( sp_url( 'faq' ) ); ?>">سؤال‌های رایج</a>
					</nav>
				</div>
				<div>
					<h4>مجله</h4>
					<nav aria-label="مجله">
						<a href="<?php echo esc_url( sp_blog_url() ); ?>">همه‌ی مطالب</a>
						<a href="<?php echo esc_url( sp_blog_url( 'cake-pricing' ) ); ?>">قیمت‌گذاری کیک</a>
						<a href="<?php echo esc_url( sp_blog_url( 'home-food' ) ); ?>">قیمت‌گذاری غذای خانگی</a>
					</nav>
				</div>
				<div>
					<h4>ما و قوانین</h4>
					<nav aria-label="درباره و قوانین">
						<a href="<?php echo esc_url( sp_url( 'about' ) ); ?>">درباره‌ی سودپز</a>
						<a href="<?php echo esc_url( sp_url( 'contact' ) ); ?>">تماس با ما</a>
						<a href="<?php echo esc_url( sp_url( 'terms' ) ); ?>">قوانین و مقررات</a>
						<a href="<?php echo esc_url( sp_url( 'privacy' ) ); ?>">حریم خصوصی</a>
					</nav>
				</div>
			</div>
		</div>

		<?php
		$sp_gy = (int) gmdate( 'Y' );
		$sp_jy = ( (int) gmdate( 'n' ) > 3 || ( 3 === (int) gmdate( 'n' ) && (int) gmdate( 'j' ) >= 21 ) ) ? $sp_gy - 621 : $sp_gy - 622;
		?>
		<div class="sp-footer__bar">
			<span>© <?php echo esc_html( sp_fa( $sp_jy, false ) ); ?> سودپز · ساخته‌شده برای آشپزها و قنادهای خونگی ایران</span>
			<a class="sp-totop" href="#" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">بالای صفحه <?php echo sp_icon( 'up', 16 ); ?></a>
		</div>
	</div>
	<div class="sp-footer__mark" aria-hidden="true">سودپز</div>
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
