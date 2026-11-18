<?php
/**
 * میز کار کاربر (داشبورد): دستورهای ذخیره‌شده، مقاله‌های نشان‌شده، سود و وضعیت اشتراک.
 * داده‌ها از همان حساب کاربری می‌آید (inc/account.php) و هیچ چیز تازه‌ای جمع نمی‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** آدرس میز کار. */
function sp_dash_url() {
	return sp_url( 'dashboard' );
}

/* کاربر واردشده به‌جای صفحه‌ی ورود/عضویت، میز کارش را ببیند. */
add_action( 'template_redirect', function () {
	if ( is_user_logged_in() && is_page( array( 'login', 'register' ) ) && ! isset( $_GET['redirect_to'] ) ) {
		wp_safe_redirect( sp_dash_url() );
		exit;
	}
	if ( ! is_user_logged_in() && is_page( 'dashboard' ) ) {
		wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( sp_dash_url() ), sp_url( 'login' ) ) );
		exit;
	}
}, 5 );

/** دستورهای کاربر، تازه‌ترین اول. */
function sp_dash_recipes( $uid ) {
	$r = sp_user_store( $uid )['recipes'] ?? array();
	if ( ! is_array( $r ) ) {
		return array();
	}
	uasort( $r, function ( $a, $b ) { return ( $b['updated'] ?? 0 ) <=> ( $a['updated'] ?? 0 ); } );
	return $r;
}

/** جمع‌بندی عددی دستورها برای کارت‌ها و نمودار. */
function sp_dash_stats( $recipes ) {
	$o = array( 'count' => 0, 'ready' => 0, 'profit_batch' => 0, 'margin' => 0, 'saved' => 0, 'mat' => 0, 'wg' => 0, 'ot' => 0, 'pr' => 0, 'bars' => array() );
	foreach ( $recipes as $id => $r ) {
		$o['count']++;
		$s = $r['sum'] ?? null;
		if ( ! is_array( $s ) || empty( $s['p'] ) || ! empty( $s['miss'] ) ) {
			continue;
		}
		$n = max( 1, (float) ( $s['n'] ?? 1 ) );
		$o['ready']++;
		$o['profit_batch'] += (float) $s['pr'] * $n;
		$o['margin']       += (float) ( $s['m'] ?? 0 );
		$o['mat']          += (float) ( $s['mat'] ?? 0 ) * $n;
		$o['wg']           += (float) ( $s['wg'] ?? 0 ) * $n;
		$o['ot']           += (float) ( $s['ot'] ?? 0 ) * $n;
		$o['pr']           += (float) $s['pr'] * $n;
		$cur                = (float) ( $s['cur'] ?? 0 );
		if ( $cur > 0 && $s['p'] > $cur ) {
			$o['saved'] += ( $s['p'] - $cur ) * $n;
		}
		$o['bars'][] = array(
			'name'   => (string) ( $r['name'] ?? 'بدون اسم' ),
			'profit' => (float) $s['pr'] * $n,
			'food'   => ( $r['kitchen'] ?? 'sweet' ) === 'food',
			'id'     => (string) $id,
		);
	}
	if ( $o['ready'] ) {
		$o['margin'] = $o['margin'] / $o['ready'];
	}
	usort( $o['bars'], function ( $a, $b ) { return $b['profit'] <=> $a['profit']; } );
	$o['bars'] = array_slice( $o['bars'], 0, 6 );
	return $o;
}

/** وضعیت اشتراک: برچسب، روز باقی‌مانده و درصد پیشرفت. */
function sp_dash_plan( $uid ) {
	$plan = sp_user_plan( $uid );
	$d    = array( 'plan' => $plan, 'days' => 0, 'total' => 0, 'label' => sp_plan_label( $uid ) );
	if ( 'trial' === $plan ) {
		$d['total'] = sp_trial_days();
		$d['days']  = max( 0, (int) ceil( ( sp_trial_start( $uid ) + $d['total'] * DAY_IN_SECONDS - time() ) / DAY_IN_SECONDS ) );
	} elseif ( 'pro' === $plan ) {
		$until = (int) get_user_meta( $uid, 'sp_pro_until', true );
		if ( $until ) {
			$d['days']  = max( 0, (int) ceil( ( $until - time() ) / DAY_IN_SECONDS ) );
			$d['total'] = 365;
			$d['until'] = function_exists( 'sp_jdate' ) ? sp_jdate( gmdate( 'Y-m-d', $until ) ) : '';
		}
	}
	return $d;
}

/* ---------- مقاله‌های نشان‌شده ---------- */
function sp_saved_posts( $uid ) {
	$ids = get_user_meta( $uid, 'sp_saved_posts', true );
	return is_array( $ids ) ? array_values( array_filter( array_map( 'intval', $ids ) ) ) : array();
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'soodpaz/v1', '/bookmark', array(
		'methods'             => 'POST',
		'permission_callback' => function () { return is_user_logged_in(); },
		'callback'            => function ( WP_REST_Request $r ) {
			$uid = get_current_user_id();
			$id  = (int) $r->get_param( 'id' );
			$on  = (bool) $r->get_param( 'on' );
			if ( ! $id || 'publish' !== get_post_status( $id ) ) {
				return new WP_Error( 'bad_post', 'مطلب پیدا نشد.', array( 'status' => 404 ) );
			}
			$ids = sp_saved_posts( $uid );
			$ids = array_values( array_diff( $ids, array( $id ) ) );
			if ( $on ) {
				array_unshift( $ids, $id );
			}
			update_user_meta( $uid, 'sp_saved_posts', array_slice( $ids, 0, 200 ) );
			return array( 'ok' => true, 'on' => $on, 'count' => count( $ids ) );
		},
	) );
} );

/** دکمه‌ی «ذخیره برای بعد» روی مطلب‌های مجله. */
function sp_bookmark_btn( $post_id ) {
	$in  = is_user_logged_in();
	$on  = $in && in_array( (int) $post_id, sp_saved_posts( get_current_user_id() ), true );
	$cls = 'sp-bm' . ( $on ? ' is-on' : '' );
	printf(
		'<button type="button" class="%s" data-bm="%d" aria-pressed="%s"><span class="sp-bm__i">%s</span><span class="sp-bm__t">%s</span></button>',
		esc_attr( $cls ),
		(int) $post_id,
		$on ? 'true' : 'false',
		sp_icon( 'save', 17 ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $on ? 'ذخیره شد' : 'ذخیره برای بعد' )
	);
}

/* اسکریپت کوچک دکمه‌ی ذخیره + آدرس‌ها */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_singular( 'post' ) && ! is_page( 'dashboard' ) ) {
		return;
	}
	$path = SP_DIR . '/assets/js/account.js';
	wp_enqueue_script( 'sp-account', SP_URI . '/assets/js/account.js', array(), file_exists( $path ) ? filemtime( $path ) : null, true );
	wp_localize_script( 'sp-account', 'SP_ACC', array(
		'in'    => is_user_logged_in(),
		'rest'  => esc_url_raw( rest_url( 'soodpaz/v1/bookmark' ) ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
		'login' => add_query_arg( 'redirect_to', rawurlencode( is_singular() ? get_permalink() : sp_dash_url() ), sp_url( 'login' ) ),
	) );
}, 20 );
