<?php
/**
 * گرفتن هفتگی قیمت مرجع مواد از منبع‌ها (روی هاست ایرانی اجرا می‌شود).
 * نتیجه فقط «پیشنهاد» است: تا مدیر در پیشخوان ← سودپز تأیید نکند، روی سایت نمی‌رود.
 *
 * منبع ۱: نرخ‌نامه‌ی رسمی سازمان میادین میوه و تره‌بار شهرداری تهران (ریال).
 * منبع ۲: میانه‌ی قیمت فروشگاه‌های آنلاین در ایمالز (تومان)، با محاسبه‌ی قیمت هر کیلو/لیتر از وزن داخل اسم کالا.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** نگاشت مواد به نرخ‌نامه‌ی میادین: کلید => [شناسه‌ی دسته، بخشی از اسم کالا، تقسیم بر (مثلاً ۳۰ تخم‌مرغ در شانه)، کلمه‌های ممنوع]. */
function sp_src_mayadin_map() {
	return array(
		'meat'        => array( 10085, 'گوساله - مخلوط بدون استخوان' ),
		'mince'       => array( 10085, 'چرخکرده گوساله تازه' ),
		'lambbone'    => array( 10085, 'گوسفندی - گردن' ),
		'chicken'     => array( 13, 'مرغ کامل تازه' ),
		'chickbreast' => array( 14, 'سینه بدون استخوان' ),
		'fish'        => array( 31, 'قزل' ),
		'egg'         => array( 10099, 'بسته بندی 30 عددی', 30 ),
		'rice'        => array( 10087, 'طارم اصل' ),
		'splitpea'    => array( 7, 'لپه ریز' ),
		'lentil'      => array( 7, 'عدس درشت' ),
		'kidneybean'  => array( 7, 'لوبیا قرمز' ),
		'whitebean'   => array( 7, 'لوبیا سفید' ),
		'pintobean'   => array( 7, 'لوبیا چیتی' ),
		'chickpea'    => array( 7, 'نخود', 1, 'فرنگی|نخودچی' ),
		'mungbean'    => array( 7, 'ماش' ),
		'barberry'    => array( 7, 'زرشک پلویی' ),
		'onion'       => array( 10079, 'پیاز' ),
		'potato'      => array( 10079, 'سیب زمینی' ),
		'tomato'      => array( 10079, 'گوجه فرنگی گلخانه' ),
		'eggplant'    => array( 10079, 'بادمجان', 1, 'دلمه' ),
		'carrot'      => array( 10079, 'هویج' ),
		'garlic'      => array( 10079, 'سیر خشک' ),
		'herbs'       => array( 10079, 'سبزی پاک کرده' ),
		'herbskuku'   => array( 10079, 'سبزی پاک کرده' ),
		'herbspolo'   => array( 10079, 'سبزی پاک کرده' ),
		'greenbean'   => array( 10079, 'لوبیا سبز' ),
		'broadbean'   => array( 10079, 'باقلا' ),
		'peas'        => array( 10079, 'نخود فرنگی' ),
		'bellpepper'  => array( 10079, 'فلفل دلمه سبز' ),
		'lemon'       => array( 10078, 'لیموترش' ),
		'banana'      => array( 10078, 'موز خوشه ای ایرانی' ),
		'strawberry'  => array( 10078, 'توت فرنگی' ),
		'mushroom'    => array( 8, 'قارچ دکمه ای پشت بسته فله' ),
		'pickle'      => array( 51, 'خیارشور ممتاز' ),
		'sugar'       => array( 35, 'شکر فله' ),
		'dates'       => array( 10, 'رطب مضافتی بم (کارتنی' ),
		'walnut'      => array( 34, 'مغز گردو' ),
		'pistachio'   => array( 34, 'مغز پسته' ),
		'almond'      => array( 34, 'مغز بادام' ),
		'raisin'      => array( 34, 'کشمش' ),
		'whitecheese' => array( 10069, 'پنیرسفیدپاستوریزه' ),
	);
}

/** نگاشت مواد به جست‌وجوی ایمالز: کلید => [عبارت جست‌وجو، کلمه‌ی لازم در اسم کالا (regex)، کلمه‌های ممنوع (regex)]. */
function sp_src_emalls_map() {
	return array(
		'milk'         => array( 'شیر پاستوریزه', '^شیر', 'خشک|کاکائو|موز|عسل|قهوه|پودر|نارگیل|بادام|سویا|طعم|قند|شکلات|کره|خامه|تخم|شتر|بز|گاومیش' ),
		'cream'        => array( 'خامه قنادی', 'خامه', 'اسپری|پودر' ),
		'freshcream'   => array( 'خامه صبحانه', 'خامه', 'اسپری|پودر|قنادی' ),
		'butter'       => array( 'کره حیوانی', 'کره', 'بادام|کنجد|گیاهی|پسته|نارگیل|کاکائو|ارده|فندق|گردو' ),
		'yogurt'       => array( 'ماست', 'ماست', 'موسیر|خیار|بستنی|نوشیدنی|دوغ|خامه|میوه' ),
		'kashk'        => array( 'کشک', 'کشک', 'خشک|بادمجان|پودر|بادنجان' ),
		'creamcheese'  => array( 'پنیر خامه ای', 'پنیر', 'پیتزا|ورقه' ),
		'mascarpone'   => array( 'ماسکارپونه', 'ماسکارپونه', '' ),
		'cheese'       => array( 'پنیر پیتزا', 'پیتزا', 'ورقه|سس' ),
		'flour'        => array( 'آرد سفید', 'آرد', 'سوخاری|برنج|نخودچی|ذرت|جو|سبوس|سنگک|کیک|کامل|بادام|سویا' ),
		'pasta'        => array( 'اسپاگتی', 'اسپاگتی|ماکارونی', 'سس|آماده' ),
		'starch'       => array( 'نشاسته ذرت', 'نشاسته', '' ),
		'breadcrumb'   => array( 'پودر سوخاری', 'سوخاری', '' ),
		'biscuit'      => array( 'بیسکویت ساقه طلایی', 'ساقه طلایی', 'کرمدار|شکلاتی' ),
		'ladyfinger'   => array( 'لیدی فینگر', 'لیدی', '' ),
		'oil'          => array( 'روغن مایع آفتابگردان', 'روغن', 'جامد|اسپری|زیتون|کنجد|بذر|کتان|حیوانی|کره|ماساژ|مو' ),
		'solidoil'     => array( 'روغن نیمه جامد', 'روغن', 'مایع|زیتون' ),
		'margarine'    => array( 'کره گیاهی مارگارین', 'مارگارین|گیاهی', '' ),
		'powdersugar'  => array( 'پودر قند', 'پودر قند', '' ),
		'honey'        => array( 'عسل طبیعی', '^عسل', 'موم|شانه|ماسک|شامپو|کرم|صابون|آبنبات|ارده|شیر|سیاه دانه' ),
		'cocoa'        => array( 'پودر کاکائو', 'کاکائو', 'شیر|نوشیدنی|هات|فوری|کیک|کره|شکلات' ),
		'bakingpowder' => array( 'بیکینگ پودر', 'بیکینگ', '' ),
		'bakingsoda'   => array( 'جوش شیرین', '^جوش شیرین', 'اسپری|شوینده' ),
		'vanilla'      => array( 'پودر وانیل', 'وانیل', 'اسانس|مایع|عطر|شمع|بستنی' ),
		'gelatin'      => array( 'پودر ژلاتین', 'ژلاتین', 'ژله|کلاژن|کپسول' ),
		'yeast'        => array( 'خمیر مایه', 'مایه', 'نان' ),
		'fondant'      => array( 'فوندانت', 'فوندانت', 'قالب|ابزار|کاتر' ),
		'coconut'      => array( 'پودر نارگیل', '^پودر نارگیل', 'روغن|شیر|کره' ),
		'jam'          => array( 'مربا', 'مربا', '' ),
		'coffee'       => array( 'قهوه فوری', 'قهوه', 'دستگاه|ماگ|فنجان|آسیاب|ساشه' ),
		'rosewater'    => array( 'گلاب', 'گلاب', 'اسانس|اسپری|تونر|پاک|کرم|ماسک|عطر|شربت' ),
		'vinegar'      => array( 'سرکه سفید', 'سرکه', 'سیب|بالزامیک|انگور قرمز' ),
		'chocolate'    => array( 'شکلات تلخ', 'شکلات', 'صبحانه|کیک|بیسکویت|کره|سس|ویفر|نوشیدنی|بستنی' ),
		'whitechoc'    => array( 'شکلات سفید', 'شکلات', 'صبحانه|کیک|بیسکویت|سس|ویفر' ),
		'saffron'      => array( 'زعفران', 'زعفران', 'بستنی|نبات|چای|عرق|شربت|حلوا|کیک|شیر|آبنبات|عسل|قهوه' ),
		'spice'        => array( 'ادویه کاری', '^ادویه', 'آسیاب|ظرف|جا ادویه|نگهدارنده|قوطی' ),
		'salt'         => array( 'نمک یددار', '^نمک', 'صورتی|دریا|ظرف|سنگ' ),
		'turmeric'     => array( 'زردچوبه', 'زردچوبه', '' ),
		'blackpepper'  => array( 'فلفل سیاه', '^(پودر )?فلفل سیاه', 'آسیاب|دستگاه|چیپس|پفک' ),
		'cinnamon'     => array( 'دارچین', 'دارچین', 'چای|عطر|شمع' ),
		'cardamom'     => array( 'هل سبز', 'هل', 'چای|عطر' ),
		'driedlime'    => array( 'لیمو عمانی', 'لیمو عمانی', '' ),
		'drymint'      => array( 'نعناع خشک', 'نعناع', 'عرق|اسانس|چای|خمیر' ),
		'drydill'      => array( 'شوید خشک', '^شوید', 'عرق|اسانس' ),
		'tomatopaste'  => array( 'رب گوجه فرنگی', 'رب گوجه', 'سس' ),
		'pomegranate'  => array( 'رب انار', 'رب انار', 'سس|ترش' ),
		'mayo'         => array( 'سس مایونز', 'مایونز', 'کم چرب' ),
		'lemonjuice'   => array( 'آبلیمو', 'آبلیمو|آب لیمو', 'اسانس|پودر|دستگاه' ),
	);
}

/** عدد فارسی/عربی به انگلیسی و یکدست کردن حروف. */
function sp_src_norm( $s ) {
	$s = sp_en_digits( (string) $s );
	$s = str_replace( array( 'ي', 'ك', "\u{200c}", '٫' ), array( 'ی', 'ک', ' ', '.' ), $s );
	return trim( preg_replace( '/\s+/u', ' ', $s ) );
}

function sp_src_get( $url ) {
	$r = wp_remote_get( $url, array(
		'timeout'    => 25,
		'user-agent' => 'Mozilla/5.0 (compatible; SoodpazPriceBot/1.0; +https://soodpaz.com)',
		'headers'    => array( 'Accept-Language' => 'fa-IR,fa;q=0.9' ),
	) );
	if ( is_wp_error( $r ) || 200 !== (int) wp_remote_retrieve_response_code( $r ) ) {
		return '';
	}
	return (string) wp_remote_retrieve_body( $r );
}

function sp_src_dom( $html ) {
	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8"?>' . $html );
	libxml_clear_errors();
	return new DOMXPath( $doc );
}

/** کلاس CSS در XPath. */
function sp_src_cls( $c ) {
	return "contains(concat(' ', normalize-space(@class), ' '), ' $c ')";
}

/**
 * میادین: همه‌ی کالاهای یک دسته‌ی نرخ‌نامه.
 * خروجی: [ [title, unit, prices[] (ریال)], ... ]
 */
function sp_src_mayadin_cat( $id ) {
	$url  = 'https://mayadin.tehran.ir/' . rawurlencode( 'خدمات' ) . '/' . rawurlencode( 'نرخنامه-ها' ) . '/ctl/PriceListClassification/mid/2307?PriceListId=' . (int) $id;
	$html = sp_src_get( $url );
	if ( ! $html ) {
		return array();
	}
	$xp  = sp_src_dom( $html );
	$out = array();
	foreach ( $xp->query( '//div[' . sp_src_cls( 'classification-tile' ) . ']' ) as $tile ) {
		$t = $xp->query( './/span[' . sp_src_cls( 'title' ) . ']', $tile )->item( 0 );
		if ( ! $t ) {
			continue;
		}
		$prices = array();
		$unit   = '';
		foreach ( $xp->query( './/span[' . sp_src_cls( 'PriceList' ) . ']', $tile ) as $sp ) {
			$txt = sp_src_norm( $sp->textContent );
			if ( '' === $unit && false !== mb_strpos( $txt, 'قیمت هر' ) ) {
				$unit = trim( str_replace( 'قیمت هر', '', $txt ) );
			}
			if ( preg_match( '/:\s*(\d{4,})/', $txt, $m ) ) {
				$prices[] = (float) $m[1];
			}
		}
		$out[] = array( sp_src_norm( $t->textContent ), $unit, $prices );
	}
	return $out;
}

/** میادین: پیشنهاد قیمت برای همه‌ی مواد نگاشت‌شده (تومان). */
function sp_src_mayadin() {
	$cache = array();
	$res   = array();
	foreach ( sp_src_mayadin_map() as $key => $m ) {
		list( $id, $pat ) = $m;
		$div              = isset( $m[2] ) ? (float) $m[2] : 1;
		$bad              = isset( $m[3] ) ? $m[3] : '';
		if ( ! isset( $cache[ $id ] ) ) {
			$cache[ $id ] = sp_src_mayadin_cat( $id );
		}
		foreach ( $cache[ $id ] as $item ) {
			list( $title, $unit, $prices ) = $item;
			if ( false === mb_strpos( $title, sp_src_norm( $pat ) ) || ( $bad && preg_match( '/' . $bad . '/u', $title ) ) || ! $prices ) {
				continue;
			}
			$res[ $key ] = array(
				'p'    => round( $prices[0] / 10 / $div ),
				'src'  => 'mayadin',
				'note' => $title . ( $div > 1 ? ' (÷' . (int) $div . ')' : '' ),
				'url'  => 'https://mayadin.tehran.ir/' . rawurlencode( 'خدمات' ) . '/' . rawurlencode( 'نرخنامه-ها' ) . '/ctl/PriceListClassification/mid/2307?PriceListId=' . (int) $id,
			);
			break;
		}
	}
	return $res;
}

/**
 * وزن یا حجم داخل اسم کالا. خروجی: array( 'g' => گرم یا null، 'v' => میلی‌لیتر یا null ) یا null.
 * «۲۰ گرمی (۱۰۰ عددی)» = ۲۰۰۰ گرم.
 */
function sp_src_amount( $t ) {
	$t = sp_src_norm( $t );
	$g = null;
	$v = null;
	if ( preg_match( '/(\d+(?:\.\d+)?)\s*(?:کیلو ?گرمی|کیلو ?گرم|کیلویی|کیلو|kg)/iu', $t, $m ) ) {
		$g = $m[1] * 1000;
	} elseif ( preg_match( '/(\d+(?:\.\d+)?)\s*(?:گرمی|گرم|gr\b|g\b)/iu', $t, $m ) ) {
		$g = (float) $m[1];
	} elseif ( preg_match( '/(\d+(?:\.\d+)?)\s*(?:میلی ?لیتری|میلی ?لیتر|میل\b|سی ?سی|cc\b|ml\b)/iu', $t, $m ) ) {
		$v = (float) $m[1];
	} elseif ( preg_match( '/(\d+(?:\.\d+)?)\s*(?:لیتری|لیتر)/u', $t, $m ) ) {
		$v = $m[1] * 1000;
	} elseif ( preg_match( '/(\d+(?:\.\d+)?)\s*مثقال/u', $t, $m ) ) {
		$g = $m[1] * 4.608;
	} elseif ( preg_match( '/نیم ?مثقال/u', $t ) ) {
		$g = 2.304;
	} elseif ( preg_match( '/ربع ?مثقال/u', $t ) ) {
		$g = 1.152;
	} elseif ( preg_match( '/(یک|1)\s*مثقال/u', $t ) ) {
		$g = 4.608;
	} elseif ( preg_match( '/نیم ?کیلو/u', $t ) ) {
		$g = 500;
	} elseif ( preg_match( '/یک ?کیلو/u', $t ) ) {
		$g = 1000;
	} elseif ( preg_match( '/نیم ?لیتر/u', $t ) ) {
		$v = 500;
	} elseif ( preg_match( '/یک ?لیتر/u', $t ) ) {
		$v = 1000;
	}
	if ( null === $g && null === $v ) {
		return null;
	}
	$mult = 1;
	if ( preg_match( '/(\d+)\s*عدد/u', $t, $m ) && (int) $m[1] > 1 && (int) $m[1] <= 200 ) {
		$mult = (int) $m[1];
	}
	return array( 'g' => null !== $g ? $g * $mult : null, 'v' => null !== $v ? $v * $mult : null );
}

/** مقدار (گرم/میلی‌لیتر) به واحد خرید ماده؛ null اگر تبدیل‌پذیر نیست. */
function sp_src_to_buy( $a, $ing ) {
	$u = sp_units();
	if ( ! isset( $u[ $ing['unit'] ] ) ) {
		return null;
	}
	$b = $u[ $ing['unit'] ];
	$d = (float) $ing['d'];
	if ( 'm' === $b['d'] ) {
		$g = null !== $a['g'] ? $a['g'] : ( $d > 0 && null !== $a['v'] ? $a['v'] * $d : null );
		return $g ? $g / $b['f'] : null;
	}
	if ( 'v' === $b['d'] ) {
		$ml = null !== $a['v'] ? $a['v'] : ( $d > 0 && null !== $a['g'] ? $a['g'] / $d : null );
		return $ml ? $ml / $b['f'] : null;
	}
	return null;
}

function sp_src_median( $a ) {
	sort( $a );
	$n = count( $a );
	if ( ! $n ) {
		return 0;
	}
	return $n % 2 ? $a[ ( $n - 1 ) / 2 ] : ( $a[ $n / 2 - 1 ] + $a[ $n / 2 ] ) / 2;
}

/** ایمالز: قیمت هر واحد خرید برای یک ماده (تومان)، یا null. */
function sp_src_emalls_url( $q ) {
	return 'https://emalls.ir/' . rawurlencode( 'لیست-قیمت' ) . '~skey~' . rawurlencode( $q );
}

/** ایمالز: قیمت هر واحد خرید برای یک ماده (تومان)، یا null.
 *  اگر جست‌وجوی ساده کالای وزن‌دار کم داشت، یک بار هم با کلمه‌ی «گرم»/«لیتر» می‌گردد (اسم کالاها وزن‌دار می‌شود). */
function sp_src_emalls_one( $key, $q, $must, $bad ) {
	$ings = sp_ingredients();
	$u    = sp_units();
	$dim  = isset( $u[ $ings[ $key ]['unit'] ] ) ? $u[ $ings[ $key ]['unit'] ]['d'] : 'm';
	$url  = sp_src_emalls_url( $q );
	$vals = sp_src_emalls_vals( $url, $key, $must, $bad );
	if ( count( $vals ) < 6 ) {
		usleep( 1200000 );
		$url2 = sp_src_emalls_url( $q . ( 'v' === $dim ? ' لیتر' : ' گرم' ) );
		$v2   = sp_src_emalls_vals( $url2, $key, $must, $bad );
		if ( count( $v2 ) > count( $vals ) ) {
			$vals = $v2;
			$url  = $url2;
		}
	}
	return sp_src_emalls_pick( $vals, $url );
}

/** همه‌ی قیمت‌های واحدِ قابل‌استفاده‌ی یک صفحه‌ی جست‌وجو. */
function sp_src_emalls_vals( $url, $key, $must, $bad ) {
	$ings = sp_ingredients();
	$html = sp_src_get( $url );
	if ( ! $html ) {
		return array();
	}
	$xp   = sp_src_dom( $html );
	$vals = array();
	foreach ( $xp->query( '//div[' . sp_src_cls( 'product-block' ) . ']' ) as $b ) {
		$a  = $xp->query( './/*[' . sp_src_cls( 'prd-name' ) . ']', $b )->item( 0 );
		$pb = $xp->query( './/*[' . sp_src_cls( 'price-box' ) . ']', $b )->item( 0 );
		if ( ! $a || ! $pb ) {
			continue;
		}
		$t = sp_src_norm( $a->textContent );
		if ( ! preg_match( '/' . $must . '/u', $t ) || ( $bad && preg_match( '/' . $bad . '/u', $t ) ) ) {
			continue;
		}
		// فقط اولین عدد (بعضی کالاها بازه‌ی «از … تا …» دارند)
		$price = preg_match( '/\d[\d,]{3,}/', sp_src_norm( $pb->textContent ), $pm ) ? (float) str_replace( ',', '', $pm[0] ) : 0;
		$am    = sp_src_amount( $t );
		if ( ! $price || ! $am ) {
			continue;
		}
		$qty = sp_src_to_buy( $am, $ings[ $key ] );
		if ( $qty ) {
			$vals[] = $price / $qty;
		}
	}
	return $vals;
}

/** میانه‌ی بدون داده‌های پرت؛ حداقل ۳ کالا لازم است. */
function sp_src_emalls_pick( $vals, $url ) {
	$m0   = sp_src_median( $vals );
	$kept = array_values( array_filter( $vals, function ( $x ) use ( $m0 ) { return $x >= $m0 / 2 && $x <= $m0 * 2; } ) );
	if ( count( $kept ) < 3 ) {
		return null;
	}
	$m = sp_src_median( $kept );
	return array(
		'p'    => round( $m, $m >= 1000 ? -2 : 0 ),
		'src'  => 'emalls',
		'note' => 'میانه‌ی ' . count( $kept ) . ' کالا، از ' . number_format( min( $kept ) ) . ' تا ' . number_format( max( $kept ) ),
		'url'  => $url,
	);
}

function sp_src_emalls() {
	$res = array();
	foreach ( sp_src_emalls_map() as $key => $m ) {
		$r = sp_src_emalls_one( $key, $m[0], $m[1], $m[2] );
		if ( $r ) {
			$res[ $key ] = $r;
		}
		usleep( 1200000 ); // فاصله بین درخواست‌ها، برای فشار نیاوردن به سایت
	}
	return $res;
}

/**
 * همه‌ی منبع‌ها را می‌گیرد و پیشنهادهای جدید را ذخیره می‌کند.
 * پیشنهاد فقط وقتی ثبت می‌شود که با قیمت تأییدشده‌ی فعلی بیش از ۱٪ فرق داشته باشد.
 */
function sp_fetch_prices() {
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore
	}
	$found = sp_src_mayadin() + sp_src_emalls(); // میادین اولویت دارد
	$info  = sp_price_info();
	$props = sp_price_proposals();
	$today = current_time( 'Y-m-d' );
	$new   = 0;
	foreach ( $found as $key => $r ) {
		if ( ! isset( $info[ $key ] ) || $r['p'] <= 0 ) {
			continue;
		}
		$cur = $info[ $key ]['p'];
		if ( $cur && abs( $r['p'] - $cur ) / $cur < 0.01 ) {
			unset( $props[ $key ] );
			continue;
		}
		$r['date']     = $today;
		$props[ $key ] = $r;
		$new++;
	}
	update_option( 'sp_price_proposals', $props, false );
	$total = count( sp_ingredients() );
	update_option( 'sp_price_fetch_log', array(
		'time'    => current_time( 'Y-m-d H:i' ),
		'summary' => count( $found ) . ' ماده از ' . $total . ' پیدا شد، ' . $new . ' پیشنهاد تازه',
		'missing' => array_values( array_diff( array_keys( sp_ingredients() ), array_keys( $found ) ) ),
	), false );
	return $new;
}

/* هر هفته، صبح شنبه (اگر سایت بازدید داشته باشد، وردپرس اجرایش می‌کند). */
add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'sp_weekly_prices' ) ) {
		$next = strtotime( 'next saturday 06:30', current_time( 'timestamp' ) ) - (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		wp_schedule_event( $next, 'weekly', 'sp_weekly_prices' );
	}
} );
add_action( 'sp_weekly_prices', 'sp_fetch_prices' );

/* دکمه‌ی «همین الان بگیر» در پیشخوان. */
add_action( 'admin_post_sp_fetch_prices', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'دسترسی ندارید.' );
	}
	check_admin_referer( 'sp_fetch_prices' );
	sp_fetch_prices();
	wp_safe_redirect( admin_url( 'admin.php?page=soodpaz&fetched=1' ) );
	exit;
} );
