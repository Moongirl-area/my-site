<?php
get_header();
$sp_recipes = sp_recipes();
$sp_ings    = sp_ingredients();
$sp_wood    = sp_img_url( 'wood' );
$sp_firsts  = array(
	'sweet' => array_key_first( sp_recipes_of( 'sweet' ) ),
	'food'  => array_key_first( sp_recipes_of( 'food' ) ),
);
?>

<!-- ۱. بالای صفحه: هر دو آشپزخونه -->
<section class="sp-hero">
	<div class="sp-wrap sp-hero__grid">
		<div class="sp-hero__text">
			<p class="sp-script">رفیق حسابگر آشپزخونه</p>
			<h1 class="sp-display">قورمه‌سبزی و کیکت چند تموم می‌شه؟</h1>
			<p class="sp-lead">دقیق بدون، با سود بفروش. سودپز قیمت تمام‌شده و قیمت فروش هر کیک و هر پرس غذای خانگیت رو حساب می‌کنه؛ گوشت یا خامه هم که گرون شد، همه‌ی قیمت‌ها با یه کلیک درست می‌شن.</p>

			<div class="sp-doors">
				<a class="sp-door" href="<?php echo esc_url( add_query_arg( 'k', 'sweet', sp_url( 'calculator' ) ) ); ?>" style="--c:#E8673C">
					<span class="sp-door__ico"><?php echo sp_icon( 'cake', 26 ); ?></span>
					<span><small>قناد خانگی هستم</small><b>قیمت کیکم رو حساب کن</b></span>
					<?php echo sp_icon( 'arrow', 20 ); ?>
				</a>
				<a class="sp-door" href="<?php echo esc_url( add_query_arg( 'k', 'food', sp_url( 'calculator' ) ) ); ?>" style="--c:#3F8A4E">
					<span class="sp-door__ico"><?php echo sp_icon( 'pot', 26 ); ?></span>
					<span><small>غذای خانگی می‌فروشم</small><b>قیمت هر پرس رو حساب کن</b></span>
					<?php echo sp_icon( 'arrow', 20 ); ?>
				</a>
			</div>

			<ul class="sp-meta">
				<li><?php echo sp_icon( 'clock', 20 ); ?><span><small>زمان</small>۲ دقیقه</span></li>
				<li><?php echo sp_icon( 'user', 20 ); ?><span><small>ثبت‌نام</small>لازم نیست</span></li>
				<li><?php echo sp_icon( 'phone', 20 ); ?><span><small>روی</small>گوشی</span></li>
			</ul>
		</div>

		<div class="sp-hero__art">
			<span class="sp-blob sp-blob--a" aria-hidden="true"></span>
			<span class="sp-blob sp-blob--b" aria-hidden="true"></span>
			<?php
			foreach ( array( 'ing-eggs' => 1, 'ing-chocolate' => 2, 'ing-flour' => 3, 'ing-butter' => 4 ) as $sp_ing_img => $sp_n ) {
				$sp_ing_url = sp_img_url( $sp_ing_img );
				if ( $sp_ing_url ) {
					echo '<span class="sp-float sp-float--' . (int) $sp_n . '" aria-hidden="true"><img src="' . esc_url( $sp_ing_url ) . '" alt="" width="72" height="72" decoding="async"></span>';
				}
			}
			?>
			<div class="sp-plate sp-plate--hero">
				<?php echo sp_img( 'hero-cake', 'کیک شکلاتی خانگی از بالا', 'sp-plate__img', true ); ?>
			</div>
			<div class="sp-plate sp-plate--hero2">
				<?php echo sp_img( 'food-ghormeh', 'قورمه‌سبزی با برنج', 'sp-plate__img', true ); ?>
			</div>

			<div class="sp-widget sp-widget--price">
				<span class="sp-chip">نمونه</span>
				<div class="sp-widget__title"><?php echo sp_icon( 'cake', 18 ); ?> کیک شکلاتی، هر کیلو</div>
				<div class="sp-widget__row"><span>تمام‌شده</span><b>۶۴۲ هزار</b></div>
				<div class="sp-widget__big"><span>بفروش</span><b>۹۲۰ هزار</b></div>
				<div class="sp-widget__good"><?php echo sp_icon( 'up', 16 ); ?> ۳۰٪ سود</div>
			</div>

			<?php echo sp_soodi( 'hero', 'sp-soodi--hero', 'سودی، رفیق حسابگر آشپزخونه', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

			<div class="sp-widget sp-widget--alert">
				<span class="sp-widget__ico"><?php echo sp_icon( 'bell', 18 ); ?></span>
				<p><b>گوشت گرون شد.</b> قیمت ۳ غذات عوض شد؛ ببین.</p>
			</div>
		</div>
	</div>
</section>

<!-- ۲. سوئیچ آشپزخونه + دردها -->
<section class="sp-pains">
	<div class="sp-wrap">
		<?php sp_kitchen_switch(); ?>
		<div class="sp-pains__grid" data-only="sweet">
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E03A3E"><?php echo sp_icon( 'tag', 22 ); ?></span><p>دو ماه پیش قیمت بستی، الان خامه و شکلات گرون‌ترن.</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E9A23B"><?php echo sp_icon( 'flame', 22 ); ?></span><p>دستمزد خودت و گاز فر رو حساب کردی؟</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#149C98"><?php echo sp_icon( 'calc', 22 ); ?></span><p>مطمئنی روی همه‌ی کیک‌هات سود می‌کنی؟</p></div>
		</div>
		<div class="sp-pains__grid" data-only="food">
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E03A3E"><?php echo sp_icon( 'tag', 22 ); ?></span><p>منوی این هفته رو با قیمت گوشت ماه پیش بستی؟</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#E9A23B"><?php echo sp_icon( 'box', 22 ); ?></span><p>ظرف، گاز چهارساعته‌ی اجاق و دستمزدت رو حساب کردی؟</p></div>
			<div class="sp-pain"><span class="sp-pain__ico" style="--c:#149C98"><?php echo sp_icon( 'calc', 22 ); ?></span><p>قیمت رستوران بغلی رو کپی کردی؟ هزینه‌ی اون با تو فرق داره.</p></div>
		</div>
	</div>
</section>

<!-- نوار متحرک دستورها و مواد -->
<div class="sp-marquee" aria-hidden="true">
	<div class="sp-marquee__band sp-marquee__band--a">
		<div class="sp-marquee__track">
			<?php for ( $sp_i = 0; $sp_i < 2; $sp_i++ ) : foreach ( $sp_recipes as $sp_r ) : ?>
				<span class="sp-marquee__item"><?php echo sp_icon( $sp_r['icon'], 22 ); ?><?php echo esc_html( $sp_r['name'] ); ?></span><span class="sp-marquee__sep">✦</span>
			<?php endforeach; endfor; ?>
		</div>
	</div>
	<div class="sp-marquee__band sp-marquee__band--b">
		<div class="sp-marquee__track sp-marquee__track--rev">
			<?php
			$sp_words = array( 'خامه', 'شکلات', 'گوشت', 'برنج', 'زعفران', 'کره', 'تخم‌مرغ', 'آرد', 'سبزی', 'لپه', 'مرغ', 'گردو' );
			for ( $sp_i = 0; $sp_i < 2; $sp_i++ ) :
				foreach ( $sp_words as $sp_w ) :
					?>
				<span class="sp-marquee__item">قیمت روز <?php echo esc_html( $sp_w ); ?></span><span class="sp-marquee__sep">●</span>
					<?php
				endforeach;
			endfor;
			?>
		</div>
	</div>
</div>

<!-- ۳. حساب سرانگشتی در برابر سودپز (اینفوگرافیک با نوار هزینه) -->
<?php
/*
 * داده‌ی مقایسه (هزار تومان؛ عددها نمونه‌اند).
 * parts: [برچسب، مقدار، نوع رنگ، حساب شد؟]
 */
$sp_cmp = array(
	'sweet' => array(
		'note'  => 'کیک شکلاتی ۱ کیلویی (عددها نمونه‌ان).',
		'scale' => 920,
		'bad'   => array( 'parts' => array( array( 'مواد', 420, 'mat', 1 ), array( 'دستمزد ۲٫۵ ساعت', 187, 'lab', 0 ), array( 'گاز فر و جعبه', 35, 'gas', 0 ) ), 'extra' => array( '«یه کم بذارم روش»', '+ ۱۳۰ هزار' ), 'sale' => 550, 'cost' => 642, 'end' => 'هزینه‌ی واقعی ۶۴۲ هزاره؛ از هر کیک ۹۲ هزار تومان ضرر.' ),
		'good'  => array( 'parts' => array( array( 'مواد با قیمت امروز', 420, 'mat', 1 ), array( 'دستمزد ۲٫۵ ساعت', 187, 'lab', 1 ), array( 'گاز فر و جعبه', 35, 'gas', 1 ) ), 'sale' => 920, 'cost' => 642, 'end' => 'هم دستمزدت رو گرفتی، هم ۲۷۸ هزار تومان سود.' ),
	),
	'food'  => array(
		'note'  => 'قورمه‌سبزی با برنج، هر پرس (از یه دیگ ۱۰ پرسی؛ عددها نمونه‌ان).',
		'scale' => 386,
		'bad'   => array( 'parts' => array( array( 'مواد هر پرس', 220, 'mat', 1 ), array( 'دستمزد (۳ ساعت برای ۱۰ پرس)', 30, 'lab', 0 ), array( 'گاز ۴ ساعته و ظرف', 20, 'gas', 0 ) ), 'extra' => array( '«رستوران بغلی چند می‌ده؟»', '۲۵۰ هزار' ), 'sale' => 250, 'cost' => 270, 'end' => 'هر پرس ۲۰ هزار ضرر؛ با ۵۰ پرس در هفته، یعنی ۱ میلیون تومان.' ),
		'good'  => array( 'parts' => array( array( 'مواد با قیمت امروز', 220, 'mat', 1 ), array( 'سهم دستمزد', 30, 'lab', 1 ), array( 'سهم گاز و ظرف', 20, 'gas', 1 ) ), 'sale' => 386, 'cost' => 270, 'end' => 'هم دستمزدت رو گرفتی، هم از هر پرس ۱۱۶ هزار تومان سود.' ),
	),
);
if ( ! function_exists( 'sp_cmp_card' ) ) {
	function sp_cmp_card( $d, $scale, $good ) {
		$pct  = function ( $v ) use ( $scale ) { return round( $v / $scale * 100, 2 ); };
		$diff = $d['sale'] - $d['cost'];
		ob_start();
		?>
		<div class="sp-compare__card sp-compare__card--<?php echo $good ? 'good' : 'bad'; ?>">
			<div class="sp-cmp__head">
				<span class="sp-cmp__ico"><?php echo sp_icon( $good ? 'check' : 'calc', 22 ); ?></span>
				<div><h3><?php echo $good ? 'با سودپز' : 'حساب سرانگشتی'; ?></h3><small><?php echo $good ? 'همه‌ی هزینه‌ها، با قیمت امروز' : 'فقط مواد، بقیه حدسی'; ?></small></div>
				<span class="sp-cmp__result"><bdi dir="ltr"><?php echo $diff >= 0 ? '+' : '−'; ?><?php echo esc_html( sp_fa( abs( $diff ) ) ); ?></bdi><small>هزار <?php echo $diff >= 0 ? 'سود' : 'ضرر'; ?></small></span>
			</div>

			<div class="sp-cmp__bar" role="img" aria-label="<?php echo esc_attr( 'هزینه‌ی واقعی ' . sp_fa( $d['cost'] ) . ' هزار، قیمت فروش ' . sp_fa( $d['sale'] ) . ' هزار' ); ?>">
				<div class="sp-cmp__fill">
					<?php foreach ( $d['parts'] as $p ) : ?>
						<span class="sp-cmp__seg sp-cmp__seg--<?php echo esc_attr( $p[2] ); ?><?php echo $p[3] ? '' : ' is-miss'; ?>" style="width:<?php echo esc_attr( $pct( $p[1] ) ); ?>%"></span>
					<?php endforeach; ?>
					<?php if ( $diff > 0 ) : ?>
						<span class="sp-cmp__seg sp-cmp__seg--profit" style="width:<?php echo esc_attr( $pct( $diff ) ); ?>%"></span>
					<?php endif; ?>
				</div>
				<?php if ( $diff < 0 ) : ?>
					<span class="sp-cmp__loss" style="inset-inline-start:<?php echo esc_attr( $pct( $d['sale'] ) ); ?>%;width:<?php echo esc_attr( $pct( -$diff ) ); ?>%"></span>
				<?php endif; ?>
				<span class="sp-cmp__mark" style="inset-inline-start:<?php echo esc_attr( $pct( $d['sale'] ) ); ?>%"><em>فروش <?php echo esc_html( sp_fa( $d['sale'] ) ); ?></em></span>
			</div>
			<div class="sp-cmp__axis"><span>۰</span><span>هزینه‌ی واقعی <?php echo esc_html( sp_fa( $d['cost'] ) ); ?> هزار</span><span><?php echo esc_html( sp_fa( $scale ) ); ?> هزار</span></div>

			<ul class="sp-cmp__rows">
				<?php foreach ( $d['parts'] as $p ) : ?>
					<li class="<?php echo $p[3] ? '' : 'is-miss'; ?>"><i class="sp-cmp__dot sp-cmp__dot--<?php echo esc_attr( $p[2] ); ?>"></i><span><?php echo esc_html( $p[0] ); ?></span><b><?php echo $p[3] ? esc_html( sp_fa( $p[1] ) ) . ' هزار' : 'حساب نشد'; ?></b></li>
				<?php endforeach; ?>
				<?php if ( ! empty( $d['extra'] ) ) : ?>
					<li><i class="sp-cmp__dot sp-cmp__dot--guess"></i><span><?php echo esc_html( $d['extra'][0] ); ?></span><b><?php echo esc_html( $d['extra'][1] ); ?></b></li>
				<?php else : ?>
					<li><i class="sp-cmp__dot sp-cmp__dot--profit"></i><span>سود ۳۰٪</span><b><?php echo esc_html( sp_fa( $diff ) ); ?> هزار</b></li>
				<?php endif; ?>
				<li class="is-total"><span>قیمت فروش</span><b><?php echo esc_html( sp_fa( $d['sale'] ) ); ?> هزار</b></li>
			</ul>
			<p class="sp-compare__end"><?php echo sp_icon( $good ? 'up' : 'down', 18 ); ?> <?php echo esc_html( $d['end'] ); ?></p>
		</div>
		<?php
		return ob_get_clean();
	}
}
?>
<section class="sp-section sp-cmp">
	<div class="sp-wrap">
		<p class="sp-script sp-center">یه محصول، دو حساب</p>
		<h2 class="sp-display sp-display--md sp-center">حساب سرانگشتی کجا ضرر می‌ده؟</h2>

		<?php foreach ( $sp_cmp as $sp_k => $sp_c ) : ?>
			<div data-only="<?php echo esc_attr( $sp_k ); ?>">
				<p class="sp-sub sp-center"><?php echo esc_html( $sp_c['note'] ); ?></p>
				<div class="sp-compare">
					<?php echo sp_cmp_card( $sp_c['bad'], $sp_c['scale'], false ); // phpcs:ignore ?>
					<span class="sp-cmp__vs" aria-hidden="true">در برابر</span>
					<?php echo sp_cmp_card( $sp_c['good'], $sp_c['scale'], true ); // phpcs:ignore ?>
				</div>
			</div>
		<?php endforeach; ?>

		<ul class="sp-cmp__legend" aria-hidden="true">
			<li><i class="sp-cmp__dot sp-cmp__dot--mat"></i>مواد</li>
			<li><i class="sp-cmp__dot sp-cmp__dot--lab"></i>دستمزد</li>
			<li><i class="sp-cmp__dot sp-cmp__dot--gas"></i>گاز و بسته‌بندی</li>
			<li><i class="sp-cmp__dot sp-cmp__dot--profit"></i>سود</li>
			<li><i class="sp-cmp__dot sp-cmp__dot--loss"></i>ضرر</li>
		</ul>

		<div class="sp-actions" style="justify-content:center;margin-top:28px">
			<a class="sp-btn sp-btn--coral" data-only="sweet" href="<?php echo esc_url( add_query_arg( 'k', 'sweet', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'calc', 20 ); ?>کیک خودت رو حساب کن</a>
			<a class="sp-btn sp-btn--coral" data-only="food" href="<?php echo esc_url( add_query_arg( 'k', 'food', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'calc', 20 ); ?>غذای خودت رو حساب کن</a>
		</div>
	</div>
</section>

<!-- ۴. چطور کار می‌کنه (سه کارت با سودی) -->
<section class="sp-section sp-how">
	<div class="sp-wrap">
		<p class="sp-script sp-center">سه قدم تا قیمت درست</p>
		<h2 class="sp-display sp-display--md sp-center">چطور کار می‌کنه</h2>
		<ol class="sp-how__steps">
			<li class="sp-how__step" style="--c:#E8673C">
				<span class="sp-how__n">۱</span>
				<div class="sp-how__art"><?php echo sp_soodi( 'thinking', 'sp-how__soodi', 'سودی در حال فکر کردن' ); // phpcs:ignore ?></div>
				<h3><?php echo sp_icon( 'book', 20 ); ?>دستورت رو انتخاب کن</h3>
				<p>از کتابخونه‌ی کیک‌ها و غذاهای آماده بردار، یا دستور خودت رو با مواد و مقدارش بنویس.</p>
			</li>
			<li class="sp-how__step" style="--c:#149C98">
				<span class="sp-how__n">۲</span>
				<div class="sp-how__art"><?php echo sp_soodi( 'shocked', 'sp-how__soodi', 'سودی تعجب کرده' ); // phpcs:ignore ?></div>
				<h3><?php echo sp_icon( 'calc', 20 ); ?>قیمت واقعی رو ببین</h3>
				<p>با قیمت به‌روز مواد، دستمزد خودت، گاز فر یا اجاق و بسته‌بندی؛ برای کیک کیلویی، برای غذا هر پرس.</p>
			</li>
			<li class="sp-how__step" style="--c:#E9A23B">
				<span class="sp-how__n">۳</span>
				<div class="sp-how__art"><?php echo sp_soodi( 'celebrating', 'sp-how__soodi', 'سودی جشن گرفته' ); // phpcs:ignore ?></div>
				<h3><?php echo sp_icon( 'send', 20 ); ?>لیست قیمت یا منوت رو بفرست</h3>
				<p>لیست قیمت یا منوی هفتگیت رو برای استوری بگیر. گوشت یا خامه گرون شد؟ همه‌چی خودش به‌روز می‌شه.</p>
			</li>
		</ol>
	</div>
</section>

<!-- ۵. کتابخونه روی میز چوبی -->
<section class="sp-table-scene"<?php echo $sp_wood ? ' style="--wood:url(' . esc_url( $sp_wood ) . ')"' : ''; ?> id="recipes">
	<div class="sp-wrap">
		<?php sp_kitchen_switch( 'کدوم آشپزخونه؟', true ); ?>
		<div class="sp-scene">
			<div class="sp-scene__info">
				<p class="sp-script sp-script--light">کتابخونه‌ی دستورها</p>
				<h2 class="sp-display sp-display--md">امروز چی می‌پزی؟</h2>
				<?php foreach ( $sp_recipes as $key => $r ) : $sp_food = 'food' === $r['kitchen']; ?>
					<div class="sp-scene__card" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_firsts[ $r['kitchen'] ] ? '' : ' hidden'; ?>>
						<h3><?php echo esc_html( $r['name'] ); ?></h3>
						<ul class="sp-tags">
							<?php foreach ( array_keys( $r['items'] ) as $ik ) : ?>
								<li><?php echo esc_html( $sp_ings[ $ik ]['name'] ); ?></li>
							<?php endforeach; ?>
						</ul>
						<p class="sp-scene__meta"><?php echo sp_icon( 'clock', 18 ); ?> حدود <?php echo esc_html( sp_fa( $r['hours'] ) ); ?> ساعت کار برای <?php echo $sp_food ? '۱۰ پرس' : '۱ کیلو'; ?></p>
						<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( add_query_arg( 'dish', $key, sp_url( 'calculator' ) ) ); ?>">قیمتش رو حساب کن <?php echo sp_icon( 'arrow', 18 ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="sp-scene__plate">
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<div class="sp-plate sp-plate--scene" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>"<?php echo $key === $sp_firsts[ $r['kitchen'] ] ? '' : ' hidden'; ?>>
						<?php echo sp_img( $r['img'], $r['name'], 'sp-plate__img' ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="sp-flags" role="tablist" aria-label="انتخاب دستور">
				<?php foreach ( $sp_recipes as $key => $r ) : ?>
					<button class="sp-flag" type="button" role="tab" data-only="<?php echo esc_attr( $r['kitchen'] ); ?>" style="--c:<?php echo esc_attr( $r['color'] ); ?>" data-cake="<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $key === $sp_firsts[ $r['kitchen'] ] ? 'true' : 'false'; ?>">
						<?php echo sp_icon( $r['icon'], 26 ); ?><span><?php echo esc_html( $r['name'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ۶. امکانات + پیش‌نمایش اپ -->
<section class="sp-section" id="features">
	<div class="sp-wrap sp-features">
		<div>
			<p class="sp-script">سودپز پرو</p>
			<h2 class="sp-display sp-display--md">هر چی برای ضرر نکردن لازم داری</h2>
			<p class="sp-sub">برای کیک و شیرینی، و برای غذای خانگی. نسخه‌ی کامل اول برای عضوهای بنیان‌گذار باز می‌شه.</p>
			<div class="sp-feature-grid">
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E8673C"><?php echo sp_icon( 'refresh', 24 ); ?></span><h3>بازقیمت‌گذاری یه‌کلیکی</h3><p>قیمت یه ماده رو عوض کن، قیمت همه‌ی کیک‌ها و غذاهات خودش درست می‌شه.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#149C98"><?php echo sp_icon( 'tag', 24 ); ?></span><h3>قیمت مرجع هفتگی</h3><p>قیمت حدود ۴۰ ماده‌ی پرمصرف، از خامه تا گوشت و برنج، هر هفته به‌روز می‌شه.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E9A23B"><?php echo sp_icon( 'book', 24 ); ?></span><h3>دستورهای آماده</h3><p>کیک‌های پرفروش و غذاهای خونگی رایج با مقدار مواد؛ فقط وزن یا تعداد پرس رو عوض کن.</p></div>
				<div class="sp-feature"><span class="sp-feature__ico" style="--c:#E03A3E"><?php echo sp_icon( 'bell', 24 ); ?></span><h3>هشدار ضرر</h3><p>هر کیک یا غذایی که زیر قیمت تمام‌شده می‌فروشی، قرمز می‌شه و می‌گه چقدر.</p></div>
			</div>
		</div>

		<div class="sp-phone" aria-label="نمونه‌ی صفحه‌ی اپ سودپز">
			<div class="sp-phone__screen">
				<div class="sp-phone__bar"><b>محصولات من</b><span class="sp-chip">نمونه</span></div>
				<div class="sp-app-alert"><?php echo sp_icon( 'bell', 18 ); ?><span>گوشت ۸٪ گرون شد. ۳ غذات تغییر کردن.</span></div>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#3F8A4E"><?php echo sp_icon( 'pot', 18 ); ?></span><div><b>قورمه‌سبزی</b><small>هر پرس، تمام‌شده ۲۷۰ هزار</small></div><em class="is-good">۳۸۶ هزار</em></div>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#D9822B"><?php echo sp_icon( 'bowl', 18 ); ?></span><div><b>قیمه</b><small>هر پرس، تمام‌شده ۲۴۵ هزار</small></div><em class="is-bad">۲۳۰ هزار</em></div>
				<p class="sp-app-loss"><?php echo sp_icon( 'down', 16 ); ?> قیمه رو هر پرس ۱۵ هزار تومان زیر قیمت تمام‌شده می‌فروشی.</p>
				<div class="sp-app-row"><span class="sp-app-row__dot" style="--c:#E8673C"><?php echo sp_icon( 'cake', 18 ); ?></span><div><b>کیک شکلاتی</b><small>کیلویی، تمام‌شده ۶۴۲ هزار</small></div><em class="is-good">۹۲۰ هزار</em></div>
				<div class="sp-phone__nav"><span class="is-on"><?php echo sp_icon( 'pot', 20 ); ?>محصولات</span><span><?php echo sp_icon( 'tag', 20 ); ?>مواد</span><span><?php echo sp_icon( 'story', 20 ); ?>لیست قیمت</span></div>
			</div>
		</div>
	</div>
</section>

<!-- ۶٫۵. تازه‌ترین مطالب مجله -->
<?php $sp_posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'fields' => 'ids' ) ); ?>
<?php if ( $sp_posts ) : ?>
<section class="sp-section sp-hblog">
	<div class="sp-wrap">
		<div class="sp-hblog__head">
			<div>
				<p class="sp-script">مجله‌ی سودپز</p>
				<h2 class="sp-display sp-display--md">تازه‌ترین مطالب</h2>
			</div>
			<a class="sp-hblog__all" href="<?php echo esc_url( sp_blog_url() ); ?>">همه‌ی مطالب <?php echo sp_icon( 'arrow', 18 ); ?></a>
		</div>
		<div class="sp-hblog__grid">
			<?php foreach ( $sp_posts as $sp_pid ) { echo sp_blog_card( $sp_pid, 'md' ); } // phpcs:ignore ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ۷. اعتماد: سه قول، به‌صورت اینفوگرافیک -->
<section class="sp-section sp-trust">
	<div class="sp-wrap">
		<p class="sp-script sp-center">خیالت راحت</p>
		<h2 class="sp-display sp-display--md sp-center">سه تا قول سودپز</h2>
		<div class="sp-trust__grid">

			<article class="sp-trust__item" style="--c:#149C98">
				<div class="sp-tv sp-tv--net" aria-hidden="true">
					<div class="sp-tv__server"><?php echo sp_icon( 'server', 30 ); ?><span class="sp-tv__flag">ایران</span></div>
					<div class="sp-tv__rows">
						<div class="sp-tv__row is-off"><span class="sp-tv__dot"></span><span>اینترنت بین‌الملل</span><b>قطع</b></div>
						<div class="sp-tv__row is-on"><span class="sp-tv__dot"></span><span>سودپز</span><b>در دسترس</b></div>
					</div>
				</div>
				<div class="sp-trust__txt">
					<h3><span class="sp-trust__ico"><?php echo sp_icon( 'server', 20 ); ?></span>روی سرور ایرانی</h3>
					<p>موقع قطعی اینترنت هم بازه؛ قیمت‌هات همیشه دم دستته.</p>
				</div>
			</article>

			<article class="sp-trust__item" style="--c:#910825">
				<div class="sp-tv sp-tv--lock" aria-hidden="true">
					<div class="sp-tv__fan">
						<span class="sp-tv__card" style="--r:-12deg;--x:-70px"><?php echo sp_icon( 'cake', 18 ); ?>کیک شکلاتی</span>
						<span class="sp-tv__card" style="--r:0deg;--x:0px;z-index:1"><?php echo sp_icon( 'pot', 18 ); ?>قورمه‌سبزی</span>
						<span class="sp-tv__card" style="--r:12deg;--x:70px"><?php echo sp_icon( 'cheese', 18 ); ?>چیزکیک</span>
					</div>
					<span class="sp-tv__lock"><?php echo sp_icon( 'lock', 22 ); ?></span>
					<span class="sp-tv__toggle"><i></i>فقط خودت می‌بینی</span>
				</div>
				<div class="sp-trust__txt">
					<h3><span class="sp-trust__ico"><?php echo sp_icon( 'lock', 20 ); ?></span>دستورهات مال خودته</h3>
					<p>دستور و قیمتت بدون اجازه‌ی تو هیچ‌جا نمی‌ره و به کسی نشون داده نمی‌شه.</p>
				</div>
			</article>

			<article class="sp-trust__item" style="--c:#E9A23B">
				<div class="sp-tv sp-tv--days" aria-hidden="true">
					<ol class="sp-tv__days">
						<?php for ( $sp_d = 1; $sp_d <= 7; $sp_d++ ) : ?>
							<li style="--i:<?php echo (int) $sp_d; ?>"><?php echo esc_html( sp_fa( $sp_d ) ); ?></li>
						<?php endfor; ?>
					</ol>
					<div class="sp-tv__refund"><?php echo sp_icon( 'refund', 20 ); ?><b>۱۰۰٪</b><span>پولت برمی‌گرده</span></div>
				</div>
				<div class="sp-trust__txt">
					<h3><span class="sp-trust__ico"><?php echo sp_icon( 'refund', 20 ); ?></span>۷ روز بازگشت پول</h3>
					<p>تا روز هفتم پشیمون شدی؟ بدون سؤال، کل پولت برمی‌گرده.</p>
				</div>
			</article>

		</div>
	</div>
</section>

<!-- ۸. پایان: کارت بنیان‌گذار -->
<?php $sp_set = sp_settings(); ?>
<section class="sp-final">
	<div class="sp-wrap">
		<div class="sp-final__card">
			<span class="sp-final__ring sp-final__ring--a" aria-hidden="true"></span>
			<span class="sp-final__ring sp-final__ring--b" aria-hidden="true"></span>
			<div class="sp-final__text">
				<span class="sp-final__badge"><?php echo sp_icon( 'spark', 16 ); ?><?php echo '' !== $sp_set['founders_left'] ? esc_html( sp_fa( $sp_set['founders_left'] ) ) . ' جا از ۵۰ تا مونده' : 'فقط برای ۵۰ نفر'; ?></span>
				<h2 class="sp-final__h">یه کیک یا یه دیگ غذای کم‌فروخته، گرون‌تر از اشتراک سودپزه.</h2>
				<div class="sp-final__prices">
					<div class="sp-final__price"><small>پرو ماهانه</small><b>۹۹ هزار</b></div>
					<div class="sp-final__price"><small>پرو سالانه</small><b>۶۹۰ هزار</b></div>
					<div class="sp-final__price sp-final__price--hot"><small>بنیان‌گذار، سالانه برای همیشه</small><b><s>۶۹۰</s> ۳۹۰ هزار</b></div>
				</div>
				<div class="sp-final__cta">
					<a class="sp-btn sp-final__btn" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>"><?php echo sp_icon( 'spark', 18 ); ?>جامو نگه دار</a>
					<a class="sp-final__link" href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>">مقایسه‌ی پلن‌ها <?php echo sp_icon( 'arrow', 16 ); ?></a>
				</div>
			</div>
			<div class="sp-final__art">
				<?php echo sp_soodi( 'celebrating', 'sp-final__soodi', 'سودی جشن گرفته' ); // phpcs:ignore ?>
			</div>
		</div>
	</div>
</section>

<script>
(function () {
	// انتخاب دستور روی میز چوبی؛ هر آشپزخونه انتخاب خودش رو نگه می‌داره.
	var scene = document.querySelector('.sp-scene');
	if (!scene) return;
	scene.addEventListener('click', function (e) {
		var f = e.target.closest('.sp-flag');
		if (!f) return;
		var k = f.getAttribute('data-only'), key = f.getAttribute('data-cake');
		Array.prototype.forEach.call(scene.querySelectorAll('[data-only="' + k + '"][data-cake]'), function (el) {
			if (el.classList.contains('sp-flag')) {
				el.setAttribute('aria-selected', el === f ? 'true' : 'false');
			} else {
				el.hidden = el.getAttribute('data-cake') !== key;
			}
		});
	});
})();
</script>

<?php get_footer(); ?>
