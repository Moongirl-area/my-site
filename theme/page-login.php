<?php
/* ورود (نامک: login) — فرم ورود Gravity Forms User Registration */
get_header();
$sp_to = isset( $_GET['redirect_to'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ), '' ) : '';
$sp_to = $sp_to ? $sp_to : sp_url( 'calculator' );
?>
<section class="sp-page-head sp-page-head--soodi">
	<div class="sp-wrap">
		<?php echo sp_soodi( 'waving', 'sp-soodi--head', 'سودی دست تکان می‌دهد', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<p class="sp-script">ورود</p>
		<h1 class="sp-h1">خوش برگشتی!</h1>
		<p class="sp-sub">با شماره موبایل یا ایمیل و رمزت وارد شو.</p>
	</div>
</section>
<section class="sp-section sp-section--tight">
	<div class="sp-wrap sp-auth">
		<div class="sp-auth__card">
			<?php if ( is_user_logged_in() ) : ?>
				<p class="sp-auth__msg">الان وارد حسابت هستی.</p>
				<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( $sp_to ); ?>">برو به ماشین‌حساب</a>
				<p class="sp-auth__alt"><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">خروج از حساب</a></p>
			<?php elseif ( shortcode_exists( 'gravityform' ) && function_exists( 'gf_user_registration' ) ) : ?>
				<?php echo do_shortcode( '[gravityform action="login" description="false" title="false" login_redirect="' . esc_url( $sp_to ) . '" registration_link_text="" forgot_password_text="رمزت یادت رفته؟"]' ); // phpcs:ignore ?>
				<p class="sp-auth__alt">هنوز عضو نیستی؟ <a href="<?php echo esc_url( add_query_arg( 'redirect_to', rawurlencode( $sp_to ), sp_url( 'register' ) ) ); ?>">عضو شو، رایگانه</a></p>
			<?php else : ?>
				<?php wp_login_form( array( 'redirect' => $sp_to, 'label_username' => 'موبایل یا ایمیل', 'label_password' => 'رمز عبور', 'label_remember' => 'مرا یادت باشه', 'label_log_in' => 'ورود' ) ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
