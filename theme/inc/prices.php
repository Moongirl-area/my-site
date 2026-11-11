<?php
/**
 * قیمت مرجع مواد، دستورهای ماشین‌حساب رایگان، و صفحه‌ی تنظیمات در پیشخوان.
 * قیمت‌ها از پیشخوان وردپرس عوض می‌شوند: پیشخوان ← سودپز.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** فهرست مواد. price = قیمت نمونه برای هر واحد خرید (تومان)؛ تا وقتی از پیشخوان ذخیره نشده، «نمونه» نمایش داده می‌شود. */
function sp_ingredients() {
	return array(
		'flour'      => array( 'name' => 'آرد سفید',            'unit' => 'کیلو',  'price' => 0 ),
		'sugar'      => array( 'name' => 'شکر',                  'unit' => 'کیلو',  'price' => 0 ),
		'egg'        => array( 'name' => 'تخم‌مرغ',              'unit' => 'عدد',   'price' => 0 ),
		'butter'     => array( 'name' => 'کره',                  'unit' => 'کیلو',  'price' => 0 ),
		'oil'        => array( 'name' => 'روغن مایع',            'unit' => 'لیتر',  'price' => 0 ),
		'milk'       => array( 'name' => 'شیر',                  'unit' => 'لیتر',  'price' => 0 ),
		'cream'      => array( 'name' => 'خامه‌ی قنادی',          'unit' => 'کیلو',  'price' => 0 ),
		'cocoa'      => array( 'name' => 'پودر کاکائو',          'unit' => 'کیلو',  'price' => 0 ),
		'chocolate'  => array( 'name' => 'شکلات تخته‌ای',         'unit' => 'کیلو',  'price' => 0 ),
		'creamcheese'=> array( 'name' => 'پنیر خامه‌ای',          'unit' => 'کیلو',  'price' => 0 ),
		'biscuit'    => array( 'name' => 'بیسکویت ساقه‌طلایی',    'unit' => 'کیلو',  'price' => 0 ),
		'gelatin'    => array( 'name' => 'پودر ژلاتین',          'unit' => 'کیلو',  'price' => 0 ),
		'bakingpowder'=> array( 'name' => 'بیکینگ پودر',         'unit' => 'کیلو',  'price' => 0 ),
		'vanilla'    => array( 'name' => 'وانیل',                'unit' => 'کیلو',  'price' => 0 ),
		'carrot'     => array( 'name' => 'هویج',                 'unit' => 'کیلو',  'price' => 0 ),
		'walnut'     => array( 'name' => 'مغز گردو',             'unit' => 'کیلو',  'price' => 0 ),
		'cinnamon'   => array( 'name' => 'دارچین',               'unit' => 'کیلو',  'price' => 0 ),
	);
}

/**
 * پنج دستور ماشین‌حساب رایگان؛ مقادیر برای یک کیک ۱ کیلویی (به واحد خرید هر ماده).
 * hours = ساعت کار دستی، oven = ساعت روشن بودن فر.
 */
function sp_recipes() {
	return array(
		'chocolate' => array(
			'name'  => 'کیک شکلاتی خامه‌ای',
			'hours' => 2.5,
			'oven'  => 0.75,
			'items' => array( 'flour' => 0.15, 'sugar' => 0.18, 'egg' => 3, 'cocoa' => 0.04, 'oil' => 0.1, 'milk' => 0.15, 'bakingpowder' => 0.008, 'cream' => 0.3, 'chocolate' => 0.1 ),
		),
		'vanilla' => array(
			'name'  => 'کیک وانیلی خامه‌ای',
			'hours' => 2.5,
			'oven'  => 0.7,
			'items' => array( 'flour' => 0.16, 'sugar' => 0.18, 'egg' => 4, 'oil' => 0.06, 'milk' => 0.1, 'bakingpowder' => 0.006, 'vanilla' => 0.002, 'cream' => 0.35 ),
		),
		'cheesecake' => array(
			'name'  => 'چیزکیک سرد',
			'hours' => 1.5,
			'oven'  => 0,
			'items' => array( 'biscuit' => 0.2, 'butter' => 0.08, 'creamcheese' => 0.35, 'cream' => 0.25, 'sugar' => 0.1, 'gelatin' => 0.01 ),
		),
		'carrot' => array(
			'name'  => 'کیک هویج و گردو',
			'hours' => 1.5,
			'oven'  => 1,
			'items' => array( 'flour' => 0.2, 'sugar' => 0.17, 'egg' => 3, 'oil' => 0.15, 'carrot' => 0.25, 'walnut' => 0.08, 'cinnamon' => 0.005, 'bakingpowder' => 0.008 ),
		),
		'wetchoc' => array(
			'name'  => 'کیک خیس شکلاتی',
			'hours' => 1.5,
			'oven'  => 0.75,
			'items' => array( 'flour' => 0.15, 'sugar' => 0.2, 'egg' => 3, 'cocoa' => 0.05, 'oil' => 0.12, 'milk' => 0.2, 'chocolate' => 0.12, 'cream' => 0.1, 'bakingpowder' => 0.006 ),
		),
	);
}

/** قیمت‌های ذخیره‌شده در پیشخوان (یا نمونه). */
function sp_current_prices() {
	$saved = get_option( 'sp_prices', array() );
	$out   = array();
	foreach ( sp_ingredients() as $key => $ing ) {
		$out[ $key ] = isset( $saved[ $key ] ) ? (float) $saved[ $key ] : (float) $ing['price'];
	}
	return $out;
}

/** آیا همه‌ی قیمت‌های مواد ماشین‌حساب از پیشخوان وارد شده‌اند؟ */
function sp_prices_ready() {
	foreach ( sp_current_prices() as $p ) {
		if ( $p <= 0 ) {
			return false;
		}
	}
	return true;
}

function sp_settings() {
	return wp_parse_args( get_option( 'sp_settings', array() ), array(
		'wage'          => 0,   // دستمزد ساعتی پیش‌فرض
		'oven'          => 0,   // هزینه‌ی گاز/برق هر ساعت فر
		'packaging'     => 0,   // جعبه و بسته‌بندی هر کیک
		'margin'        => 30,  // درصد سود هدف روی قیمت فروش
		'prices_date'   => '',  // تاریخ به‌روزرسانی قیمت‌ها، مثل ۲۰ آبان ۱۴۰۵
		'founders_left' => '',  // تعداد واقعی جای خالی بنیان‌گذار؛ خالی = نمایش داده نشود
	) );
}

/** داده‌ای که به ماشین‌حساب (جاوااسکریپت) داده می‌شود. */
function sp_calculator_data() {
	$s = sp_settings();
	return array(
		'ingredients' => sp_ingredients(),
		'prices'      => sp_current_prices(),
		'recipes'     => sp_recipes(),
		'defaults'    => array(
			'wage'      => (float) $s['wage'],
			'oven'      => (float) $s['oven'],
			'packaging' => (float) $s['packaging'],
			'margin'    => (float) $s['margin'],
		),
		'ready'       => sp_prices_ready(),
		'pricesDate'  => $s['prices_date'],
		'foundersUrl' => sp_url( 'founders' ),
	);
}

/* ---------- صفحه‌ی تنظیمات در پیشخوان ---------- */

add_action( 'admin_menu', function () {
	add_menu_page( 'سودپز', 'سودپز', 'manage_options', 'soodpaz', 'sp_admin_page', 'dashicons-chart-line', 3 );
} );

add_action( 'admin_post_sp_save_settings', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'دسترسی ندارید.' );
	}
	check_admin_referer( 'sp_save_settings' );

	$prices = array();
	foreach ( sp_ingredients() as $key => $ing ) {
		$raw            = isset( $_POST['price'][ $key ] ) ? sp_en_digits( wp_unslash( $_POST['price'][ $key ] ) ) : '0';
		$prices[ $key ] = max( 0, (float) preg_replace( '/[^0-9.]/', '', $raw ) );
	}
	update_option( 'sp_prices', $prices );

	$num = function ( $field ) {
		$raw = isset( $_POST[ $field ] ) ? sp_en_digits( wp_unslash( $_POST[ $field ] ) ) : '';
		return max( 0, (float) preg_replace( '/[^0-9.]/', '', $raw ) );
	};
	$left_raw = isset( $_POST['founders_left'] ) ? trim( sp_en_digits( wp_unslash( $_POST['founders_left'] ) ) ) : '';
	update_option( 'sp_settings', array(
		'wage'          => $num( 'wage' ),
		'oven'          => $num( 'oven' ),
		'packaging'     => $num( 'packaging' ),
		'margin'        => min( 90, $num( 'margin' ) ),
		'prices_date'   => isset( $_POST['prices_date'] ) ? sanitize_text_field( wp_unslash( $_POST['prices_date'] ) ) : '',
		'founders_left' => '' === $left_raw ? '' : (int) $left_raw,
	) );

	wp_safe_redirect( admin_url( 'admin.php?page=soodpaz&saved=1' ) );
	exit;
} );

function sp_admin_page() {
	$prices = sp_current_prices();
	$s      = sp_settings();
	?>
	<div class="wrap">
		<h1>سودپز: قیمت مواد و تنظیمات</h1>
		<?php if ( isset( $_GET['saved'] ) ) : ?>
			<div class="notice notice-success"><p>ذخیره شد.</p></div>
		<?php endif; ?>
		<?php if ( ! sp_prices_ready() ) : ?>
			<div class="notice notice-warning"><p>تا وقتی قیمت همه‌ی مواد وارد نشده، ماشین‌حساب روی سایت پیام «قیمت‌ها هنوز وارد نشده» نشان می‌دهد.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="sp_save_settings">
			<?php wp_nonce_field( 'sp_save_settings' ); ?>

			<h2>قیمت مرجع مواد (تومان، برای هر واحد خرید)</h2>
			<table class="widefat striped" style="max-width:640px">
				<thead><tr><th>ماده</th><th>واحد</th><th>قیمت (تومان)</th></tr></thead>
				<tbody>
				<?php foreach ( sp_ingredients() as $key => $ing ) : ?>
					<tr>
						<td><?php echo esc_html( $ing['name'] ); ?></td>
						<td>هر <?php echo esc_html( $ing['unit'] ); ?></td>
						<td><input type="text" inputmode="numeric" name="price[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $prices[ $key ] ? $prices[ $key ] : '' ); ?>" style="width:160px;direction:ltr"></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<p><label>تاریخ این قیمت‌ها (روی سایت نمایش داده می‌شود): <input type="text" name="prices_date" value="<?php echo esc_attr( $s['prices_date'] ); ?>" placeholder="مثلاً ۲۰ آبان ۱۴۰۵"></label></p>

			<h2>پیش‌فرض‌های ماشین‌حساب</h2>
			<table class="form-table" role="presentation">
				<tr><th>دستمزد ساعتی (تومان)</th><td><input type="text" name="wage" value="<?php echo esc_attr( $s['wage'] ? $s['wage'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>هزینه‌ی گاز و برق هر ساعت فر (تومان)</th><td><input type="text" name="oven" value="<?php echo esc_attr( $s['oven'] ? $s['oven'] : '' ); ?>" style="direction:ltr"></td></tr>
				<tr><th>جعبه و بسته‌بندی هر کیک (تومان)</th><td><input type="text" name="packaging" value="<?php echo esc_attr( $s['packaging'] ? $s['packaging'] : '' ); ?>" style="direction:ltr"></td></tr>
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
