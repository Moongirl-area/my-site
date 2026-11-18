<?php
/* عضویت (نامک: register) — فرم از Gravity Forms */
get_header();
$sp_to = isset( $_GET['redirect_to'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ), '' ) : '';
?>
<section class="sp-page-head sp-page-head--soodi">
	<div class="sp-wrap">
		<?php echo sp_soodi( 'happy', 'sp-soodi--head', 'سودی خوشحال است', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<p class="sp-script">عضویت رایگان</p>
		<h1 class="sp-h1">دستورهات رو نگه دار</h1>
		<p class="sp-sub">عضو شو تا دستورها و قیمت‌هایی که می‌نویسی ذخیره بشه و از هر گوشی و لپ‌تاپی بهشون برسی.</p>
	</div>
</section>
<section class="sp-section sp-section--tight">
	<div class="sp-wrap sp-auth">
		<div class="sp-auth__card">
			<?php if ( is_user_logged_in() ) : ?>
				<p class="sp-auth__msg">الان وارد حسابت هستی.</p>
				<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( $sp_to ? $sp_to : sp_url( 'calculator' ) ); ?>">برو به ماشین‌حساب</a>
			<?php elseif ( function_exists( 'gravity_form' ) && sp_reg_form_id() ) : ?>
				<?php gravity_form( sp_reg_form_id(), false, false, false, null, false ); ?>
				<p class="sp-auth__alt">قبلاً عضو شدی؟ <a href="<?php echo esc_url( $sp_to ? add_query_arg( 'redirect_to', rawurlencode( $sp_to ), sp_url( 'login' ) ) : sp_url( 'login' ) ); ?>">وارد شو</a></p>
			<?php else : ?>
				<p class="sp-auth__msg">عضویت به‌زودی باز می‌شه.</p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
