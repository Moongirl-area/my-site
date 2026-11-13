<?php
/**
 * قیمت مرجع مواد، دستورهای ماشین‌حساب رایگان، و صفحه‌ی تنظیمات در پیشخوان.
 * قیمت‌ها از پیشخوان وردپرس عوض می‌شوند: پیشخوان ← سودپز.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * فهرست مواد. cat = sweet (شیرینی)، food (غذا) یا both (هر دو).
 * price = قیمت نمونه برای هر واحد خرید (تومان)؛ تا وقتی از پیشخوان ذخیره نشده، صفر است.
 */
function sp_ingredients() {
	return array(
		// مشترک
		'flour'       => array( 'name' => 'آرد سفید',            'unit' => 'کیلو', 'cat' => 'both',  'price' => 0 ),
		'egg'         => array( 'name' => 'تخم‌مرغ',              'unit' => 'عدد',  'cat' => 'both',  'price' => 0 ),
		'oil'         => array( 'name' => 'روغن مایع',            'unit' => 'لیتر', 'cat' => 'both',  'price' => 0 ),
		// شیرینی
		'sugar'       => array( 'name' => 'شکر',                  'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'butter'      => array( 'name' => 'کره',                  'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'milk'        => array( 'name' => 'شیر',                  'unit' => 'لیتر', 'cat' => 'sweet', 'price' => 0 ),
		'cream'       => array( 'name' => 'خامه‌ی قنادی',          'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'cocoa'       => array( 'name' => 'پودر کاکائو',          'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'chocolate'   => array( 'name' => 'شکلات تخته‌ای',         'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'creamcheese' => array( 'name' => 'پنیر خامه‌ای',          'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'biscuit'     => array( 'name' => 'بیسکویت ساقه‌طلایی',    'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'gelatin'     => array( 'name' => 'پودر ژلاتین',          'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'bakingpowder'=> array( 'name' => 'بیکینگ پودر',          'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'vanilla'     => array( 'name' => 'وانیل',                'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'carrot'      => array( 'name' => 'هویج',                 'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'walnut'      => array( 'name' => 'مغز گردو',             'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		'cinnamon'    => array( 'name' => 'دارچین',               'unit' => 'کیلو', 'cat' => 'sweet', 'price' => 0 ),
		// غذا
		'rice'        => array( 'name' => 'برنج ایرانی',          'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'meat'        => array( 'name' => 'گوشت گوسفندی یا گوساله', 'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'mince'       => array( 'name' => 'گوشت چرخ‌کرده',         'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'chicken'     => array( 'name' => 'مرغ',                  'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'onion'       => array( 'name' => 'پیاز',                 'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'potato'      => array( 'name' => 'سیب‌زمینی',            'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'tomatopaste' => array( 'name' => 'رب گوجه‌فرنگی',        'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'splitpea'    => array( 'name' => 'لپه',                  'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'kidneybean'  => array( 'name' => 'لوبیا قرمز',           'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'herbs'       => array( 'name' => 'سبزی قورمه (تازه)',     'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'driedlime'   => array( 'name' => 'لیمو عمانی',           'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'barberry'    => array( 'name' => 'زرشک',                 'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'pasta'       => array( 'name' => 'ماکارونی',             'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
		'saffron'     => array( 'name' => 'زعفران',               'unit' => 'گرم',  'cat' => 'food',  'price' => 0 ),
		'spice'       => array( 'name' => 'ادویه و نمک',          'unit' => 'کیلو', 'cat' => 'food',  'price' => 0 ),
	);
}

/** دو آشپزخونه‌ی سودپز. */
function sp_kitchens() {
	return array(
		'sweet' => array( 'name' => 'شیرینی و کیک', 'short' => 'شیرینی', 'icon' => 'cake' ),
		'food'  => array( 'name' => 'غذای خانگی',   'short' => 'غذا',    'icon' => 'pot' ),
	);
}

/**
 * دستورهای ماشین‌حساب رایگان (مقادیر به واحد خرید هر ماده).
 * kitchen = sweet: برای یک کیک ۱ کیلویی. kitchen = food: برای ۱۰ پرس.
 * hours = ساعت کار دستی، oven = ساعت روشن بودن فر یا اجاق گاز.
 * مقدارها تخمین‌اند و باید با آشپز و قناد واقعی چک شوند.
 */
function sp_recipes() {
	return array(
		'chocolate' => array(
			'kitchen' => 'sweet', 'img' => 'cake-chocolate', 'icon' => 'cake', 'color' => '#E8673C',
			'name'  => 'کیک شکلاتی خامه‌ای', 'hours' => 2.5, 'oven' => 0.75,
			'items' => array( 'flour' => 0.15, 'sugar' => 0.18, 'egg' => 3, 'cocoa' => 0.04, 'oil' => 0.1, 'milk' => 0.15, 'bakingpowder' => 0.008, 'cream' => 0.3, 'chocolate' => 0.1 ),
		),
		'vanilla' => array(
			'kitchen' => 'sweet', 'img' => 'cake-vanilla', 'icon' => 'cupcake', 'color' => '#E9A23B',
			'name'  => 'کیک وانیلی خامه‌ای', 'hours' => 2.5, 'oven' => 0.7,
			'items' => array( 'flour' => 0.16, 'sugar' => 0.18, 'egg' => 4, 'oil' => 0.06, 'milk' => 0.1, 'bakingpowder' => 0.006, 'vanilla' => 0.002, 'cream' => 0.35 ),
		),
		'cheesecake' => array(
			'kitchen' => 'sweet', 'img' => 'cake-cheesecake', 'icon' => 'cheese', 'color' => '#149C98',
			'name'  => 'چیزکیک سرد', 'hours' => 1.5, 'oven' => 0,
			'items' => array( 'biscuit' => 0.2, 'butter' => 0.08, 'creamcheese' => 0.35, 'cream' => 0.25, 'sugar' => 0.1, 'gelatin' => 0.01 ),
		),
		'carrot' => array(
			'kitchen' => 'sweet', 'img' => 'cake-carrot', 'icon' => 'carrot', 'color' => '#E03A3E',
			'name'  => 'کیک هویج و گردو', 'hours' => 1.5, 'oven' => 1,
			'items' => array( 'flour' => 0.2, 'sugar' => 0.17, 'egg' => 3, 'oil' => 0.15, 'carrot' => 0.25, 'walnut' => 0.08, 'cinnamon' => 0.005, 'bakingpowder' => 0.008 ),
		),
		'wetchoc' => array(
			'kitchen' => 'sweet', 'img' => 'cake-brownie', 'icon' => 'choc', 'color' => '#8A4FBF',
			'name'  => 'کیک خیس شکلاتی', 'hours' => 1.5, 'oven' => 0.75,
			'items' => array( 'flour' => 0.15, 'sugar' => 0.2, 'egg' => 3, 'cocoa' => 0.05, 'oil' => 0.12, 'milk' => 0.2, 'chocolate' => 0.12, 'cream' => 0.1, 'bakingpowder' => 0.006 ),
		),
		'ghormeh' => array(
			'kitchen' => 'food', 'img' => 'food-ghormeh', 'icon' => 'pot', 'color' => '#3F8A4E',
			'name'  => 'قورمه‌سبزی با برنج', 'hours' => 3, 'oven' => 4,
			'items' => array( 'meat' => 1.0, 'herbs' => 1.0, 'kidneybean' => 0.25, 'onion' => 0.3, 'driedlime' => 0.06, 'oil' => 0.2, 'rice' => 1.2, 'saffron' => 1, 'spice' => 0.03 ),
		),
		'gheymeh' => array(
			'kitchen' => 'food', 'img' => 'food-gheymeh', 'icon' => 'bowl', 'color' => '#D9822B',
			'name'  => 'قیمه با برنج', 'hours' => 2.5, 'oven' => 3,
			'items' => array( 'meat' => 0.9, 'splitpea' => 0.3, 'onion' => 0.3, 'tomatopaste' => 0.15, 'driedlime' => 0.05, 'potato' => 0.8, 'oil' => 0.35, 'rice' => 1.2, 'saffron' => 1, 'spice' => 0.03 ),
		),
		'zereshk' => array(
			'kitchen' => 'food', 'img' => 'food-zereshk', 'icon' => 'drumstick', 'color' => '#C2364A',
			'name'  => 'زرشک‌پلو با مرغ', 'hours' => 2, 'oven' => 2,
			'items' => array( 'chicken' => 3.0, 'onion' => 0.4, 'tomatopaste' => 0.1, 'barberry' => 0.12, 'saffron' => 2, 'oil' => 0.2, 'rice' => 1.2, 'spice' => 0.03 ),
		),
		'kotlet' => array(
			'kitchen' => 'food', 'img' => 'food-kotlet', 'icon' => 'patty', 'color' => '#8A5A3B',
			'name'  => 'کتلت', 'hours' => 2.5, 'oven' => 1.5,
			'items' => array( 'mince' => 1.0, 'potato' => 1.2, 'egg' => 4, 'onion' => 0.3, 'flour' => 0.1, 'oil' => 0.4, 'spice' => 0.03 ),
		),
		'macaroni' => array(
			'kitchen' => 'food', 'img' => 'food-macaroni', 'icon' => 'pasta', 'color' => '#E0A21B',
			'name'  => 'ماکارونی', 'hours' => 1.5, 'oven' => 1.5,
			'items' => array( 'pasta' => 1.4, 'mince' => 0.8, 'onion' => 0.3, 'tomatopaste' => 0.2, 'potato' => 0.5, 'oil' => 0.25, 'spice' => 0.03 ),
		),
	);
}

/** دستورهای یک آشپزخونه. */
function sp_recipes_of( $kitchen ) {
	return array_filter( sp_recipes(), function ( $r ) use ( $kitchen ) {
		return $r['kitchen'] === $kitchen;
	} );
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

/**
 * آیا قیمت همه‌ی مواد دستورهای یک آشپزخونه وارد شده؟
 * بدون آرگومان: هر دو آشپزخونه.
 */
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

function sp_settings() {
	return wp_parse_args( get_option( 'sp_settings', array() ), array(
		'wage'          => 0,   // دستمزد ساعتی پیش‌فرض
		'oven'          => 0,   // هزینه‌ی گاز/برق هر ساعت فر
		'packaging'     => 0,   // جعبه و بسته‌بندی هر کیک
		'stove'         => 0,   // هزینه‌ی گاز هر ساعت اجاق (غذا)
		'container'     => 0,   // ظرف و بسته‌بندی هر پرس غذا
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
			'stove'     => (float) $s['stove'],
			'container' => (float) $s['container'],
			'margin'    => (float) $s['margin'],
		),
		'ready'       => array( 'sweet' => sp_prices_ready( 'sweet' ), 'food' => sp_prices_ready( 'food' ) ),
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
		'stove'         => $num( 'stove' ),
		'container'     => $num( 'container' ),
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
			<div class="notice notice-warning"><p>تا وقتی قیمت مواد یک آشپزخونه (شیرینی یا غذا) کامل وارد نشده، ماشین‌حساب در همان تب پیام «قیمت‌ها هنوز وارد نشده» نشان می‌دهد.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="sp_save_settings">
			<?php wp_nonce_field( 'sp_save_settings' ); ?>

			<h2>قیمت مرجع مواد (تومان، برای هر واحد خرید)</h2>
			<table class="widefat striped" style="max-width:640px">
				<thead><tr><th>ماده</th><th>واحد</th><th>قیمت (تومان)</th></tr></thead>
				<tbody>
				<?php
				$sp_groups = array( 'both' => 'مشترک', 'sweet' => 'شیرینی و کیک', 'food' => 'غذای خانگی' );
				foreach ( $sp_groups as $sp_cat => $sp_label ) :
					?>
					<tr><th colspan="3" style="background:#f0f0f1"><?php echo esc_html( $sp_label ); ?></th></tr>
					<?php foreach ( sp_ingredients() as $key => $ing ) : if ( $ing['cat'] !== $sp_cat ) { continue; } ?>
					<tr>
						<td><?php echo esc_html( $ing['name'] ); ?></td>
						<td>هر <?php echo esc_html( $ing['unit'] ); ?></td>
						<td><input type="text" inputmode="numeric" name="price[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $prices[ $key ] ? $prices[ $key ] : '' ); ?>" style="width:160px;direction:ltr"></td>
					</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
				</tbody>
			</table>
			<p><label>تاریخ این قیمت‌ها (روی سایت نمایش داده می‌شود): <input type="text" name="prices_date" value="<?php echo esc_attr( $s['prices_date'] ); ?>" placeholder="مثلاً ۲۰ آبان ۱۴۰۵"></label></p>

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
