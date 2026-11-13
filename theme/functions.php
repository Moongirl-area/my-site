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
