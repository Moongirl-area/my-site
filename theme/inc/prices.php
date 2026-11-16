<?php
/**
 * قیمت مرجع مواد، دستورهای ماشین‌حساب رایگان، و صفحه‌ی تنظیمات در پیشخوان.
 * قیمت‌ها از پیشخوان وردپرس عوض می‌شوند: پیشخوان ← سودپز.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** منبع‌های قیمت مرجع. */
function sp_price_sources() {
	return array(
		'mayadin' => 'میادین میوه و تره‌بار تهران',
		'emalls'  => 'میانگین فروشگاه‌های آنلاین',
		'sci'     => 'مرکز آمار ایران',
		'manual'  => 'بررسی سودپز',
	);
}

/**
 * قیمت مرجع تأییدشده‌ی هر ماده: array( p => تومان برای هر واحد خرید، src => منبع، date => Y-m-d ).
 * نسخه‌ی قدیمی فقط عدد ذخیره می‌کرد؛ آن هم خوانده می‌شود.
 */
function sp_price_info() {
	$saved = get_option( 'sp_prices', array() );
	$s     = get_option( 'sp_settings', array() );
	$out   = array();
	foreach ( sp_ingredients() as $key => $ing ) {
		$v = isset( $saved[ $key ] ) ? $saved[ $key ] : 0;
		if ( is_array( $v ) ) {
			$out[ $key ] = wp_parse_args( $v, array( 'p' => 0, 'src' => 'manual', 'date' => '' ) );
			$out[ $key ]['p'] = (float) $out[ $key ]['p'];
		} else {
			$out[ $key ] = array( 'p' => (float) $v, 'src' => 'manual', 'date' => '' );
		}
	}
	return $out;
}

/** فقط عدد قیمت‌ها (برای کدهای قدیمی‌تر). */
function sp_current_prices() {
	return array_map( function ( $i ) { return $i['p']; }, sp_price_info() );
}

/** آیا قیمت همه‌ی مواد دستورهای آماده‌ی یک آشپزخونه وارد شده؟ بدون آرگومان: هر دو. */
function sp_prices_ready( $kitchen = '' ) {
	$prices = sp_current_prices();
	foreach ( sp_recipes() as $r ) {
		if ( $kitchen && $r['kitchen'] !== $kitchen ) {
			continue;
		}
		foreach ( array_keys( $r['items'] ) as $key ) {
			if ( empty( $prices[ $key ] ) ) {
				return false;
			}
		}
	}
	return true;
}

/** تازه‌ترین تاریخ قیمت مرجع (شمسی، برای نمایش)؛ خالی اگر هیچ. */
function sp_prices_latest_date() {
	$max = '';
	foreach ( sp_price_info() as $i ) {
		if ( $i['p'] && $i['date'] > $max ) {
			$max = $i['date'];
		}
	}
	return $max ? sp_jdate( strtotime( $max . ' 12:00:00' ) ) : '';
}

function sp_settings() {
	return wp_parse_args( get_option( 'sp_settings', array() ), array(
		'wage'          => 0,   // دستمزد ساعتی پیش‌فرض
		'oven'          => 0,   // هزینه‌ی گاز/برق هر ساعت فر
		'packaging'     => 0,   // جعبه و بسته‌بندی هر کیک
		'stove'         => 0,   // هزینه‌ی گاز هر ساعت اجاق (غذا)
		'container'     => 0,   // ظرف و بسته‌بندی هر پرس غذا
		'margin'        => 30,  // درصد سود هدف روی قیمت فروش
		'prices_date'   => '',  // (قدیمی) تاریخ دستی قیمت‌ها
		'founders_left' => '',  // تعداد واقعی جای خالی بنیان‌گذار؛ خالی = نمایش داده نشود
	) );
}

/** داده‌ای که به ماشین‌حساب (جاوااسکریپت) داده می‌شود. */
function sp_calculator_data() {
	$s     = sp_settings();
	$info  = sp_price_info();
	$srcs  = sp_price_sources();
	$ings  = array();
	foreach ( sp_ingredients() as $key => $ing ) {
		$pi            = $info[ $key ];
		$ing['p']      = $pi['p'];
		$ing['src']    = $pi['p'] ? ( $srcs[ $pi['src'] ] ?? $pi['src'] ) : '';
		$ing['date']   = $pi['p'] && $pi['date'] ? sp_jdate( strtotime( $pi['date'] . ' 12:00:00' ) ) : '';
		$ings[ $key ]  = $ing;
	}
	$recipes = array();
	foreach ( sp_recipes() as $key => $r ) {
		$r['imgUrl']     = ! empty( $r['img'] ) ? sp_img_url( $r['img'] ) : '';
		$r['iconSvg']    = sp_icon( $r['icon'], 20 );
		$recipes[ $key ] = $r;
	}
	$icons = array();
	foreach ( array( 'plus', 'x', 'trash', 'search', 'check', 'refresh', 'calc', 'cake', 'pot', 'tag', 'info', 'arrow', 'clock', 'flame', 'box', 'list', 'copy', 'save', 'edit', 'user' ) as $ic ) {
		$icons[ $ic ] = sp_icon( $ic, 18 );
	}
	return array(
		'soodi'       => SP_URI . '/assets/img/soodi/',
		'units'       => sp_units(),
		'groups'      => sp_ing_groups(),
		'ingredients' => $ings,
		'recipes'     => $recipes,
		'kitchens'    => sp_kitchens(),
		'yieldUnits'  => sp_yield_units(),
		'icons'       => $icons,
		'defaults'    => array(
			'wage'      => (float) $s['wage'],
			'oven'      => (float) $s['oven'],
			'packaging' => (float) $s['packaging'],
			'stove'     => (float) $s['stove'],
			'container' => (float) $s['container'],
			'margin'    => (float) $s['margin'],
		),
		'pricesDate'  => sp_prices_latest_date(),
		'foundersUrl' => sp_url( 'founders' ),
	);
}

/* ---------- صفحه‌ی تنظیمات در پیشخوان ---------- */

add_action( 'admin_menu', function () {
	add_menu_page( 'سودپز', 'سودپز', 'manage_options', 'soodpaz', 'sp_admin_page', 'dashicons-chart-line', 3 );
} );

/** پیشنهادهای قیمت این هفته (از منبع‌ها) که هنوز تأیید نشده‌اند. */
function sp_price_proposals() {
	$p = get_option( 'sp_price_proposals', array() );
	return is_array( $p ) ? $p : array();
}

add_action( 'admin_post_sp_save_settings', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'دسترسی ندارید.' );
	}
	check_admin_referer( 'sp_save_settings' );

	$num = function ( $raw ) {
		return max( 0, (float) preg_replace( '/[^0-9.]/', '', sp_en_digits( (string) $raw ) ) );
	};

	// قیمت‌ها: تأیید پیشنهاد، یا قیمت دستی تازه، یا همان قبلی.
	$info    = sp_price_info();
	$props   = sp_price_proposals();
	$approve = isset( $_POST['approve'] ) ? array_map( 'sanitize_key', array_keys( (array) wp_unslash( $_POST['approve'] ) ) ) : array();
	if ( ! empty( $_POST['approve_all'] ) ) {
		$approve = array_keys( $props );
	}
	$today = current_time( 'Y-m-d' );
	$out   = array();
	foreach ( sp_ingredients() as $key => $ing ) {
		$cur = $info[ $key ];
		if ( in_array( $key, $approve, true ) && isset( $props[ $key ] ) ) {
			$out[ $key ] = array( 'p' => (float) $props[ $key ]['p'], 'src' => $props[ $key ]['src'], 'date' => $props[ $key ]['date'] );
			unset( $props[ $key ] );
			continue;
		}
		$typed = isset( $_POST['price'][ $key ] ) ? $num( wp_unslash( $_POST['price'][ $key ] ) ) : $cur['p'];
		if ( abs( $typed - $cur['p'] ) > 0.001 ) {
			$out[ $key ] = array( 'p' => $typed, 'src' => 'manual', 'date' => $today );
		} else {
			$out[ $key ] = $cur;
		}
	}
	update_option( 'sp_prices', $out );
	if ( ! empty( $_POST['reject'] ) ) {
		foreach ( array_map( 'sanitize_key', array_keys( (array) wp_unslash( $_POST['reject'] ) ) ) as $k ) {
			unset( $props[ $k ] );
		}
	}
	update_option( 'sp_price_proposals', $props, false );

	$left_raw = isset( $_POST['founders_left'] ) ? trim( sp_en_digits( wp_unslash( $_POST['founders_left'] ) ) ) : '';
	$old      = sp_settings();
	update_option( 'sp_settings', array(
		'wage'          => $num( wp_unslash( $_POST['wage'] ?? '' ) ),
		'oven'          => $num( wp_unslash( $_POST['oven'] ?? '' ) ),
		'packaging'     => $num( wp_unslash( $_POST['packaging'] ?? '' ) ),
		'stove'         => $num( wp_unslash( $_POST['stove'] ?? '' ) ),
		'container'     => $num( wp_unslash( $_POST['container'] ?? '' ) ),
		'margin'        => min( 90, $num( wp_unslash( $_POST['margin'] ?? '' ) ) ),
		'prices_date'   => $old['prices_date'],
		'founders_left' => '' === $left_raw ? '' : (int) $left_raw,
	) );

	wp_safe_redirect( admin_url( 'admin.php?page=soodpaz&saved=1' ) );
	exit;
} );

function sp_admin_page() {
	$info  = sp_price_info();
	$props = sp_price_proposals();
	$srcs  = sp_price_sources();
	$s     = sp_settings();
	$fmt   = function ( $n ) { return $n ? number_format( $n ) : '—'; };
	$jd    = function ( $d ) { return $d ? sp_jdate( strtotime( $d . ' 12:00:00' ) ) : ''; };
	$last  = get_option( 'sp_price_fetch_log', array() );
	?>
	<div class="wrap" dir="rtl">
		<h1>سودپز: قیمت مواد و تنظیمات</h1>
		<?php if ( isset( $_GET['saved'] ) ) : ?>
			<div class="notice notice-success"><p>ذخیره شد.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['fetched'] ) ) : ?>
			<div class="notice notice-info"><p>قیمت‌ها از منبع‌ها گرفته شد. پیشنهادها پایین همین صفحه است.</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="sp_save_settings">
			<?php wp_nonce_field( 'sp_save_settings' ); ?>

			<h2>پیشنهادهای قیمت این هفته (<?php echo (int) count( $props ); ?>)</h2>
			<p>
				سایت هر هفته قیمت‌ها را از منبع‌ها می‌گیرد. هر کدام را که درست است تیک بزن و ذخیره کن؛ تا تأیید نکنی، روی سایت نمی‌رود.
				<?php if ( ! empty( $last['time'] ) ) : ?>
					<br><small>آخرین گرفتن قیمت: <?php echo esc_html( $jd( substr( $last['time'], 0, 10 ) ) . ' ' . substr( $last['time'], 11, 5 ) ); ?> · <?php echo esc_html( $last['summary'] ?? '' ); ?></small>
					<?php
					if ( ! empty( $last['missing'] ) ) {
						$ings = sp_ingredients();
						$miss = array_map( function ( $k ) use ( $ings ) { return isset( $ings[ $k ] ) ? $ings[ $k ]['name'] : $k; }, $last['missing'] );
						echo '<br><small style="color:#777">در منبع‌ها پیدا نشد (قیمتش را خودت در جدول پایین بنویس): ' . esc_html( implode( '، ', $miss ) ) . '</small>';
					}
					?>
				<?php endif; ?>
			</p>
			<p><a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sp_fetch_prices' ), 'sp_fetch_prices' ) ); ?>">همین الان قیمت‌ها را از منبع‌ها بگیر</a></p>
			<?php if ( $props ) : ?>
				<table class="widefat striped" style="max-width:980px">
					<thead><tr><th style="width:30px"><input type="checkbox" onclick="document.querySelectorAll('.sp-ap').forEach(function(c){c.checked=this.checked}.bind(this))"></th><th>ماده</th><th>قیمت فعلی</th><th>پیشنهاد</th><th>تغییر</th><th>منبع</th><th>رد کن</th></tr></thead>
					<tbody>
					<?php foreach ( $props as $key => $pr ) : if ( ! isset( $info[ $key ] ) ) { continue; } $ing = sp_ingredients()[ $key ]; $cur = $info[ $key ]['p']; $chg = $cur ? ( $pr['p'] - $cur ) / $cur * 100 : null; ?>
						<tr<?php echo null !== $chg && abs( $chg ) > 30 ? ' style="background:#fff4e5"' : ''; ?>>
							<td><input class="sp-ap" type="checkbox" name="approve[<?php echo esc_attr( $key ); ?>]" value="1"></td>
							<td><b><?php echo esc_html( $ing['name'] ); ?></b> <small>(هر <?php echo esc_html( $ing['unit'] ); ?>)</small></td>
							<td><?php echo esc_html( $fmt( $cur ) ); ?></td>
							<td><b><?php echo esc_html( $fmt( $pr['p'] ) ); ?></b></td>
							<td><?php echo null === $chg ? 'جدید' : esc_html( ( $chg > 0 ? '+' : '' ) . round( $chg ) . '٪' ); ?><?php echo null !== $chg && abs( $chg ) > 30 ? ' ⚠️' : ''; ?></td>
							<td><?php echo esc_html( $srcs[ $pr['src'] ] ?? $pr['src'] ); ?> · <?php echo esc_html( $jd( $pr['date'] ) ); ?><?php if ( ! empty( $pr['note'] ) ) : ?><br><small><?php echo esc_html( $pr['note'] ); ?></small><?php endif; ?><?php if ( ! empty( $pr['url'] ) ) : ?> <a href="<?php echo esc_url( $pr['url'] ); ?>" target="_blank" rel="noopener">دیدن</a><?php endif; ?></td>
							<td><input type="checkbox" name="reject[<?php echo esc_attr( $key ); ?>]" value="1"></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<p><small>ردیف‌های نارنجی بیش از ۳۰٪ با قیمت فعلی فرق دارند؛ قبل از تأیید یک نگاه بینداز.</small></p>
				<p><button class="button button-primary" name="approve_all" value="1">تأیید همه‌ی پیشنهادها</button> <button class="button">ذخیره‌ی تیک‌خورده‌ها</button></p>
			<?php else : ?>
				<p><i>پیشنهاد تازه‌ای نیست.</i></p>
			<?php endif; ?>

			<h2>قیمت مرجع مواد (تومان، برای هر واحد خرید)</h2>
			<p><small>اگر عددی را دستی عوض کنی، منبعش «بررسی سودپز» و تاریخش امروز ثبت می‌شود.</small></p>
			<table class="widefat striped" style="max-width:820px">
				<thead><tr><th>ماده</th><th>واحد</th><th>قیمت (تومان)</th><th>منبع و تاریخ</th></tr></thead>
				<tbody>
				<?php foreach ( sp_ing_groups() as $sp_g => $sp_label ) : ?>
					<tr><th colspan="4" style="background:#f0f0f1"><?php echo esc_html( $sp_label ); ?></th></tr>
					<?php foreach ( sp_ingredients() as $key => $ing ) : if ( $ing['group'] !== $sp_g ) { continue; } $pi = $info[ $key ]; ?>
					<tr>
						<td><?php echo esc_html( $ing['name'] ); ?></td>
						<td>هر <?php echo esc_html( $ing['unit'] ); ?></td>
						<td><input type="text" inputmode="numeric" name="price[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $pi['p'] ? $pi['p'] : '' ); ?>" style="width:150px;direction:ltr"></td>
						<td><small><?php echo $pi['p'] ? esc_html( ( $srcs[ $pi['src'] ] ?? $pi['src'] ) . ( $pi['date'] ? ' · ' . $jd( $pi['date'] ) : '' ) ) : '<span style="color:#b32d2e">قیمت ندارد</span>'; ?></small></td>
					</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
				</tbody>
			</table>

			<h2>پیش‌فرض‌های ماشین‌حساب</h2>
			<table class="form-table" role="presentation">
				<tr><th>دستمزد ساعتی (تومان)</th><td><input type="text" name="wage" value="<?php echo esc_attr( $s['wage'] ? $s['wage'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>هزینه‌ی گاز و برق هر ساعت فر (تومان)</th><td><input type="text" name="oven" value="<?php echo esc_attr( $s['oven'] ? $s['oven'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>جعبه و بسته‌بندی هر کیک (تومان)</th><td><input type="text" name="packaging" value="<?php echo esc_attr( $s['packaging'] ? $s['packaging'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>غذا: هزینه‌ی گاز هر ساعت اجاق (تومان)</th><td><input type="text" name="stove" value="<?php echo esc_attr( $s['stove'] ? $s['stove'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>غذا: ظرف و بسته‌بندی هر پرس (تومان)</th><td><input type="text" name="container" value="<?php echo esc_attr( $s['container'] ? $s['container'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>درصد سود هدف (روی قیمت فروش)</th><td><input type="text" name="margin" value="<?php echo esc_attr( $s['margin'] ); ?>" style="direction:ltr"></td></tr>
			</table>

			<h2>بنیان‌گذارها</h2>
			<p><label>تعداد واقعی جای خالی (از ۵۰): <input type="text" name="founders_left" value="<?php echo esc_attr( $s['founders_left'] ); ?>" style="width:80px;direction:ltr"></label>
			<br><small>خالی بگذاری، عددی روی سایت نشان داده نمی‌شود. فقط عدد واقعی بگذار.</small></p>

			<?php submit_button( 'ذخیره' ); ?>
		</form>
	</div>
	<?php
}
