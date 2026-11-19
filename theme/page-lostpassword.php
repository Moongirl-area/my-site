<?php
/* فراموشی رمز (نامک: lostpassword) */
get_header();
$sp_err  = isset( $_GET['err'] ) ? sanitize_key( wp_unslash( $_GET['err'] ) ) : '';
$sp_sent = isset( $_GET['sent'] );
$sp_msgs = array(
	'empty'   => 'شماره موبایل یا ایمیلت رو بنویس.',
	'nouser'  => 'با این شماره یا ایمیل حسابی پیدا نکردیم. املاش رو یه بار دیگه چک کن.',
	'noemail' => 'حسابت ایمیل نداره، برای همین نمی‌تونیم لینک بفرستیم. به پشتیبانی پیام بده تا دستی برات درستش کنیم.',
	'badkey'  => 'این لینک منقضی شده. یه بار دیگه درخواست بده.',
	'failed'  => 'ایمیل فرستاده نشد. چند دقیقه بعد دوباره امتحان کن.',
);
?>
<section class="sp-authx">
	<div class="sp-authx__card sp-authx__card--one">
		<div class="sp-authx__main">
			<h1 class="sp-authx__h1">رمزت یادت رفته؟</h1>
			<?php if ( $sp_sent ) : ?>
				<p class="sp-authx__sub">اگه حسابی با این مشخصات باشه، یه ایمیل برات فرستادیم. روی لینک توی ایمیل بزن و رمز تازه بساز. اگه چند دقیقه بعد نیومد، پوشه‌ی اسپم رو هم نگاه کن.</p>
				<a class="sp-btn sp-btn--paper sp-btn--block" href="<?php echo esc_url( sp_url( 'login' ) ); ?>">برگرد به صفحه‌ی ورود</a>
			<?php else : ?>
				<p class="sp-authx__sub">شماره موبایل یا ایمیلی که باهاش عضو شدی رو بنویس تا لینک ساختن رمز تازه برات بفرستیم.</p>
				<?php if ( $sp_err ) : ?>
					<div class="sp-note sp-note--bad"><?php echo esc_html( $sp_msgs[ $sp_err ] ?? 'یه مشکلی پیش اومد. دوباره امتحان کن.' ); ?></div>
				<?php endif; ?>
				<form class="sp-payf" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'sp_lostpass' ); ?>
					<input type="hidden" name="action" value="sp_lostpass">
					<label>موبایل یا ایمیل
						<input type="text" name="who" dir="ltr" placeholder="۰۹۱۲۳۴۵۶۷۸۹ یا ایمیل" required autofocus>
					</label>
					<button class="sp-btn sp-btn--coral sp-btn--block" type="submit">لینک رو برام بفرست</button>
				</form>
				<p class="sp-auth__alt">رمزت یادته؟ <a href="<?php echo esc_url( sp_url( 'login' ) ); ?>">وارد شو</a></p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
