<?php
/**
 * عضویت و ورود سودپز (با Gravity Forms + افزونه‌ی User Registration).
 *
 * - فرم «عضویت»: نام کاربری، ایمیل، موبایل (یکی از این دو لازم)، رمز. بعد از عضویت خودکار وارد می‌شود.
 * - ورود: فرم ورود Gravity Forms؛ با موبایل یا ایمیل یا نام کاربری + رمز.
 * - دستورهای ذخیره‌شده‌ی ماشین‌حساب در حساب کاربر (user meta) نگه داشته می‌شوند، نه در مرورگر.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SP_ACCOUNT_VERSION', 1 );

function sp_gf_ready() {
	return class_exists( 'GFAPI' ) && function_exists( 'gf_user_registration' );
}

/** موبایل به شکل 09xxxxxxxxx یا رشته‌ی خالی اگر معتبر نیست. */
function sp_norm_mobile( $m ) {
	$m = preg_replace( '/[^0-9]/', '', sp_en_digits( (string) $m ) );
	if ( 0 === strpos( $m, '0098' ) ) {
		$m = '0' . substr( $m, 4 );
	} elseif ( 0 === strpos( $m, '98' ) && 12 === strlen( $m ) ) {
		$m = '0' . substr( $m, 2 );
	} elseif ( 10 === strlen( $m ) && '9' === $m[0] ) {
		$m = '0' . $m;
	}
	return preg_match( '/^09\d{9}$/', $m ) ? $m : '';
}

function sp_user_by_mobile( $m ) {
	$u = get_users( array( 'meta_key' => 'sp_mobile', 'meta_value' => $m, 'number' => 1 ) );
	return $u ? $u[0] : null;
}

function sp_reg_form_id() {
	return (int) get_option( 'sp_gf_register_form', 0 );
}

/* ---------- ساخت فرم عضویت در Gravity Forms (یک بار) ---------- */
add_action( 'admin_init', function () {
	if ( ! sp_gf_ready() || (int) get_option( 'sp_account_version', 0 ) >= SP_ACCOUNT_VERSION ) {
		return;
	}
	$id = sp_reg_form_id();
	if ( ! $id || is_wp_error( GFAPI::get_form( $id ) ) || ! GFAPI::get_form( $id ) ) {
		$form = array(
			'title'                => 'عضویت در سودپز',
			'description'          => '',
			'labelPlacement'       => 'top_label',
			'button'               => array( 'type' => 'text', 'text' => 'عضو شو' ),
			'fields'               => array(
				array( 'id' => 1, 'type' => 'text', 'label' => 'نام کاربری', 'isRequired' => true, 'placeholder' => 'مثلاً maryam_cake', 'description' => 'فقط حروف انگلیسی، عدد و _' ),
				array( 'id' => 3, 'type' => 'text', 'label' => 'شماره موبایل', 'isRequired' => false, 'placeholder' => '۰۹۱۲۳۴۵۶۷۸۹', 'inputType' => 'tel' ),
				array( 'id' => 2, 'type' => 'email', 'label' => 'ایمیل', 'isRequired' => false, 'placeholder' => 'name@example.com', 'description' => 'موبایل یا ایمیل، یکی کافیه.' ),
				array(
					'id'         => 4,
					'type'       => 'password',
					'label'      => 'رمز عبور',
					'isRequired' => true,
					'inputs'     => array(
						array( 'id' => '4', 'label' => 'رمز عبور' ),
						array( 'id' => '4.2', 'label' => 'تکرار رمز' ),
					),
					'description' => 'حداقل ۶ حرف.',
				),
			),
			'confirmations'        => array(
				'sp1' => array( 'id' => 'sp1', 'name' => 'پیش‌فرض', 'isDefault' => true, 'type' => 'redirect', 'url' => home_url( '/calculator/?welcome=1' ) ),
			),
			'notifications'        => array(),
			'requireLogin'         => false,
			'enableHoneypot'       => true,
		);
		$id = GFAPI::add_form( $form );
		if ( is_wp_error( $id ) ) {
			return;
		}
		update_option( 'sp_gf_register_form', (int) $id, false );
	}
	$feeds = GFAPI::get_feeds( null, $id, 'gravityformsuserregistration' );
	if ( is_wp_error( $feeds ) || ! $feeds ) {
		GFAPI::add_feed( $id, array(
			'feedName'                         => 'ساخت کاربر سودپز',
			'feedType'                         => 'create',
			'username'                         => '1',
			'first_name'                       => '',
			'last_name'                        => '',
			'nickname'                         => '1',
			'displayname'                      => 'username',
			'email'                            => '2',
			'password'                         => '4',
			'role'                             => 'subscriber',
			'sendEmail'                        => '0',
			'userActivation'                   => '0',
			'feed_condition_conditional_logic' => '0',
		), 'gravityformsuserregistration' );
	}
	update_option( 'sp_account_version', SP_ACCOUNT_VERSION, false );
} );

/* ---------- قبل از اعتبارسنجی: یکدست کردن موبایل؛ اگر ایمیل خالی بود ایمیل داخلی بساز (وردپرس ایمیل لازم دارد) ---------- */
add_filter( 'gform_pre_validation', function ( $form ) {
	if ( (int) $form['id'] !== sp_reg_form_id() ) {
		return $form;
	}
	$m = sp_norm_mobile( rgpost( 'input_3' ) );
	if ( $m ) {
		$_POST['input_3'] = $m;
	}
	if ( '' === trim( (string) rgpost( 'input_2' ) ) && $m ) {
		$_POST['input_2'] = $m . '@users.soodpaz.com';
	}
	if ( isset( $_POST['input_1'] ) ) {
		$_POST['input_1'] = strtolower( trim( sanitize_user( wp_unslash( $_POST['input_1'] ), true ) ) );
	}
	return $form;
} );

add_filter( 'gform_validation', function ( $res ) {
	$form = $res['form'];
	if ( (int) $form['id'] !== sp_reg_form_id() ) {
		return $res;
	}
	$raw_m = trim( (string) rgpost( 'input_3' ) );
	$m     = sp_norm_mobile( $raw_m );
	$email = trim( (string) rgpost( 'input_2' ) );
	$pass  = (string) rgpost( 'input_4' );
	foreach ( $form['fields'] as &$f ) {
		$err = '';
		if ( 3 === (int) $f->id ) {
			if ( '' !== $raw_m && ! $m ) {
				$err = 'شماره موبایل درست نیست. مثل ۰۹۱۲۳۴۵۶۷۸۹ بنویس.';
			} elseif ( $m && sp_user_by_mobile( $m ) ) {
				$err = 'با این شماره قبلاً عضو شدی. از «ورود» وارد شو.';
			} elseif ( '' === $raw_m && '' === $email ) {
				$err = 'موبایل یا ایمیل، یکی رو بنویس.';
			}
		} elseif ( 1 === (int) $f->id && rgpost( 'input_1' ) && ! preg_match( '/^[a-z0-9_.]{3,30}$/', rgpost( 'input_1' ) ) ) {
			$err = 'نام کاربری فقط حروف انگلیسی، عدد، _ و نقطه (۳ تا ۳۰ حرف).';
		} elseif ( 4 === (int) $f->id && '' !== $pass && strlen( $pass ) < 6 ) {
			$err = 'رمز حداقل ۶ حرف باشه.';
		}
		if ( $err ) {
			$f->failed_validation  = true;
			$f->validation_message = $err;
			$res['is_valid']       = false;
		}
	}
	$res['form'] = $form;
	return $res;
}, 20 );

/* بعد از ساخت کاربر: ذخیره‌ی موبایل و ورود خودکار */
add_action( 'gform_user_registered', function ( $user_id, $feed, $entry ) {
	$m = sp_norm_mobile( rgar( $entry, '3' ) );
	if ( $m ) {
		update_user_meta( $user_id, 'sp_mobile', $m );
	}
	if ( ! is_user_logged_in() ) {
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true );
	}
}, 10, 3 );

/* بعد از عضویت، اگر از جایی آمده بود (مثلاً ماشین‌حساب)، همان‌جا برگردد */
add_filter( 'gform_confirmation', function ( $conf, $form ) {
	if ( (int) $form['id'] !== sp_reg_form_id() ) {
		return $conf;
	}
	$to = isset( $_GET['redirect_to'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ), '' ) : '';
	return array( 'redirect' => $to ? $to : home_url( '/calculator/?welcome=1' ) );
}, 10, 2 );

/* ---------- ورود با شماره موبایل (ایمیل و نام کاربری را خود وردپرس می‌پذیرد) ---------- */
add_filter( 'authenticate', function ( $user, $login, $password ) {
	if ( $user instanceof WP_User || '' === (string) $login ) {
		return $user;
	}
	$m = sp_norm_mobile( $login );
	if ( ! $m ) {
		return $user;
	}
	$u = sp_user_by_mobile( $m );
	return $u ? wp_authenticate_username_password( null, $u->user_login, $password ) : $user;
}, 15, 3 );

/* برچسب‌های فارسی فرم ورود Gravity Forms */
add_filter( 'gform_userregistration_login_form', function ( $form ) {
	foreach ( $form['fields'] as &$f ) {
		if ( 1 === (int) $f->id ) {
			$f->label       = 'موبایل یا ایمیل';
			$f->placeholder = '۰۹۱۲۳۴۵۶۷۸۹ یا ایمیل';
		} elseif ( 2 === (int) $f->id ) {
			$f->label = 'رمز عبور';
		} elseif ( 3 === (int) $f->id && ! empty( $f->choices ) ) {
			$f->choices[0]['text'] = 'مرا یادت باشه';
		}
	}
	$form['button']['text'] = 'ورود';
	return $form;
} );

/* کاربرهای عادی پیشخوان وردپرس و نوار مدیریت را نبینند */
add_filter( 'show_admin_bar', function ( $show ) {
	return current_user_can( 'edit_posts' ) ? $show : false;
} );
add_action( 'admin_init', function () {
	if ( wp_doing_ajax() || current_user_can( 'edit_posts' ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	if ( is_user_logged_in() && isset( $_SERVER['SCRIPT_NAME'] ) && false === strpos( $_SERVER['SCRIPT_NAME'], 'admin-post.php' ) ) {
		wp_safe_redirect( home_url( '/calculator/' ) );
		exit;
	}
} );

/* ---------- دستورهای ماشین‌حساب در حساب کاربر ---------- */
function sp_user_store( $uid ) {
	$s = get_user_meta( $uid, 'sp_calc_store', true );
	return is_array( $s ) ? $s : array();
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'soodpaz/v1', '/store', array(
		'methods'             => 'POST',
		'permission_callback' => function () { return is_user_logged_in(); },
		'callback'            => function ( WP_REST_Request $r ) {
			$body = $r->get_json_params();
			$keep = array();
			foreach ( array( 'recipes', 'prices', 'ings', 'prefs', 'last' ) as $k ) {
				$keep[ $k ] = isset( $body[ $k ] ) && is_array( $body[ $k ] ) ? $body[ $k ] : array();
			}
			if ( strlen( wp_json_encode( $keep ) ) > 400000 ) {
				return new WP_Error( 'too_big', 'حجم دستورها زیاده.', array( 'status' => 413 ) );
			}
			update_user_meta( get_current_user_id(), 'sp_calc_store', $keep );
			return array( 'ok' => true );
		},
	) );
} );

/** داده‌ی کاربر برای ماشین‌حساب. */
function sp_calc_user_data() {
	$back = home_url( '/calculator/?resume=1' );
	$d    = array(
		'in'       => is_user_logged_in(),
		'register' => add_query_arg( 'redirect_to', rawurlencode( $back ), sp_url( 'register' ) ),
		'login'    => add_query_arg( 'redirect_to', rawurlencode( $back ), sp_url( 'login' ) ),
	);
	if ( $d['in'] ) {
		$u          = wp_get_current_user();
		$d['name']  = $u->display_name;
		$d['store'] = (object) sp_user_store( $u->ID );
		$d['rest']  = esc_url_raw( rest_url( 'soodpaz/v1/store' ) );
		$d['nonce'] = wp_create_nonce( 'wp_rest' );
	}
	return $d;
}

/* این صفحه‌ها کش نشوند (محتوایشان به ورود کاربر بستگی دارد) */
add_action( 'template_redirect', function () {
	if ( is_page( array( 'login', 'register', 'calculator' ) ) && is_user_logged_in() ) {
		do_action( 'litespeed_control_set_nocache', 'soodpaz user page' );
		nocache_headers();
	}
	if ( is_page( array( 'login', 'register' ) ) ) {
		do_action( 'litespeed_control_set_nocache', 'soodpaz auth page' );
	}
} );
