<?php
/* ساختن رمز تازه (نامک: reset-password) */
get_header();
$sp_key   = isset( $_GET['key'] ) ? (string) wp_unslash( $_GET['key'] ) : '';
$sp_login = isset( $_GET['login'] ) ? (string) wp_unslash( $_GET['login'] ) : '';
$sp_user  = ( $sp_key && $sp_login ) ? check_password_reset_key( $sp_key, $sp_login ) : new WP_Error( 'no' );
$sp_err   = isset( $_GET['err'] ) ? sanitize_key( wp_unslash( $_GET['err'] ) ) : '';
?>
<section class="sp-authx">
	<div class="sp-authx__card sp-authx__card--one">
		<div class="sp-authx__main">
			<?php if ( is_wp_error( $sp_user ) ) : ?>
				<h1 class="sp-authx__h1">این لینک کار نمی‌کنه</h1>
				<p class="sp-authx__sub">لینک ساختن رمز یا منقضی شده یا قبلاً ازش استفاده شده. یه بار دیگه درخواست بده، چند لحظه‌ای بیشتر طول نمی‌کشه.</p>
				<a class="sp-btn sp-btn--coral sp-btn--block" href="<?php echo esc_url( sp_url( 'lostpassword' ) ); ?>">درخواست لینک تازه</a>
			<?php else : ?>
				<h1 class="sp-authx__h1">رمز تازه بساز</h1>
				<p class="sp-authx__sub">برای حساب <b><?php echo esc_html( $sp_user->display_name ); ?></b>. یه رمز حداقل ۶ حرفی بنویس.</p>
				<?php if ( 'short' === $sp_err ) : ?>
					<div class="sp-note sp-note--bad">رمز باید حداقل ۶ حرف باشه.</div>
				<?php elseif ( 'match' === $sp_err ) : ?>
					<div class="sp-note sp-note--bad">دو تا رمزی که نوشتی یکی نیستن.</div>
				<?php endif; ?>
				<form class="sp-payf" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'sp_resetpass' ); ?>
					<input type="hidden" name="action" value="sp_resetpass">
					<input type="hidden" name="key" value="<?php echo esc_attr( $sp_key ); ?>">
					<input type="hidden" name="login" value="<?php echo esc_attr( $sp_login ); ?>">
					<label>رمز تازه<input type="password" name="pass1" minlength="6" required autofocus></label>
					<label>یه بار دیگه بنویسش<input type="password" name="pass2" minlength="6" required></label>
					<button class="sp-btn sp-btn--coral sp-btn--block" type="submit">ذخیره‌ی رمز</button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
