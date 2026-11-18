<?php
/* میز کار من (نامک: dashboard) — فقط برای کاربر واردشده. چهار تب: خلاصه، دستورها، خوندنی‌ها، اشتراک. */
get_header();

$sp_u     = wp_get_current_user();
$sp_uid   = (int) $sp_u->ID;
$sp_recs  = sp_dash_recipes( $sp_uid );
$sp_st    = sp_dash_stats( $sp_recs );
$sp_plan  = sp_dash_plan( $sp_uid );
$sp_free  = 'free' === $sp_plan['plan'];
$sp_freei = (string) get_user_meta( $sp_uid, 'sp_free_recipe', true );
$sp_saved = sp_saved_posts( $sp_uid );
$sp_posts = $sp_saved ? get_posts( array( 'post__in' => $sp_saved, 'orderby' => 'post__in', 'numberposts' => 24, 'post_status' => 'publish' ) ) : array();
$sp_money = function ( $v ) { return sp_fa( round( $v ) ); };

/** کارت یک دستور. */
$sp_card = function ( $id, $r ) use ( $sp_free, $sp_freei, $sp_money ) {
	$s    = $r['sum'] ?? array();
	$ok   = ! empty( $s['p'] ) && empty( $s['miss'] );
	$food = ( $r['kitchen'] ?? 'sweet' ) === 'food';
	$lock = $sp_free && (string) $id !== $sp_freei;
	?>
	<article class="sp-dcard<?php echo $lock ? ' is-lock' : ''; ?>">
		<div class="sp-dcard__top">
			<span class="sp-dcard__k sp-dcard__k--<?php echo $food ? 'food' : 'sweet'; ?>"><?php echo sp_icon( $food ? 'pot' : 'cake', 18 ); // phpcs:ignore ?></span>
			<b><?php echo esc_html( $r['name'] ?? 'بدون اسم' ); ?></b>
			<?php if ( $lock ) : ?><span class="sp-dcard__lock" title="توی پلن رایگان فقط‌خواندنیه"><?php echo sp_icon( 'lock', 15 ); // phpcs:ignore ?></span><?php endif; ?>
		</div>
		<p class="sp-dcard__y"><?php echo esc_html( sp_fa( $r['yq'] ?? 1 ) . ' ' . ( $r['yu'] ?? '' ) ); ?></p>
		<?php if ( $ok ) : ?>
			<div class="sp-dcard__nums">
				<span><small>قیمت هر <?php echo esc_html( $s['u'] ?? '' ); ?></small><b><?php echo esc_html( $sp_money( $s['p'] ) ); ?></b></span>
				<span class="is-profit"><small>سود هر <?php echo esc_html( $s['u'] ?? '' ); ?></small><b><?php echo esc_html( $sp_money( $s['pr'] ) ); ?></b></span>
			</div>
		<?php else : ?>
			<p class="sp-dcard__inc"><?php echo sp_icon( 'info', 15 ); // phpcs:ignore ?>هنوز کامل نیست</p>
		<?php endif; ?>
		<a class="sp-dcard__go" href="<?php echo esc_url( add_query_arg( 'r', $id, sp_url( 'calculator' ) ) ); ?>"><?php echo $lock ? 'ببینش' : 'باز کن و ویرایش کن'; ?><?php echo sp_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
	</article>
	<?php
};

$sp_tabs = array(
	'home'  => array( 'خلاصه', 'spark' ),
	'recs'  => array( 'دستورهای من', 'cake', count( $sp_recs ) ),
	'reads' => array( 'خوندنی‌ها', 'book', count( $sp_posts ) ),
	'plan'  => array( 'اشتراک', 'clock' ),
);
?>
<section class="sp-dash" id="sp-dash">
	<div class="sp-wrap sp-dash__shell">

		<!-- ستون تب‌ها -->
		<nav class="sp-rail" aria-label="بخش‌های میز کار">
			<div class="sp-rail__me">
				<span class="sp-rail__av"><?php echo esc_html( mb_substr( $sp_u->display_name, 0, 1 ) ); ?></span>
				<span class="sp-rail__name"><b><?php echo esc_html( $sp_u->display_name ); ?></b><small><?php echo esc_html( $sp_plan['label'] ); ?></small></span>
			</div>
			<ul class="sp-rail__list">
				<?php foreach ( $sp_tabs as $k => $t ) : ?>
					<li><button type="button" class="sp-rail__b<?php echo 'home' === $k ? ' is-on' : ''; ?>" data-tab="<?php echo esc_attr( $k ); ?>" aria-selected="<?php echo 'home' === $k ? 'true' : 'false'; ?>">
						<?php echo sp_icon( $t[1], 18 ); // phpcs:ignore ?><span><?php echo esc_html( $t[0] ); ?></span>
						<?php if ( isset( $t[2] ) && $t[2] ) : ?><i><?php echo esc_html( sp_fa( $t[2] ) ); ?></i><?php endif; ?>
					</button></li>
				<?php endforeach; ?>
			</ul>
			<div class="sp-rail__foot">
				<a class="sp-rail__new" href="<?php echo esc_url( add_query_arg( 'new', '1', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'plus', 17 ); // phpcs:ignore ?>دستور تازه</a>
				<a class="sp-rail__out" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">خروج از حساب</a>
			</div>
		</nav>

		<div class="sp-dmain">

			<!-- ===== تب خلاصه ===== -->
			<div class="sp-dpanel is-on" data-panel="home">
				<div class="sp-d3d">
					<div class="sp-d3d__bg" aria-hidden="true"><i class="sp-d3d__orb"></i><i class="sp-d3d__ring"></i><i class="sp-d3d__cube"></i></div>
					<div class="sp-d3d__txt">
						<p class="sp-d3d__hi">سلام <?php echo esc_html( $sp_u->display_name ); ?> 👋</p>
						<?php if ( $sp_st['ready'] ) : ?>
							<p class="sp-d3d__k">اگه از هر دستورت یه بار بفروشی</p>
							<p class="sp-d3d__big"><b><?php echo esc_html( $sp_money( $sp_st['profit_batch'] ) ); ?></b><span>تومان سود</span></p>
						<?php else : ?>
							<p class="sp-d3d__k">میز کارت آماده‌ست</p>
							<p class="sp-d3d__big sp-d3d__big--sm"><b>بزن بریم!</b></p>
							<p class="sp-d3d__sub">اولین کیک یا غذات رو حساب کن و ذخیره‌ش کن تا سودت این‌جا بیاد.</p>
						<?php endif; ?>
						<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>"><?php echo sp_icon( 'calc', 17 ); // phpcs:ignore ?>ماشین‌حساب</a>
					</div>
					<?php echo sp_soodi( 'celebrating', 'sp-d3d__soodi', 'سودی', true ); // phpcs:ignore ?>
				</div>

				<div class="sp-dstats">
					<?php
					$sp_tiles = array(
						array( 'cake', 'cake', sp_fa( $sp_st['count'] ), 'دستور ذخیره‌شده' ),
						array( 'profit', 'spark', $sp_money( $sp_st['profit_batch'] ), 'تومان سود، اگه از هر کدوم یه بار بفروشی' ),
						array( 'margin', 'tag', sp_fa( round( $sp_st['margin'] ) ) . '٪', 'میانگین سود هدفت' ),
						array( 'save', 'refund', $sp_money( $sp_st['saved'] ), $sp_st['saved'] > 0 ? 'تومان که با قیمت فعلیت از دست می‌دی' : 'تومان ضرر پنهان (قیمت فعلیت رو بنویس)' ),
					);
					foreach ( $sp_tiles as $t ) :
						?>
						<div class="sp-dstat sp-dstat--<?php echo esc_attr( $t[0] ); ?>">
							<span class="sp-dstat__i"><?php echo sp_icon( $t[1], 20 ); // phpcs:ignore ?></span>
							<b><?php echo esc_html( $t[2] ); ?></b>
							<small><?php echo esc_html( $t[3] ); ?></small>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="sp-dduo">
					<section class="sp-dbox">
						<h2><?php echo sp_icon( 'calc', 20 ); // phpcs:ignore ?>سود هر دستور</h2>
						<?php if ( $sp_st['bars'] ) : ?>
							<p class="sp-dbox__note">سود کل هر دستور، اگه یک بار کامل بپزی و بفروشیش.</p>
							<?php
							$sp_max = max( 1, max( array_map( function ( $b ) { return $b['profit']; }, $sp_st['bars'] ) ) );
							$sp_n   = count( $sp_st['bars'] );
							$sp_w   = 100 / max( 1, $sp_n );
							?>
							<div class="sp-d3chart" style="--cols:<?php echo (int) $sp_n; ?>">
								<?php foreach ( $sp_st['bars'] as $b ) : ?>
									<div class="sp-d3col<?php echo $b['food'] ? ' is-food' : ''; ?>" style="--h:<?php echo esc_attr( max( 8, round( $b['profit'] / $sp_max * 100 ) ) ); ?>%">
										<span class="sp-d3col__v"><?php echo esc_html( $sp_money( $b['profit'] ) ); ?></span>
										<span class="sp-d3col__bar"><i></i></span>
										<span class="sp-d3col__n"><?php echo esc_html( $b['name'] ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<p class="sp-dempty">وقتی دستورهات مواد و قیمت کامل داشته باشن، نمودار سودت این‌جا می‌آد.</p>
						<?php endif; ?>
					</section>

					<section class="sp-dbox">
						<h2><?php echo sp_icon( 'tag', 20 ); // phpcs:ignore ?>پول کجا می‌ره؟</h2>
						<?php if ( $sp_st['ready'] ) : ?>
							<?php
							$sp_tot = max( 1, $sp_st['mat'] + $sp_st['wg'] + $sp_st['ot'] + $sp_st['pr'] );
							$sp_pie = array(
								array( 'مواد', $sp_st['mat'] / $sp_tot * 100, '#F2A33A' ),
								array( 'دستمزد تو', $sp_st['wg'] / $sp_tot * 100, '#7FC8C4' ),
								array( 'گاز و بسته‌بندی', $sp_st['ot'] / $sp_tot * 100, '#B9A3D6' ),
								array( 'سود تو', $sp_st['pr'] / $sp_tot * 100, '#5FD08F' ),
							);
							$sp_acc   = 0;
							$sp_stops = array();
							foreach ( $sp_pie as $p ) {
								$sp_stops[] = $p[2] . ' ' . round( $sp_acc, 2 ) . '% ' . round( $sp_acc + $p[1], 2 ) . '%';
								$sp_acc    += $p[1];
							}
							?>
							<div class="sp-dpie">
								<div class="sp-dpie__wrap">
									<div class="sp-dpie__ring" style="background:conic-gradient(<?php echo esc_attr( implode( ',', $sp_stops ) ); ?>)">
										<span><b><?php echo esc_html( sp_fa( round( $sp_st['pr'] / $sp_tot * 100 ) ) ); ?>٪</b><small>سهم تو</small></span>
									</div>
								</div>
								<ul class="sp-dpie__key">
									<?php foreach ( $sp_pie as $p ) : ?>
										<li><i style="background:<?php echo esc_attr( $p[2] ); ?>"></i><?php echo esc_html( $p[0] ); ?><b><?php echo esc_html( sp_fa( round( $p[1] ) ) ); ?>٪</b></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php else : ?>
							<p class="sp-dempty">با اولین دستور کامل، این نمودار نشون می‌ده از هر تومن فروشت چقدر مال خودته.</p>
						<?php endif; ?>
					</section>
				</div>
			</div>

			<!-- ===== تب دستورها ===== -->
			<div class="sp-dpanel" data-panel="recs" hidden>
				<div class="sp-dhead">
					<h2><?php echo sp_icon( 'cake', 22 ); // phpcs:ignore ?>دستورهای من</h2>
					<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( add_query_arg( 'new', '1', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'plus', 16 ); // phpcs:ignore ?>دستور تازه</a>
				</div>
				<?php if ( $sp_recs ) : ?>
					<div class="sp-dcards">
						<?php foreach ( $sp_recs as $sp_id => $sp_r ) { $sp_card( $sp_id, $sp_r ); } ?>
					</div>
				<?php else : ?>
					<div class="sp-dbox sp-dbox--empty">
						<?php echo sp_soodi( 'thinking', 'sp-dempty__soodi', 'سودی فکر می‌کند', false ); // phpcs:ignore ?>
						<p class="sp-dempty">هنوز دستوری ذخیره نکردی.</p>
						<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">اولین دستورت رو بساز</a>
					</div>
				<?php endif; ?>
			</div>

			<!-- ===== تب خوندنی‌ها ===== -->
			<div class="sp-dpanel" data-panel="reads" hidden>
				<div class="sp-dhead">
					<h2><?php echo sp_icon( 'book', 22 ); // phpcs:ignore ?>برای بعد ذخیره کردم</h2>
					<a class="sp-btn sp-btn--paper sp-btn--sm" href="<?php echo esc_url( sp_blog_url() ); ?>">مجله</a>
				</div>
				<?php if ( $sp_posts ) : ?>
					<div class="sp-dreads">
						<?php foreach ( $sp_posts as $sp_p ) : ?>
							<article class="sp-dread">
								<a href="<?php echo esc_url( get_permalink( $sp_p ) ); ?>">
									<?php echo sp_post_img( $sp_p->ID, 'sp-dread__img', false, '120px' ); // phpcs:ignore ?>
									<span><b><?php echo esc_html( get_the_title( $sp_p ) ); ?></b><small><?php echo sp_icon( 'clock', 14 ); // phpcs:ignore ?><?php echo esc_html( sp_fa( sp_read_minutes( $sp_p->ID ) ) ); ?> دقیقه</small></span>
								</a>
								<button type="button" class="sp-bm is-on sp-dread__rm" data-bm="<?php echo (int) $sp_p->ID; ?>" data-row="1" aria-pressed="true" title="برداشتن از این فهرست"><?php echo sp_icon( 'x', 15 ); // phpcs:ignore ?></button>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="sp-dbox sp-dbox--empty">
						<?php echo sp_soodi( 'happy', 'sp-dempty__soodi', 'سودی', false ); // phpcs:ignore ?>
						<p class="sp-dempty">هر مطلبی از مجله رو که خواستی بعداً بخونی، با دکمه‌ی «ذخیره برای بعد» این‌جا نگه دار.</p>
						<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_blog_url() ); ?>">برو به مجله</a>
					</div>
				<?php endif; ?>
			</div>

			<!-- ===== تب اشتراک ===== -->
			<div class="sp-dpanel" data-panel="plan" hidden>
				<div class="sp-dhead"><h2><?php echo sp_icon( 'clock', 22 ); // phpcs:ignore ?>اشتراک من</h2></div>
				<div class="sp-dplan">
					<div class="sp-dplan__card sp-dplan__card--<?php echo esc_attr( $sp_plan['plan'] ); ?>">
						<?php
						$sp_endless = 'pro' === $sp_plan['plan'] && ! $sp_plan['days'];
						$sp_pct     = $sp_endless ? 100 : ( ( 'free' !== $sp_plan['plan'] && $sp_plan['total'] ) ? max( 4, min( 100, round( $sp_plan['days'] / $sp_plan['total'] * 100 ) ) ) : 0 );
						?>
						<div class="sp-dring" style="--p:<?php echo esc_attr( $sp_pct ); ?>">
							<span>
								<b><?php echo $sp_endless ? '∞' : ( 'free' === $sp_plan['plan'] ? '—' : esc_html( sp_fa( $sp_plan['days'] ) ) ); ?></b>
								<small><?php echo $sp_endless ? 'همیشه فعال' : ( 'free' === $sp_plan['plan'] ? 'رایگان' : 'روز مونده' ); ?></small>
							</span>
						</div>
						<div class="sp-dplan__txt">
							<p class="sp-dplan__name"><?php echo esc_html( $sp_plan['label'] ); ?></p>
							<p class="sp-dplan__note">
								<?php
								if ( 'free' === $sp_plan['plan'] ) {
									echo 'یه دستور قابل ویرایش داری و قیمت دستورهات خودکار به‌روز نمی‌شه.';
								} elseif ( 'trial' === $sp_plan['plan'] ) {
									echo 'پرو رایگانته. بعدش یه دستور برات رایگان می‌مونه.';
								} elseif ( ! empty( $sp_plan['until'] ) ) {
									echo 'پرو تا ' . esc_html( $sp_plan['until'] ) . ' فعاله.';
								} else {
									echo 'پروی تو همیشه فعاله.';
								}
								?>
							</p>
							<?php if ( 'pro' !== $sp_plan['plan'] ) : ?>
								<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'free' === $sp_plan['plan'] ? 'pricing' : 'founders' ) ); ?>"><?php echo 'free' === $sp_plan['plan'] ? 'پرو رو ببین' : 'عضو بنیان‌گذار شو'; ?></a>
							<?php endif; ?>
						</div>
					</div>
					<ul class="sp-dfeat">
						<?php
						$sp_rows = array(
							array( 'ماشین‌حساب با قیمت روز مواد', true ),
							array( 'ذخیره‌ی دستور', true ),
							array( 'هر چند تا دستور که بخوای', ! $sp_free ),
							array( 'به‌روز شدن خودکار قیمت دستورها', ! $sp_free ),
							array( 'هشدار وقتی زیر قیمت تمام‌شده می‌فروشی', ! $sp_free ),
						);
						foreach ( $sp_rows as $r ) :
							?>
							<li class="<?php echo $r[1] ? '' : 'is-off'; ?>"><?php echo sp_icon( $r[1] ? 'check' : 'lock', 16 ); // phpcs:ignore ?><?php echo esc_html( $r[0] ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

		</div>
	</div>
</section>
<?php
get_footer();
