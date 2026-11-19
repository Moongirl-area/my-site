<?php
/**
 * پرداخت کارت‌به‌کارت تا وقتی درگاه آنلاین وصل شود.
 * کاربر در میز کار شماره کارت را می‌بیند، واریز می‌کند و رسیدش را می‌فرستد؛
 * مدیر در پیشخوان تأیید می‌کند و پرو همان لحظه فعال می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** پلن‌های خریدنی: کلید => [اسم، روز، کلید قیمت در تنظیمات، توضیح]. */
function sp_pay_plans() {
	$s = sp_settings();
	return array(
		'founder' => array( 'name' => 'بنیان‌گذار (یک سال پرو)', 'days' => 365, 'price' => (int) $s['price_founder'], 'note' => 'قیمت قفل‌شده برای ۵۰ نفر اول؛ تا هر وقت تمدید کنی همین قیمت می‌مونه.' ),
		'year'    => array( 'name' => 'پرو، یک‌ساله', 'days' => 365, 'price' => (int) $s['price_year'], 'note' => 'معادل ماهی کمتر از ۶۰ هزار تومان.' ),
		'month'   => array( 'name' => 'پرو، یک‌ماهه', 'days' => 30, 'price' => (int) $s['price_month'], 'note' => 'برای امتحان کردن؛ هر وقت خواستی قطعش کن.' ),
	);
}

function sp_pay_plan( $key ) {
	$p = sp_pay_plans();
	return $p[ $key ] ?? $p['year'];
}

/* ---------- نوع محتوای «پرداخت» ---------- */
add_action( 'init', function () {
	register_post_type( 'sp_pay', array(
		'labels'          => array( 'name' => 'پرداخت‌ها', 'singular_name' => 'پرداخت' ),
		'public'          => false,
		'show_ui'         => false,
		'supports'        => array( 'title', 'author' ),
		'capability_type' => 'post',
	) );
} );

/** پرداخت‌های یک کاربر. */
function sp_user_pays( $uid ) {
	return get_posts( array(
		'post_type'   => 'sp_pay',
		'author'      => $uid,
		'numberposts' => 20,
		'post_status' => 'any',
	) );
}

function sp_pay_status( $id ) {
	$s = get_post_meta( $id, 'sp_status', true );
	return $s ? $s : 'pending';
}

function sp_pay_status_label( $s ) {
	$m = array( 'pending' => 'در انتظار تأیید', 'ok' => 'تأیید شد', 'no' => 'تأیید نشد' );
	return $m[ $s ] ?? $s;
}

/** آیا کاربر رسید در انتظار تأیید دارد؟ */
function sp_has_pending_pay( $uid ) {
	foreach ( sp_user_pays( $uid ) as $p ) {
		if ( 'pending' === sp_pay_status( $p->ID ) ) {
			return $p;
		}
	}
	return null;
}

/* ---------- فرستادن رسید ---------- */
add_action( 'admin_post_sp_pay_submit', 'sp_pay_submit' );
function sp_pay_submit() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( sp_url( 'login' ) );
		exit;
	}
	check_admin_referer( 'sp_pay_submit' );
	$uid  = get_current_user_id();
	$back = sp_dash_url();
	$key  = sanitize_key( wp_unslash( $_POST['plan'] ?? 'year' ) );
	$plan = sp_pay_plan( $key );
	$ref  = sanitize_text_field( sp_en_digits( wp_unslash( $_POST['ref'] ?? '' ) ) );
	$amt  = (int) preg_replace( '/[^0-9]/', '', sp_en_digits( wp_unslash( $_POST['amount'] ?? '' ) ) );

	$fail = function ( $why ) use ( $back ) {
		wp_safe_redirect( add_query_arg( 'payerr', rawurlencode( $why ), $back ) . '#pay' );
		exit;
	};
	if ( $amt < 1000 ) {
		$fail( 'مبلغ واریزی رو درست بنویس.' );
	}
	if ( mb_strlen( $ref ) < 4 ) {
		$fail( 'شماره پیگیری یا ۴ رقم آخر کارتت رو بنویس.' );
	}

	// فایل رسید: عکس یا PDF، حداکثر ۵ مگابایت، با اسم تصادفی تا لینکش حدس‌زدنی نباشد.
	$file = $_FILES['receipt'] ?? null;
	if ( empty( $file['name'] ) || ! empty( $file['error'] ) ) {
		$fail( 'عکس رسید رو هم بفرست.' );
	}
	if ( (int) $file['size'] > 5 * MB_IN_BYTES ) {
		$fail( 'حجم فایل باید کمتر از ۵ مگابایت باشه.' );
	}
	$type = wp_check_filetype( $file['name'] );
	if ( ! in_array( strtolower( (string) $type['ext'] ), array( 'jpg', 'jpeg', 'png', 'webp', 'pdf' ), true ) ) {
		$fail( 'فقط عکس (JPG، PNG) یا PDF قبول می‌شه.' );
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	$up = wp_handle_upload( $file, array(
		'test_form'                => false,
		'unique_filename_callback' => function ( $dir, $name, $ext ) {
			return 'rcpt-' . wp_generate_password( 20, false, false ) . $ext;
		},
	) );
	if ( ! empty( $up['error'] ) ) {
		$fail( 'فایل آپلود نشد؛ دوباره امتحان کن.' );
	}

	$u  = get_userdata( $uid );
	$id = wp_insert_post( array(
		'post_type'   => 'sp_pay',
		'post_status' => 'private',
		'post_author' => $uid,
		'post_title'  => $u->display_name . ' — ' . $plan['name'],
	) );
	if ( is_wp_error( $id ) || ! $id ) {
		$fail( 'ثبت نشد؛ دوباره امتحان کن.' );
	}
	update_post_meta( $id, 'sp_plan', $key );
	update_post_meta( $id, 'sp_amount', $amt );
	update_post_meta( $id, 'sp_ref', $ref );
	update_post_meta( $id, 'sp_file', esc_url_raw( $up['url'] ) );
	update_post_meta( $id, 'sp_path', $up['file'] );
	update_post_meta( $id, 'sp_status', 'pending' );

	$admin = get_option( 'admin_email' );
	if ( $admin ) {
		wp_mail(
			$admin,
			'سودپز: رسید تازه از ' . $u->display_name,
			$u->display_name . ' برای «' . $plan['name'] . '» مبلغ ' . number_format( $amt ) . " تومان واریز کرده.\n" .
			'شماره پیگیری: ' . $ref . "\n" .
			'تأیید: ' . admin_url( 'admin.php?page=soodpaz-pays' )
		);
	}
	wp_safe_redirect( add_query_arg( 'paid', '1', $back ) . '#pay' );
	exit;
}

/* ---------- تأیید یا رد در پیشخوان ---------- */
add_action( 'admin_post_sp_pay_act', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'دسترسی ندارید.' );
	}
	check_admin_referer( 'sp_pay_act' );
	$id  = (int) ( $_POST['id'] ?? 0 );
	$act = sanitize_key( wp_unslash( $_POST['act'] ?? '' ) );
	$p   = $id ? get_post( $id ) : null;
	if ( ! $p || 'sp_pay' !== $p->post_type ) {
		wp_die( 'پیدا نشد.' );
	}
	if ( 'ok' === $act ) {
		$plan = sp_pay_plan( (string) get_post_meta( $id, 'sp_plan', true ) );
		$days = isset( $_POST['days'] ) && (int) $_POST['days'] > 0 ? (int) $_POST['days'] : (int) $plan['days'];
		$uid  = (int) $p->post_author;
		$from = max( time(), (int) get_user_meta( $uid, 'sp_pro_until', true ) );
		update_user_meta( $uid, 'sp_pro_until', $from + $days * DAY_IN_SECONDS );
		delete_user_meta( $uid, 'sp_price_snap' );
		update_post_meta( $id, 'sp_status', 'ok' );
		update_post_meta( $id, 'sp_done', current_time( 'Y-m-d H:i' ) );
		$u = get_userdata( $uid );
		if ( $u && $u->user_email && false === strpos( $u->user_email, '@users.soodpaz.com' ) ) {
			wp_mail( $u->user_email, 'سودپز: پرو فعال شد', 'سلام ' . $u->display_name . "،\nپرداختت تأیید شد و حساب پروت فعال شد. خوش اومدی!\n" . sp_dash_url() );
		}
	} elseif ( 'no' === $act ) {
		update_post_meta( $id, 'sp_status', 'no' );
		update_post_meta( $id, 'sp_why', sanitize_text_field( wp_unslash( $_POST['why'] ?? '' ) ) );
		update_post_meta( $id, 'sp_done', current_time( 'Y-m-d H:i' ) );
	} elseif ( 'del' === $act ) {
		$path = (string) get_post_meta( $id, 'sp_path', true );
		if ( $path && file_exists( $path ) ) {
			wp_delete_file( $path );
		}
		wp_delete_post( $id, true );
	}
	wp_safe_redirect( admin_url( 'admin.php?page=soodpaz-pays' ) );
	exit;
} );

/* ---------- صفحه‌ی «پرداخت‌ها» در پیشخوان ---------- */
add_action( 'admin_menu', function () {
	$pend  = count( get_posts( array( 'post_type' => 'sp_pay', 'post_status' => 'any', 'numberposts' => 50, 'meta_key' => 'sp_status', 'meta_value' => 'pending' ) ) );
	$label = 'پرداخت‌ها' . ( $pend ? ' <span class="awaiting-mod">' . sp_fa( $pend ) . '</span>' : '' );
	add_submenu_page( 'soodpaz', 'پرداخت‌ها', $label, 'manage_options', 'soodpaz-pays', 'sp_pays_page', 1 );
}, 11 );

function sp_pays_page() {
	$pays = get_posts( array( 'post_type' => 'sp_pay', 'post_status' => 'any', 'numberposts' => 200 ) );
	?>
	<div class="wrap">
		<h1>پرداخت‌های کارت‌به‌کارت</h1>
		<p>رسید را باز کن، با حساب بانکی خودت چک کن و بعد «تأیید و فعال‌سازی» را بزن. با تأیید، پرو همان لحظه برای کاربر فعال می‌شود.</p>
		<table class="widefat striped">
			<thead><tr><th>کاربر</th><th>پلن</th><th>مبلغ</th><th>پیگیری</th><th>رسید</th><th>تاریخ</th><th>وضعیت</th><th>کار</th></tr></thead>
			<tbody>
			<?php if ( ! $pays ) : ?>
				<tr><td colspan="8">هنوز رسیدی نیامده.</td></tr>
			<?php endif; ?>
			<?php
			foreach ( $pays as $p ) :
				$st   = sp_pay_status( $p->ID );
				$u    = get_userdata( $p->post_author );
				$plan = sp_pay_plan( (string) get_post_meta( $p->ID, 'sp_plan', true ) );
				?>
				<tr<?php echo 'pending' === $st ? ' style="background:#FFF8E5"' : ''; ?>>
					<td>
						<b><?php echo esc_html( $u ? $u->display_name : '—' ); ?></b><br>
						<small><?php echo esc_html( (string) get_user_meta( $p->post_author, 'sp_mobile', true ) ); ?></small><br>
						<small><a href="<?php echo esc_url( get_edit_user_link( $p->post_author ) ); ?>">پرونده‌ی کاربر</a> · <?php echo esc_html( sp_plan_label( (int) $p->post_author ) ); ?></small>
					</td>
					<td><?php echo esc_html( $plan['name'] ); ?></td>
					<td><?php echo esc_html( number_format( (int) get_post_meta( $p->ID, 'sp_amount', true ) ) ); ?> تومان<br>
						<small>انتظار: <?php echo esc_html( number_format( (int) $plan['price'] ) ); ?></small></td>
					<td style="direction:ltr"><?php echo esc_html( (string) get_post_meta( $p->ID, 'sp_ref', true ) ); ?></td>
					<td><a href="<?php echo esc_url( (string) get_post_meta( $p->ID, 'sp_file', true ) ); ?>" target="_blank" rel="noopener">دیدن رسید</a></td>
					<td><?php echo esc_html( sp_jdate( get_the_date( 'Y-m-d', $p ) ) . ' ' . get_the_date( 'H:i', $p ) ); ?></td>
					<td>
						<b><?php echo esc_html( sp_pay_status_label( $st ) ); ?></b>
						<?php if ( 'no' === $st && get_post_meta( $p->ID, 'sp_why', true ) ) : ?>
							<br><small><?php echo esc_html( (string) get_post_meta( $p->ID, 'sp_why', true ) ); ?></small>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( 'pending' === $st ) : ?>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-bottom:6px">
								<?php wp_nonce_field( 'sp_pay_act' ); ?>
								<input type="hidden" name="action" value="sp_pay_act">
								<input type="hidden" name="id" value="<?php echo (int) $p->ID; ?>">
								<input type="hidden" name="act" value="ok">
								<input type="number" name="days" value="<?php echo (int) $plan['days']; ?>" style="width:70px"> روز
								<button class="button button-primary">تأیید و فعال‌سازی</button>
							</form>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
								<?php wp_nonce_field( 'sp_pay_act' ); ?>
								<input type="hidden" name="action" value="sp_pay_act">
								<input type="hidden" name="id" value="<?php echo (int) $p->ID; ?>">
								<input type="hidden" name="act" value="no">
								<input type="text" name="why" placeholder="دلیل (به کاربر نشان داده می‌شود)" style="width:180px">
								<button class="button">رد کن</button>
							</form>
						<?php else : ?>
							<small><?php echo esc_html( (string) get_post_meta( $p->ID, 'sp_done', true ) ); ?></small>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('حذف شود؟')">
								<?php wp_nonce_field( 'sp_pay_act' ); ?>
								<input type="hidden" name="action" value="sp_pay_act">
								<input type="hidden" name="id" value="<?php echo (int) $p->ID; ?>">
								<input type="hidden" name="act" value="del">
								<button class="button-link delete">حذف</button>
							</form>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/** آدرس خرید: عضو → میز کار (تب پرداخت)، مهمان → عضویت و بعد همان‌جا. */
function sp_buy_url( $plan = 'year' ) {
	$to = add_query_arg( 'plan', $plan, sp_dash_url() ) . '#pay';
	return is_user_logged_in() ? $to : add_query_arg( 'redirect_to', rawurlencode( $to ), sp_url( 'register' ) );
}

/** شماره کارت را ۴رقمی و با رقم فارسی نشان می‌دهد. */
function sp_fa_digits_group( $num ) {
	$n = preg_replace( '/[^0-9]/', '', sp_en_digits( (string) $num ) );
	return sp_fa_num( implode( ' ', str_split( $n, 4 ) ) );
}

/** رقم‌های یک رشته را فارسی می‌کند (بدون جداکننده‌ی هزارگان). */
function sp_fa_num( $s ) {
	return str_replace( range( 0, 9 ), array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ), (string) $s );
}
