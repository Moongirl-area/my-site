<?php
/**
 * سودپز: تنظیمات اصلی پوسته
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SP_VERSION', '0.4.0' );
define( 'SP_DIR', get_template_directory() );
define( 'SP_URI', get_template_directory_uri() );

require_once SP_DIR . '/inc/helpers.php';
require_once SP_DIR . '/inc/prices.php';
require_once SP_DIR . '/inc/leads.php';
require_once SP_DIR . '/inc/contact.php';
require_once SP_DIR . '/inc/content.php';
require_once SP_DIR . '/inc/blog.php';
require_once SP_DIR . '/inc/blog-import.php';
require_once SP_DIR . '/inc/blog-seo.php';

/* ---------- راه‌اندازی پوسته ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'post-thumbnails' );
} );

/* ---------- استایل و اسکریپت ---------- */
add_action( 'wp_enqueue_scripts', function () {
	$css = SP_DIR . '/assets/css/main.css';
	wp_enqueue_style( 'sp-main', SP_URI . '/assets/css/main.css', array(), file_exists( $css ) ? filemtime( $css ) : SP_VERSION );

	if ( is_front_page() ) {
		$mjs = SP_DIR . '/assets/js/motion.js';
		wp_enqueue_script( 'sp-motion', SP_URI . '/assets/js/motion.js', array(), file_exists( $mjs ) ? filemtime( $mjs ) : SP_VERSION, true );
	}

	if ( is_page( 'calculator' ) ) {
		$js = SP_DIR . '/assets/js/calculator.js';
		wp_enqueue_script( 'sp-calc', SP_URI . '/assets/js/calculator.js', array(), file_exists( $js ) ? filemtime( $js ) : SP_VERSION, true );
		wp_localize_script( 'sp-calc', 'SP_DATA', sp_calculator_data() );
	}

	// اسکریپت‌های اضافه‌ی وردپرس که لازم نداریم (سبک‌تر و بدون منبع خارجی)
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

// ایموجی وردپرس از سرور خارجی لود می‌شود؛ در قطعی اینترنت سایت را کند می‌کند.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ---------- ساختن خودکار صفحه‌ها و آموزش‌ها (inc/content.php) ---------- */
add_action( 'after_switch_theme', function () {
	sp_create_pages();
	sp_create_starter_posts();
} );

/**
 * بعد از هر آپلود، کش صفحه‌ها پاک شود تا بازدیدکننده‌ها نسخه‌ی قدیمی (مثلاً بدون سودی) نبینند.
 * گیت‌هاب شناسه‌ی آخرین کامیت را در deploy.txt می‌نویسد؛ اولین درخواستی که PHP را اجرا کند
 * (درخواست ‎?sp_deploy=… در پایان آپلود، یا باز کردن پیشخوان) اگر شناسه عوض شده باشد همه‌ی کش‌ها را پاک می‌کند.
 */
function sp_flush_all_caches() {
	do_action( 'litespeed_purge_all' );                                             // LiteSpeed Cache
	if ( function_exists( 'rocket_clean_domain' ) ) { rocket_clean_domain(); }       // WP Rocket
	if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }                 // W3 Total Cache
	if ( function_exists( 'wp_cache_clear_cache' ) ) { wp_cache_clear_cache(); }     // WP Super Cache
	if ( class_exists( 'WpFastestCache' ) ) { do_action( 'wpfc_clear_all_cache' ); } // WP Fastest Cache
	if ( function_exists( 'sg_cachepress_purge_cache' ) ) { sg_cachepress_purge_cache(); }
	do_action( 'breeze_clear_all_cache' );                                          // Breeze
	do_action( 'cache_enabler_clear_complete_cache' );                              // Cache Enabler
	wp_cache_flush();
}
add_action( 'init', function () {
	$f = SP_DIR . '/deploy.txt';
	if ( ! file_exists( $f ) ) {
		return;
	}
	$stamp = trim( (string) file_get_contents( $f ) );
	if ( '' === $stamp || get_option( 'sp_deploy_stamp' ) === $stamp ) {
		return;
	}
	update_option( 'sp_deploy_stamp', $stamp, false );
	sp_flush_all_caches();
}, 1 );

/**
 * عکس پروفایل مدیرهای سایت (نوار بالای وردپرس، پیشخوان، دیدگاه‌ها) = لوگوی سودپز،
 * به‌جای عکس Gravatar ایمیل‌شان.
 */
add_filter( 'pre_get_avatar_data', function ( $args, $id_or_email ) {
	$user = false;
	if ( is_numeric( $id_or_email ) ) {
		$user = get_user_by( 'id', (int) $id_or_email );
	} elseif ( $id_or_email instanceof WP_User ) {
		$user = $id_or_email;
	} elseif ( $id_or_email instanceof WP_Post ) {
		$user = get_user_by( 'id', (int) $id_or_email->post_author );
	} elseif ( $id_or_email instanceof WP_Comment ) {
		$user = $id_or_email->user_id ? get_user_by( 'id', (int) $id_or_email->user_id ) : false;
	} elseif ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
	}
	if ( $user && user_can( $user, 'manage_options' ) ) {
		$args['url']          = SP_URI . '/assets/img/logo/apple-touch-icon.png';
		$args['found_avatar'] = true;
	}
	return $args;
}, 10, 2 );
