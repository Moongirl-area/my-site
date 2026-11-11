<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** لینک یک صفحه با نامک (slug) آن. */
function sp_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/** عدد انگلیسی به فارسی، با جداکننده‌ی هزارگان. */
function sp_fa( $number, $thousands = true ) {
	$str = $thousands && is_numeric( $number ) ? number_format( (float) $number ) : (string) $number;
	$str = str_replace( ',', '٬', $str );
	return strtr( $str, array( '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹' ) );
}

/** عدد فارسی/عربی به انگلیسی (برای ورودی فرم‌ها). */
function sp_en_digits( $str ) {
	return strtr( (string) $str, array(
		'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
		'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
	) );
}

/** لوگوی سودپز: برش کیک با لایه‌های پله‌ای رو به بالا و گیلاس زعفرانی. */
function sp_logo_svg( $size = 36 ) {
	return '<svg class="sp-logo-mark" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 48 48" aria-hidden="true" focusable="false">'
		. '<rect x="6" y="30" width="36" height="10" rx="2" fill="#3B2418"/>'
		. '<rect x="12" y="21" width="30" height="8" rx="2" fill="#7A4A1E"/>'
		. '<rect x="20" y="13" width="22" height="7" rx="2" fill="#F4C6C2"/>'
		. '<circle cx="34" cy="8" r="4.5" fill="#F2A33A"/>'
		. '<path d="M34 4 q2 -3 5 -3" stroke="#23704A" stroke-width="1.8" fill="none" stroke-linecap="round"/>'
		. '</svg>';
}
