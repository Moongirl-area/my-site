<?php
/**
 * داده‌ی ماشین‌حساب: واحدها، کتابخانه‌ی مواد و دستورهای آماده.
 * قیمت‌ها اینجا نیستند؛ در inc/prices.php (پیشخوان ← سودپز) نگه داشته می‌شوند.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * واحدها. d = بُعد (m وزن، v حجم، n تعداد)، f = ضریب به واحد پایه (گرم، میلی‌لیتر، عدد).
 */
function sp_units() {
	return array(
		'گرم'            => array( 'd' => 'm', 'f' => 1 ),
		'کیلو'           => array( 'd' => 'm', 'f' => 1000 ),
		'مثقال'          => array( 'd' => 'm', 'f' => 4.608 ),
		'میلی‌لیتر'      => array( 'd' => 'v', 'f' => 1 ),
		'لیتر'           => array( 'd' => 'v', 'f' => 1000 ),
		'پیمانه'         => array( 'd' => 'v', 'f' => 240 ),
		'قاشق غذاخوری'   => array( 'd' => 'v', 'f' => 15 ),
		'قاشق چای‌خوری'  => array( 'd' => 'v', 'f' => 5 ),
		'عدد'            => array( 'd' => 'n', 'f' => 1 ),
	);
}

/** گروه‌های مواد (برای جست‌وجو و پیشخوان). */
function sp_ing_groups() {
	return array(
		'dairy'   => 'لبنیات و تخم‌مرغ',
		'protein' => 'گوشت، مرغ و ماهی',
		'grain'   => 'برنج، آرد و غلات',
		'legume'  => 'حبوبات',
		'veg'     => 'سبزی، صیفی و میوه',
		'fat'     => 'روغن و چربی',
		'sweet'   => 'شکر و مواد قنادی',
		'choc'    => 'شکلات، مغز و خشکبار',
		'spice'   => 'ادویه و چاشنی',
	);
}

/**
 * یک ماده. $unit = واحد خرید (قیمت برای همین واحد است).
 * $d = چگالی (گرم در هر میلی‌لیتر) برای تبدیل پیمانه و قاشق به وزن؛ ۰ یعنی تبدیل نشود.
 * $each = وزن هر عدد به گرم (مثلاً تخم‌مرغ)؛ ۰ یعنی ندارد.
 */
function sp_ing( $name, $group, $unit, $d = 0, $each = 0, $alias = '' ) {
	return array( 'name' => $name, 'group' => $group, 'unit' => $unit, 'd' => $d, 'each' => $each, 'alias' => $alias );
}

/** کتابخانه‌ی مواد. کلیدها ثابت‌اند (قیمت‌ها و دستورها به آن‌ها وصل‌اند). */
function sp_ingredients() {
	static $list = null;
	if ( null !== $list ) {
		return $list;
	}
	$list = array(
		// لبنیات و تخم‌مرغ
		'egg'          => sp_ing( 'تخم‌مرغ', 'dairy', 'عدد', 0, 55, 'تخم مرغ' ),
		'milk'         => sp_ing( 'شیر', 'dairy', 'لیتر', 1.03 ),
		'cream'        => sp_ing( 'خامه‌ی قنادی', 'dairy', 'کیلو', 1, 0, 'خامه قنادی، خامه فرم‌گیر' ),
		'freshcream'   => sp_ing( 'خامه‌ی صبحانه', 'dairy', 'کیلو', 1, 0, 'خامه پاستوریزه' ),
		'butter'       => sp_ing( 'کره‌ی حیوانی', 'dairy', 'کیلو', 0.96, 0, 'کره' ),
		'yogurt'       => sp_ing( 'ماست', 'dairy', 'کیلو', 1.03 ),
		'kashk'        => sp_ing( 'کشک', 'dairy', 'کیلو', 1.05 ),
		'creamcheese'  => sp_ing( 'پنیر خامه‌ای', 'dairy', 'کیلو', 1, 0, 'خامه پنیر' ),
		'mascarpone'   => sp_ing( 'پنیر ماسکارپونه', 'dairy', 'کیلو', 1 ),
		'cheese'       => sp_ing( 'پنیر پیتزا', 'dairy', 'کیلو', 0.45, 0, 'موتزارلا' ),
		'whitecheese'  => sp_ing( 'پنیر سفید', 'dairy', 'کیلو', 1 ),

		// گوشت، مرغ و ماهی
		'meat'         => sp_ing( 'گوشت خورشی (گوسفندی یا گوساله)', 'protein', 'کیلو', 0, 0, 'گوشت قرمز، گوشت گوسفند، گوشت گوساله' ),
		'mince'        => sp_ing( 'گوشت چرخ‌کرده', 'protein', 'کیلو', 0, 0, 'گوشت چرخ کرده' ),
		'lambbone'     => sp_ing( 'گوشت با استخوان (آبگوشتی)', 'protein', 'کیلو', 0, 0, 'گردن، ماهیچه' ),
		'chicken'      => sp_ing( 'مرغ', 'protein', 'کیلو', 0, 0, 'مرغ کامل، ران مرغ' ),
		'chickbreast'  => sp_ing( 'سینه‌ی مرغ', 'protein', 'کیلو', 0, 0, 'فیله مرغ' ),
		'fish'         => sp_ing( 'ماهی', 'protein', 'کیلو', 0, 0, 'ماهی قزل‌آلا' ),
		'sausage'      => sp_ing( 'سوسیس و کالباس', 'protein', 'کیلو' ),

		// برنج، آرد و غلات
		'rice'         => sp_ing( 'برنج ایرانی', 'grain', 'کیلو', 0.8 ),
		'flour'        => sp_ing( 'آرد سفید (شیرینی‌پزی)', 'grain', 'کیلو', 0.53, 0, 'آرد' ),
		'pasta'        => sp_ing( 'ماکارونی (اسپاگتی)', 'grain', 'کیلو' ),
		'bread'        => sp_ing( 'نان (سنگک یا لواش)', 'grain', 'عدد' ),
		'starch'       => sp_ing( 'نشاسته', 'grain', 'کیلو', 0.55, 0, 'نشاسته ذرت' ),
		'breadcrumb'   => sp_ing( 'پودر سوخاری', 'grain', 'کیلو', 0.45, 0, 'پودر نان' ),
		'biscuit'      => sp_ing( 'بیسکویت ساقه‌طلایی', 'grain', 'کیلو', 0, 0, 'بیسکویت' ),
		'ladyfinger'   => sp_ing( 'بیسکویت لیدی‌فینگر', 'grain', 'کیلو', 0, 0, 'بیسکویت تیرامیسو' ),

		// حبوبات
		'splitpea'     => sp_ing( 'لپه', 'legume', 'کیلو', 0.8 ),
		'kidneybean'   => sp_ing( 'لوبیا قرمز', 'legume', 'کیلو', 0.8 ),
		'whitebean'    => sp_ing( 'لوبیا سفید', 'legume', 'کیلو', 0.8 ),
		'pintobean'    => sp_ing( 'لوبیا چیتی', 'legume', 'کیلو', 0.8 ),
		'chickpea'     => sp_ing( 'نخود', 'legume', 'کیلو', 0.8 ),
		'lentil'       => sp_ing( 'عدس', 'legume', 'کیلو', 0.8 ),
		'mungbean'     => sp_ing( 'ماش', 'legume', 'کیلو', 0.8 ),

		// سبزی، صیفی و میوه
		'onion'        => sp_ing( 'پیاز', 'veg', 'کیلو', 0, 150 ),
		'potato'       => sp_ing( 'سیب‌زمینی', 'veg', 'کیلو', 0, 180, 'سیب زمینی' ),
		'tomato'       => sp_ing( 'گوجه‌فرنگی', 'veg', 'کیلو', 0, 120, 'گوجه' ),
		'eggplant'     => sp_ing( 'بادمجان', 'veg', 'کیلو', 0, 250 ),
		'carrot'       => sp_ing( 'هویج', 'veg', 'کیلو', 0, 100 ),
		'garlic'       => sp_ing( 'سیر', 'veg', 'کیلو', 0, 50 ),
		'herbs'        => sp_ing( 'سبزی قورمه (تازه)', 'veg', 'کیلو', 0, 0, 'سبزی خورشتی' ),
		'herbskuku'    => sp_ing( 'سبزی کوکو (تازه)', 'veg', 'کیلو' ),
		'herbspolo'    => sp_ing( 'سبزی پلویی (تازه)', 'veg', 'کیلو' ),
		'greenbean'    => sp_ing( 'لوبیا سبز', 'veg', 'کیلو' ),
		'broadbean'    => sp_ing( 'باقالی (منجمد یا تازه)', 'veg', 'کیلو', 0, 0, 'باقلا' ),
		'peas'         => sp_ing( 'نخود فرنگی', 'veg', 'کیلو', 0.6 ),
		'bellpepper'   => sp_ing( 'فلفل دلمه‌ای', 'veg', 'کیلو', 0, 150 ),
		'mushroom'     => sp_ing( 'قارچ', 'veg', 'کیلو' ),
		'lemon'        => sp_ing( 'لیمو ترش', 'veg', 'کیلو', 0, 80 ),
		'banana'       => sp_ing( 'موز', 'veg', 'کیلو', 0, 120 ),
		'strawberry'   => sp_ing( 'توت‌فرنگی', 'veg', 'کیلو' ),
		'pickle'       => sp_ing( 'خیارشور', 'veg', 'کیلو' ),

		// روغن و چربی
		'oil'          => sp_ing( 'روغن مایع', 'fat', 'لیتر', 0.92, 0, 'روغن آفتابگردان' ),
		'solidoil'     => sp_ing( 'روغن جامد (نیمه‌جامد)', 'fat', 'کیلو', 0.9 ),
		'margarine'    => sp_ing( 'کره‌ی گیاهی (مارگارین)', 'fat', 'کیلو', 0.96 ),

		// شکر و مواد قنادی
		'sugar'        => sp_ing( 'شکر', 'sweet', 'کیلو', 0.85 ),
		'powdersugar'  => sp_ing( 'پودر قند', 'sweet', 'کیلو', 0.5 ),
		'honey'        => sp_ing( 'عسل', 'sweet', 'کیلو', 1.42 ),
		'cocoa'        => sp_ing( 'پودر کاکائو', 'sweet', 'کیلو', 0.42 ),
		'bakingpowder' => sp_ing( 'بیکینگ پودر', 'sweet', 'کیلو', 0.9 ),
		'bakingsoda'   => sp_ing( 'جوش شیرین', 'sweet', 'کیلو', 1.1 ),
		'vanilla'      => sp_ing( 'پودر وانیل', 'sweet', 'کیلو', 0.5, 0, 'وانیل' ),
		'gelatin'      => sp_ing( 'پودر ژلاتین', 'sweet', 'کیلو', 0.6 ),
		'yeast'        => sp_ing( 'خمیرمایه', 'sweet', 'کیلو', 0.6, 0, 'مایه خمیر' ),
		'foodcolor'    => sp_ing( 'رنگ خوراکی', 'sweet', 'کیلو', 1 ),
		'fondant'      => sp_ing( 'فوندانت', 'sweet', 'کیلو' ),
		'coconut'      => sp_ing( 'پودر نارگیل', 'sweet', 'کیلو', 0.35 ),
		'jam'          => sp_ing( 'مربا', 'sweet', 'کیلو', 1.3 ),
		'coffee'       => sp_ing( 'پودر قهوه', 'sweet', 'کیلو', 0.4, 0, 'قهوه فوری' ),
		'rosewater'    => sp_ing( 'گلاب', 'sweet', 'لیتر', 1 ),
		'vinegar'      => sp_ing( 'سرکه', 'sweet', 'لیتر', 1 ),

		// شکلات، مغز و خشکبار
		'chocolate'    => sp_ing( 'شکلات تخته‌ای (تلخ)', 'choc', 'کیلو', 0, 0, 'شکلات' ),
		'whitechoc'    => sp_ing( 'شکلات سفید', 'choc', 'کیلو' ),
		'walnut'       => sp_ing( 'مغز گردو', 'choc', 'کیلو', 0.45, 0, 'گردو' ),
		'pistachio'    => sp_ing( 'مغز پسته', 'choc', 'کیلو', 0.5, 0, 'پسته' ),
		'almond'       => sp_ing( 'مغز بادام', 'choc', 'کیلو', 0.55, 0, 'بادام' ),
		'raisin'       => sp_ing( 'کشمش', 'choc', 'کیلو', 0.6 ),
		'dates'        => sp_ing( 'خرما', 'choc', 'کیلو', 0, 10 ),
		'barberry'     => sp_ing( 'زرشک', 'choc', 'کیلو', 0.3 ),

		// ادویه و چاشنی
		'saffron'      => sp_ing( 'زعفران', 'spice', 'گرم' ),
		'spice'        => sp_ing( 'ادویه و نمک (کلی)', 'spice', 'کیلو', 0.8, 0, 'نمک، فلفل، زردچوبه' ),
		'salt'         => sp_ing( 'نمک', 'spice', 'کیلو', 1.2 ),
		'turmeric'     => sp_ing( 'زردچوبه', 'spice', 'کیلو', 0.5 ),
		'blackpepper'  => sp_ing( 'فلفل سیاه', 'spice', 'کیلو', 0.5 ),
		'cinnamon'     => sp_ing( 'دارچین', 'spice', 'کیلو', 0.5 ),
		'cardamom'     => sp_ing( 'هل', 'spice', 'کیلو', 0.5 ),
		'driedlime'    => sp_ing( 'لیمو عمانی', 'spice', 'کیلو', 0, 8 ),
		'drymint'      => sp_ing( 'نعناع خشک', 'spice', 'کیلو', 0.15 ),
		'drydill'      => sp_ing( 'شوید خشک', 'spice', 'کیلو', 0.15 ),
		'tomatopaste'  => sp_ing( 'رب گوجه‌فرنگی', 'spice', 'کیلو', 1.1, 0, 'رب گوجه' ),
		'pomegranate'  => sp_ing( 'رب انار', 'spice', 'کیلو', 1.3 ),
		'mayo'         => sp_ing( 'سس مایونز', 'spice', 'کیلو', 0.95 ),
		'lemonjuice'   => sp_ing( 'آبلیمو', 'spice', 'لیتر', 1 ),
	);
	return $list;
}

/** دو آشپزخونه‌ی سودپز. */
function sp_kitchens() {
	return array(
		'sweet' => array( 'name' => 'شیرینی و کیک', 'short' => 'شیرینی', 'icon' => 'cake' ),
		'food'  => array( 'name' => 'غذای خانگی',   'short' => 'غذا',    'icon' => 'pot' ),
	);
}

/** واحدهای «خروجی» یک دستور (کیک کیلویی، کاپ‌کیک عددی، غذا پرسی...). */
function sp_yield_units() {
	return array( 'کیلو', 'عدد', 'پرس', 'قالب', 'ظرف', 'سینی' );
}

/**
 * دستورهای آماده. مقدار مواد به «واحد خرید» هر ماده است.
 * yield = [مقدار، واحد] که این مقدار مواد برایش است.
 * hours = ساعت کار دستی، oven = ساعت روشن بودن فر (شیرینی) یا اجاق (غذا).
 * featured = در صفحه‌ی اول هم نشان داده شود.
 * مقدارها تخمین‌اند و باید با آشپز و قناد واقعی چک شوند.
 */
function sp_recipes() {
	$r = array(
		// ---------- شیرینی و کیک ----------
		'chocolate'  => array( 'kitchen' => 'sweet', 'featured' => 1, 'img' => 'cake-chocolate', 'icon' => 'cake', 'color' => '#E8673C', 'name' => 'کیک شکلاتی خامه‌ای', 'yield' => array( 1, 'کیلو' ), 'hours' => 2.5, 'oven' => 0.75,
			'items' => array( 'flour' => 0.15, 'sugar' => 0.18, 'egg' => 3, 'cocoa' => 0.04, 'oil' => 0.1, 'milk' => 0.15, 'bakingpowder' => 0.008, 'cream' => 0.3, 'chocolate' => 0.1 ) ),
		'vanilla'    => array( 'kitchen' => 'sweet', 'featured' => 1, 'img' => 'cake-vanilla', 'icon' => 'cupcake', 'color' => '#E9A23B', 'name' => 'کیک وانیلی خامه‌ای', 'yield' => array( 1, 'کیلو' ), 'hours' => 2.5, 'oven' => 0.7,
			'items' => array( 'flour' => 0.16, 'sugar' => 0.18, 'egg' => 4, 'oil' => 0.06, 'milk' => 0.1, 'bakingpowder' => 0.006, 'vanilla' => 0.002, 'cream' => 0.35 ) ),
		'cheesecake' => array( 'kitchen' => 'sweet', 'featured' => 1, 'img' => 'cake-cheesecake', 'icon' => 'cheese', 'color' => '#149C98', 'name' => 'چیزکیک سرد', 'yield' => array( 1, 'کیلو' ), 'hours' => 1.5, 'oven' => 0,
			'items' => array( 'biscuit' => 0.2, 'butter' => 0.08, 'creamcheese' => 0.35, 'cream' => 0.25, 'sugar' => 0.1, 'gelatin' => 0.01 ) ),
		'carrot'     => array( 'kitchen' => 'sweet', 'featured' => 1, 'img' => 'cake-carrot', 'icon' => 'carrot', 'color' => '#E03A3E', 'name' => 'کیک هویج و گردو', 'yield' => array( 1, 'کیلو' ), 'hours' => 1.5, 'oven' => 1,
			'items' => array( 'flour' => 0.2, 'sugar' => 0.17, 'egg' => 3, 'oil' => 0.15, 'carrot' => 0.25, 'walnut' => 0.08, 'cinnamon' => 0.005, 'bakingpowder' => 0.008 ) ),
		'wetchoc'    => array( 'kitchen' => 'sweet', 'featured' => 1, 'img' => 'cake-brownie', 'icon' => 'choc', 'color' => '#8A4FBF', 'name' => 'کیک خیس شکلاتی', 'yield' => array( 1, 'کیلو' ), 'hours' => 1.5, 'oven' => 0.75,
			'items' => array( 'flour' => 0.15, 'sugar' => 0.2, 'egg' => 3, 'cocoa' => 0.05, 'oil' => 0.12, 'milk' => 0.2, 'chocolate' => 0.12, 'cream' => 0.1, 'bakingpowder' => 0.006 ) ),
		'brownie'    => array( 'kitchen' => 'sweet', 'icon' => 'choc', 'color' => '#6B3E2E', 'name' => 'براونی', 'yield' => array( 1, 'کیلو' ), 'hours' => 1.25, 'oven' => 0.6,
			'items' => array( 'chocolate' => 0.25, 'butter' => 0.2, 'sugar' => 0.25, 'egg' => 4, 'flour' => 0.12, 'cocoa' => 0.03, 'walnut' => 0.1, 'vanilla' => 0.002 ) ),
		'redvelvet'  => array( 'kitchen' => 'sweet', 'icon' => 'cake', 'color' => '#C2364A', 'name' => 'کیک ردولوت', 'yield' => array( 1, 'کیلو' ), 'hours' => 2.5, 'oven' => 0.75,
			'items' => array( 'flour' => 0.2, 'sugar' => 0.2, 'egg' => 2, 'oil' => 0.15, 'yogurt' => 0.2, 'cocoa' => 0.01, 'foodcolor' => 0.01, 'vinegar' => 0.01, 'bakingpowder' => 0.008, 'creamcheese' => 0.25, 'cream' => 0.2, 'powdersugar' => 0.1 ) ),
		'tiramisu'   => array( 'kitchen' => 'sweet', 'icon' => 'cupcake', 'color' => '#8A5A3B', 'name' => 'تیرامیسو', 'yield' => array( 1, 'کیلو' ), 'hours' => 1.5, 'oven' => 0,
			'items' => array( 'mascarpone' => 0.4, 'cream' => 0.2, 'ladyfinger' => 0.2, 'egg' => 3, 'sugar' => 0.1, 'coffee' => 0.02, 'cocoa' => 0.02 ) ),
		'roll'       => array( 'kitchen' => 'sweet', 'icon' => 'cake', 'color' => '#E9A23B', 'name' => 'رولت خامه‌ای', 'yield' => array( 1, 'کیلو' ), 'hours' => 1.5, 'oven' => 0.3,
			'items' => array( 'egg' => 5, 'sugar' => 0.15, 'flour' => 0.12, 'vanilla' => 0.002, 'cream' => 0.3, 'powdersugar' => 0.03, 'strawberry' => 0.15 ) ),
		'sponge'     => array( 'kitchen' => 'sweet', 'icon' => 'cake', 'color' => '#E0A21B', 'name' => 'کیک ساده‌ی عصرانه', 'yield' => array( 1, 'کیلو' ), 'hours' => 1, 'oven' => 0.75,
			'items' => array( 'flour' => 0.25, 'sugar' => 0.25, 'egg' => 5, 'oil' => 0.15, 'milk' => 0.15, 'bakingpowder' => 0.01, 'vanilla' => 0.002 ) ),
		'cupcake'    => array( 'kitchen' => 'sweet', 'icon' => 'cupcake', 'color' => '#D9579A', 'name' => 'کاپ‌کیک خامه‌ای', 'yield' => array( 12, 'عدد' ), 'hours' => 2, 'oven' => 0.5,
			'items' => array( 'flour' => 0.2, 'sugar' => 0.2, 'egg' => 3, 'butter' => 0.1, 'milk' => 0.12, 'bakingpowder' => 0.008, 'vanilla' => 0.002, 'cream' => 0.2, 'foodcolor' => 0.005 ) ),
		'yazdi'      => array( 'kitchen' => 'sweet', 'icon' => 'cupcake', 'color' => '#C98A2B', 'name' => 'کیک یزدی', 'yield' => array( 20, 'عدد' ), 'hours' => 1.5, 'oven' => 0.5,
			'items' => array( 'flour' => 0.3, 'sugar' => 0.3, 'oil' => 0.25, 'yogurt' => 0.2, 'egg' => 5, 'cardamom' => 0.005, 'rosewater' => 0.05, 'bakingpowder' => 0.01, 'pistachio' => 0.03 ) ),

		// ---------- غذای خانگی (برای ۱۰ پرس) ----------
		'ghormeh'    => array( 'kitchen' => 'food', 'featured' => 1, 'img' => 'food-ghormeh', 'icon' => 'pot', 'color' => '#3F8A4E', 'name' => 'قورمه‌سبزی با برنج', 'yield' => array( 10, 'پرس' ), 'hours' => 3, 'oven' => 4,
			'items' => array( 'meat' => 1.0, 'herbs' => 1.0, 'kidneybean' => 0.25, 'onion' => 0.3, 'driedlime' => 0.06, 'oil' => 0.2, 'rice' => 1.2, 'saffron' => 1, 'spice' => 0.03 ) ),
		'gheymeh'    => array( 'kitchen' => 'food', 'featured' => 1, 'img' => 'food-gheymeh', 'icon' => 'bowl', 'color' => '#D9822B', 'name' => 'قیمه با برنج', 'yield' => array( 10, 'پرس' ), 'hours' => 2.5, 'oven' => 3,
			'items' => array( 'meat' => 0.9, 'splitpea' => 0.3, 'onion' => 0.3, 'tomatopaste' => 0.15, 'driedlime' => 0.05, 'potato' => 0.8, 'oil' => 0.35, 'rice' => 1.2, 'saffron' => 1, 'spice' => 0.03 ) ),
		'zereshk'    => array( 'kitchen' => 'food', 'featured' => 1, 'img' => 'food-zereshk', 'icon' => 'drumstick', 'color' => '#C2364A', 'name' => 'زرشک‌پلو با مرغ', 'yield' => array( 10, 'پرس' ), 'hours' => 2, 'oven' => 2,
			'items' => array( 'chicken' => 3.0, 'onion' => 0.4, 'tomatopaste' => 0.1, 'barberry' => 0.12, 'saffron' => 2, 'oil' => 0.2, 'rice' => 1.2, 'spice' => 0.03 ) ),
		'kotlet'     => array( 'kitchen' => 'food', 'featured' => 1, 'img' => 'food-kotlet', 'icon' => 'patty', 'color' => '#8A5A3B', 'name' => 'کتلت', 'yield' => array( 10, 'پرس' ), 'hours' => 2.5, 'oven' => 1.5,
			'items' => array( 'mince' => 1.0, 'potato' => 1.2, 'egg' => 4, 'onion' => 0.3, 'flour' => 0.1, 'oil' => 0.4, 'spice' => 0.03 ) ),
		'macaroni'   => array( 'kitchen' => 'food', 'featured' => 1, 'img' => 'food-macaroni', 'icon' => 'pasta', 'color' => '#E0A21B', 'name' => 'ماکارونی', 'yield' => array( 10, 'پرس' ), 'hours' => 1.5, 'oven' => 1.5,
			'items' => array( 'pasta' => 1.4, 'mince' => 0.8, 'onion' => 0.3, 'tomatopaste' => 0.2, 'potato' => 0.5, 'oil' => 0.25, 'spice' => 0.03 ) ),
		'fesenjan'   => array( 'kitchen' => 'food', 'icon' => 'pot', 'color' => '#7A2E2E', 'name' => 'فسنجان با برنج', 'yield' => array( 10, 'پرس' ), 'hours' => 2.5, 'oven' => 3,
			'items' => array( 'chicken' => 2.5, 'walnut' => 0.8, 'pomegranate' => 0.5, 'onion' => 0.3, 'sugar' => 0.05, 'oil' => 0.1, 'rice' => 1.2, 'saffron' => 1, 'spice' => 0.02 ) ),
		'bademjan'   => array( 'kitchen' => 'food', 'icon' => 'bowl', 'color' => '#6B3FA0', 'name' => 'خورش بادمجان با برنج', 'yield' => array( 10, 'پرس' ), 'hours' => 3, 'oven' => 3,
			'items' => array( 'meat' => 0.9, 'eggplant' => 2.5, 'tomato' => 0.8, 'tomatopaste' => 0.15, 'onion' => 0.4, 'splitpea' => 0.15, 'oil' => 0.5, 'rice' => 1.2, 'saffron' => 1, 'spice' => 0.03 ) ),
		'loobia'     => array( 'kitchen' => 'food', 'icon' => 'pot', 'color' => '#5A9E3E', 'name' => 'لوبیاپلو', 'yield' => array( 10, 'پرس' ), 'hours' => 2, 'oven' => 2,
			'items' => array( 'meat' => 0.8, 'greenbean' => 1.0, 'tomatopaste' => 0.2, 'onion' => 0.4, 'potato' => 0.5, 'oil' => 0.3, 'rice' => 1.5, 'cinnamon' => 0.005, 'spice' => 0.03 ) ),
		'adaspolo'   => array( 'kitchen' => 'food', 'icon' => 'bowl', 'color' => '#A86B2D', 'name' => 'عدس‌پلو با کشمش', 'yield' => array( 10, 'پرس' ), 'hours' => 1.5, 'oven' => 1.5,
			'items' => array( 'lentil' => 0.5, 'rice' => 1.5, 'raisin' => 0.3, 'dates' => 0.2, 'onion' => 0.4, 'mince' => 0.5, 'oil' => 0.3, 'saffron' => 1, 'spice' => 0.02 ) ),
		'tahchin'    => array( 'kitchen' => 'food', 'icon' => 'drumstick', 'color' => '#E3A21A', 'name' => 'ته‌چین مرغ', 'yield' => array( 10, 'پرس' ), 'hours' => 2.5, 'oven' => 2,
			'items' => array( 'chickbreast' => 1.5, 'rice' => 1.5, 'yogurt' => 1.0, 'egg' => 4, 'saffron' => 2, 'barberry' => 0.1, 'oil' => 0.3, 'onion' => 0.2, 'spice' => 0.02 ) ),
		'baghali'    => array( 'kitchen' => 'food', 'icon' => 'drumstick', 'color' => '#4E9A6B', 'name' => 'باقالی‌پلو با مرغ', 'yield' => array( 10, 'پرس' ), 'hours' => 2, 'oven' => 2.5,
			'items' => array( 'chicken' => 2.5, 'rice' => 1.5, 'broadbean' => 1.0, 'drydill' => 0.1, 'oil' => 0.3, 'saffron' => 2, 'onion' => 0.3, 'spice' => 0.03 ) ),
		'kashkbademjan' => array( 'kitchen' => 'food', 'icon' => 'bowl', 'color' => '#8A6B3B', 'name' => 'کشک بادمجان', 'yield' => array( 10, 'پرس' ), 'hours' => 2, 'oven' => 1.5,
			'items' => array( 'eggplant' => 3.0, 'kashk' => 0.6, 'onion' => 0.6, 'garlic' => 0.1, 'walnut' => 0.1, 'drymint' => 0.03, 'oil' => 0.6, 'spice' => 0.02 ) ),
		'kuku'       => array( 'kitchen' => 'food', 'icon' => 'patty', 'color' => '#3F8A4E', 'name' => 'کوکو سبزی', 'yield' => array( 10, 'پرس' ), 'hours' => 1.5, 'oven' => 1,
			'items' => array( 'herbskuku' => 1.2, 'egg' => 10, 'walnut' => 0.1, 'barberry' => 0.05, 'flour' => 0.05, 'oil' => 0.3, 'spice' => 0.02 ) ),
		'olivieh'    => array( 'kitchen' => 'food', 'icon' => 'bowl', 'color' => '#D9B23B', 'name' => 'سالاد الویه', 'yield' => array( 10, 'پرس' ), 'hours' => 1.5, 'oven' => 1.5,
			'items' => array( 'chickbreast' => 0.8, 'potato' => 1.5, 'egg' => 8, 'pickle' => 0.5, 'peas' => 0.3, 'carrot' => 0.3, 'mayo' => 0.6, 'spice' => 0.01 ) ),
		'abgoosht'   => array( 'kitchen' => 'food', 'icon' => 'pot', 'color' => '#B5562B', 'name' => 'آبگوشت (دیزی)', 'yield' => array( 10, 'پرس' ), 'hours' => 1, 'oven' => 4,
			'items' => array( 'lambbone' => 1.2, 'chickpea' => 0.3, 'whitebean' => 0.3, 'potato' => 1.0, 'onion' => 0.4, 'tomato' => 0.5, 'tomatopaste' => 0.1, 'driedlime' => 0.04, 'bread' => 5, 'spice' => 0.02 ) ),
	);
	return $r;
}

/** دستورهای یک آشپزخونه. */
function sp_recipes_of( $kitchen ) {
	return array_filter( sp_recipes(), function ( $r ) use ( $kitchen ) {
		return $r['kitchen'] === $kitchen;
	} );
}

/** دستورهای منتخب صفحه‌ی اول. */
function sp_recipes_featured() {
	return array_filter( sp_recipes(), function ( $r ) {
		return ! empty( $r['featured'] );
	} );
}
