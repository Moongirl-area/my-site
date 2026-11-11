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

/**
 * عکس از assets/img؛ اگر هنوز آپلود نشده، یک قاب جای‌خالی نشان می‌دهد.
 * $name بدون پسوند (jpg یا webp).
 */
function sp_img( $name, $alt = '', $class = '', $eager = false ) {
	foreach ( array( 'webp', 'jpg', 'jpeg', 'png' ) as $ext ) {
		$rel = '/assets/img/' . $name . '.' . $ext;
		if ( file_exists( SP_DIR . $rel ) ) {
			return '<img class="' . esc_attr( $class ) . '" src="' . esc_url( SP_URI . $rel ) . '" alt="' . esc_attr( $alt ) . '"' . ( $eager ? ' fetchpriority="high"' : ' loading="lazy"' ) . ' decoding="async">';
		}
	}
	return '<span class="sp-ph ' . esc_attr( $class ) . '" role="img" aria-label="' . esc_attr( $alt ) . '"><span>' . esc_html( $alt ) . '</span></span>';
}

/** آیکون‌های خطی سودپز (SVG درون‌خطی، بدون منبع خارجی). */
function sp_icon( $name, $size = 24 ) {
	$p = array(
		'cake'      => '<path d="M4 21h16M5 21v-7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v7"/><path d="M5 16c1.5 1 2.5 1 3.5 0s2.5-1 3.5 0 2.5 1 3.5 0 2-1 3.5 0"/><path d="M12 12V8M12 5.5a1.2 1.2 0 1 0 0-.01"/>',
		'cheese'    => '<path d="M3 18l9-12 9 6v6z"/><path d="M3 18h18M8 14.5a1 1 0 1 0 0-.01M15 15a1.2 1.2 0 1 0 0-.01"/>',
		'choc'      => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M5 9h14M5 15h14M12 3v18"/>',
		'carrot'    => '<path d="M14 10 4 20M14 10c2-2 5-2 6 0-1 2-1 5-3 6L7 17"/><path d="M16 8c0-2 1-4 3-5M17 9c1-1 3-1 4-1"/>',
		'cupcake'   => '<path d="M6 12h12l-1.5 9h-9z"/><path d="M5 12a3 3 0 0 1 2-5 5 5 0 0 1 10 0 3 3 0 0 1 2 5"/><path d="M10 12l1 9M14 12l-1 9"/>',
		'calc'      => '<rect x="5" y="2.5" width="14" height="19" rx="2.5"/><rect x="8" y="5.5" width="8" height="4" rx="1"/><path d="M8.5 13h.01M12 13h.01M15.5 13h.01M8.5 16.5h.01M12 16.5h.01M15.5 16.5h.01"/>',
		'bell'      => '<path d="M6 16V11a6 6 0 1 1 12 0v5l1.5 2h-15z"/><path d="M10 20a2 2 0 0 0 4 0"/>',
		'refresh'   => '<path d="M20 11a8 8 0 0 0-14.5-4.5L4 8M4 4v4h4"/><path d="M4 13a8 8 0 0 0 14.5 4.5L20 16M20 20v-4h-4"/>',
		'tag'       => '<path d="M3 12V4a1 1 0 0 1 1-1h8l9 9-9 9z"/><path d="M8 8h.01"/>',
		'story'     => '<rect x="6" y="2.5" width="12" height="19" rx="2.5"/><path d="M9 8h6M9 11.5h6M9 15h4"/>',
		'book'      => '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 19V5M9 7h6"/>',
		'server'    => '<rect x="4" y="3" width="16" height="7" rx="2"/><rect x="4" y="14" width="16" height="7" rx="2"/><path d="M8 6.5h.01M8 17.5h.01"/>',
		'lock'      => '<rect x="5" y="10.5" width="14" height="10" rx="2"/><path d="M8 10.5V7a4 4 0 0 1 8 0v3.5"/>',
		'refund'    => '<path d="M4 9h12a4 4 0 0 1 0 8H9"/><path d="M8 5 4 9l4 4"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'phone'     => '<rect x="6.5" y="2.5" width="11" height="19" rx="2.5"/><path d="M11 18.5h2"/>',
		'flame'     => '<path d="M12 21a6 6 0 0 0 6-6c0-4-3-6-4-10-2 2-3 3.5-3 6-1-1-1.5-2-1.5-3C7.5 10 6 12.5 6 15a6 6 0 0 0 6 6z"/>',
		'box'       => '<path d="M3 8l9-5 9 5v8l-9 5-9-5z"/><path d="M3 8l9 5 9-5M12 13v8"/>',
		'arrow'     => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
		'check'     => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'up'        => '<path d="M12 19V5M6 11l6-6 6 6"/>',
		'down'      => '<path d="M12 5v14M6 13l6 6 6-6"/>',
		'users'     => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"/>',
		'spark'     => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M6 18l2.5-2.5M15.5 8.5 18 6"/>',
	);
	if ( ! isset( $p[ $name ] ) ) {
		return '';
	}
	return '<svg class="sp-ico" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p[ $name ] . '</svg>';
}

/** آدرس عکس (برای پس‌زمینه)؛ اگر نبود، رشته‌ی خالی. */
function sp_img_url( $name ) {
	foreach ( array( 'webp', 'jpg', 'jpeg', 'png' ) as $ext ) {
		$rel = '/assets/img/' . $name . '.' . $ext;
		if ( file_exists( SP_DIR . $rel ) ) {
			return SP_URI . $rel;
		}
	}
	return '';
}
