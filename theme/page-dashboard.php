<?php
/* میز کار من (نامک: dashboard) — فقط برای کاربر واردشده */
get_header();

$sp_u     = wp_get_current_user();
$sp_uid   = (int) $sp_u->ID;
$sp_recs  = sp_dash_recipes( $sp_uid );
$sp_st    = sp_dash_stats( $sp_recs );
$sp_plan  = sp_dash_plan( $sp_uid );
$sp_free  = 'free' === $sp_plan['plan'];
$sp_saved = sp_saved_posts( $sp_uid );
$sp_money = function ( $v ) { return sp_fa( round( $v ) ); };
?>
<section class="sp-dash">
	<div class="sp-wrap">

		<header class="sp-dash__head">
			<div>
				<p class="sp-script">میز کار من</p>
				<h1 class="sp-h1">سلام <?php echo esc_html( $sp_u->display_name ); ?>!</h1>
				<p class="sp-sub"><?php
				if ( ! $sp_st['count'] ) {
					echo 'هنوز دستوری ذخیره نکردی. اولین کیک یا غذات رو حساب کن و نگهش دار.';
				} elseif ( $sp_st['ready'] ) {
					echo 'اگه از هر دستورت یه بار بفروشی، ' . esc_html( $sp_money( $sp_st['profit_batch'] ) ) . ' تومان سود می‌کنی.';
				} else {
					echo 'دستورهات این‌جان. مواد و قیمت‌هاشون رو کامل کن تا سودت رو حساب کنم.';
				}
				?></p>
			</div>
			<span class="sp-dash__plan sp-dash__plan--<?php echo esc_attr( $sp_plan['plan'] ); ?>"><?php echo sp_icon( 'spark', 17 ); // phpcs:ignore ?><?php echo esc_html( $sp_plan['label'] ); ?></span>
		</header>

		<!-- کارت‌های عددی -->
		<div class="sp-dstats">
			<div class="sp-dstat">
				<span class="sp-dstat__i sp-dstat__i--cake"><?php echo sp_icon( 'cake', 20 ); // phpcs:ignore ?></span>
				<b><?php echo esc_html( sp_fa( $sp_st['count'] ) ); ?></b>
				<small>دستور ذخیره‌شده</small>
			</div>
			<div class="sp-dstat">
				<span class="sp-dstat__i sp-dstat__i--profit"><?php echo sp_icon( 'spark', 20 ); // phpcs:ignore ?></span>
				<b><?php echo esc_html( $sp_money( $sp_st['profit_batch'] ) ); ?></b>
				<small>تومان سود، اگه از هر کدوم یه بار بفروشی</small>
			</div>
			<div class="sp-dstat">
				<span class="sp-dstat__i sp-dstat__i--margin"><?php echo sp_icon( 'tag', 20 ); // phpcs:ignore ?></span>
				<b><?php echo esc_html( sp_fa( round( $sp_st['margin'] ) ) ); ?>٪</b>
				<small>میانگین سود هدفت</small>
			</div>
			<div class="sp-dstat<?php echo $sp_st['saved'] > 0 ? ' is-warn' : ''; ?>">
				<span class="sp-dstat__i sp-dstat__i--save"><?php echo sp_icon( 'refund', 20 ); // phpcs:ignore ?></span>
				<b><?php echo esc_html( $sp_money( $sp_st['saved'] ) ); ?></b>
				<small><?php echo $sp_st['saved'] > 0 ? 'تومان که با قیمت فعلیت داری از دست می‌دی' : 'تومان ضرر پنهان (قیمت فعلیت رو بنویس تا حساب کنم)'; ?></small>
			</div>
		</div>

		<div class="sp-dash__grid">
			<!-- نمودار سود -->
			<section class="sp-dbox sp-dbox--chart">
				<h2><?php echo sp_icon( 'calc', 20 ); // phpcs:ignore ?>سود هر دستور</h2>
				<?php if ( $sp_st['bars'] ) : ?>
					<p class="sp-dbox__note">سود کل هر دستور، اگه یک بار کامل بپزی و بفروشیش.</p>
					<ul class="sp-dbars">
						<?php
						$sp_max = max( array_map( function ( $b ) { return $b['profit']; }, $sp_st['bars'] ) );
						$sp_max = $sp_max > 0 ? $sp_max : 1;
						foreach ( $sp_st['bars'] as $b ) :
							?>
							<li>
								<span class="sp-dbars__n"><?php echo esc_html( $b['name'] ); ?></span>
								<span class="sp-dbars__t"><i class="<?php echo $b['food'] ? 'is-food' : ''; ?>" style="--w:<?php echo esc_attr( max( 4, round( $b['profit'] / $sp_max * 100 ) ) ); ?>%"></i></span>
								<span class="sp-dbars__v"><?php echo esc_html( $sp_money( $b['profit'] ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
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
						<div class="sp-dpie__ring" style="background:conic-gradient(<?php echo esc_attr( implode( ',', $sp_stops ) ); ?>)">
							<span><b><?php echo esc_html( sp_fa( round( $sp_st['pr'] / $sp_tot * 100 ) ) ); ?>٪</b><small>سود</small></span>
						</div>
						<ul class="sp-dpie__key">
							<?php foreach ( $sp_pie as $p ) : ?>
								<li><i style="background:<?php echo esc_attr( $p[2] ); ?>"></i><?php echo esc_html( $p[0] ); ?><b><?php echo esc_html( sp_fa( round( $p[1] ) ) ); ?>٪</b></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php else : ?>
					<p class="sp-dempty">وقتی دستورهات مواد و قیمت کامل داشته باشن، نمودار سودت این‌جا می‌آد.</p>
					<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">شروع کن</a>
				<?php endif; ?>
			</section>

			<!-- اشتراک -->
			<section class="sp-dbox sp-dbox--plan">
				<h2><?php echo sp_icon( 'clock', 20 ); // phpcs:ignore ?>اشتراک من</h2>
				<?php if ( 'free' === $sp_plan['plan'] ) : ?>
					<p class="sp-dplan__big">پلن رایگان</p>
					<p class="sp-dbox__note">یه دستور قابل ویرایش داری و قیمت‌های دستورهات خودکار به‌روز نمی‌شن.</p>
					<a class="sp-btn sp-btn--coral sp-btn--block" href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>">پرو رو ببین</a>
				<?php else : ?>
					<?php $sp_pct = $sp_plan['total'] ? max( 3, min( 100, round( $sp_plan['days'] / $sp_plan['total'] * 100 ) ) ) : 100; ?>
					<p class="sp-dplan__big"><?php echo $sp_plan['days'] ? esc_html( sp_fa( $sp_plan['days'] ) ) . ' <span>روز مونده</span>' : 'فعال'; ?></p>
					<div class="sp-dplan__bar"><i style="--w:<?php echo esc_attr( $sp_pct ); ?>%"></i></div>
					<p class="sp-dbox__note">
						<?php
						if ( 'trial' === $sp_plan['plan'] ) {
							echo 'پرو رایگانته. بعدش یه دستور برات رایگان می‌مونه.';
						} elseif ( ! empty( $sp_plan['until'] ) ) {
							echo 'پرو تا ' . esc_html( $sp_plan['until'] ) . ' فعاله.';
						} else {
							echo 'پروی تو همیشه فعاله.';
						}
						?>
					</p>
					<?php if ( 'trial' === $sp_plan['plan'] ) : ?>
						<a class="sp-btn sp-btn--coral sp-btn--block" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">عضو بنیان‌گذار شو</a>
					<?php endif; ?>
				<?php endif; ?>
				<ul class="sp-dplan__list">
					<li><?php echo sp_icon( 'check', 16 ); // phpcs:ignore ?>ماشین‌حساب با قیمت روز</li>
					<li class="<?php echo $sp_free ? 'is-off' : ''; ?>"><?php echo sp_icon( $sp_free ? 'x' : 'check', 16 ); // phpcs:ignore ?>هر چند تا دستور که بخوای</li>
					<li class="<?php echo $sp_free ? 'is-off' : ''; ?>"><?php echo sp_icon( $sp_free ? 'x' : 'check', 16 ); // phpcs:ignore ?>به‌روز شدن خودکار قیمت‌ها</li>
				</ul>
			</section>
		</div>

		<!-- دستورهای من -->
		<section class="sp-dbox">
			<h2><?php echo sp_icon( 'cake', 20 ); // phpcs:ignore ?>دستورهای من<small><?php echo esc_html( sp_fa( $sp_st['count'] ) ); ?></small></h2>
			<?php if ( $sp_recs ) : ?>
				<div class="sp-dcards">
					<?php
					$sp_i = 0;
					foreach ( $sp_recs as $sp_id => $sp_r ) :
						$sp_i++;
						$s    = $sp_r['sum'] ?? array();
						$ok   = ! empty( $s['p'] ) && empty( $s['miss'] );
						$lock = $sp_free && (string) $sp_id !== (string) get_user_meta( $sp_uid, 'sp_free_recipe', true );
						?>
						<article class="sp-dcard<?php echo $lock ? ' is-lock' : ''; ?>">
							<div class="sp-dcard__top">
								<span class="sp-dcard__k sp-dcard__k--<?php echo ( $sp_r['kitchen'] ?? 'sweet' ) === 'food' ? 'food' : 'sweet'; ?>"><?php echo sp_icon( ( $sp_r['kitchen'] ?? 'sweet' ) === 'food' ? 'pot' : 'cake', 18 ); // phpcs:ignore ?></span>
								<b><?php echo esc_html( $sp_r['name'] ?? 'بدون اسم' ); ?></b>
								<?php if ( $lock ) : ?><span class="sp-dcard__lock" title="توی پلن رایگان فقط‌خواندنیه"><?php echo sp_icon( 'lock', 15 ); // phpcs:ignore ?></span><?php endif; ?>
							</div>
							<p class="sp-dcard__y"><?php echo esc_html( sp_fa( $sp_r['yq'] ?? 1 ) . ' ' . ( $sp_r['yu'] ?? '' ) ); ?></p>
							<?php if ( $ok ) : ?>
								<div class="sp-dcard__nums">
									<span><small>قیمت هر <?php echo esc_html( $s['u'] ?? '' ); ?></small><b><?php echo esc_html( $sp_money( $s['p'] ) ); ?></b></span>
									<span class="is-profit"><small>سود هر <?php echo esc_html( $s['u'] ?? '' ); ?></small><b><?php echo esc_html( $sp_money( $s['pr'] ) ); ?></b></span>
								</div>
							<?php else : ?>
								<p class="sp-dcard__inc"><?php echo sp_icon( 'info', 15 ); // phpcs:ignore ?>هنوز کامل نیست</p>
							<?php endif; ?>
							<a class="sp-dcard__go" href="<?php echo esc_url( add_query_arg( 'r', $sp_id, sp_url( 'calculator' ) ) ); ?>"><?php echo $lock ? 'ببینش' : 'باز کن و ویرایش کن'; ?><?php echo sp_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
						</article>
					<?php endforeach; ?>
					<a class="sp-dcard sp-dcard--new" href="<?php echo esc_url( add_query_arg( 'new', '1', sp_url( 'calculator' ) ) ); ?>"><?php echo sp_icon( 'plus', 22 ); // phpcs:ignore ?><b>دستور تازه</b><small>یه کیک یا غذای دیگه حساب کن</small></a>
				</div>
			<?php else : ?>
				<p class="sp-dempty">هنوز چیزی ذخیره نکردی.</p>
				<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>">اولین دستورت رو بساز</a>
			<?php endif; ?>
		</section>

		<!-- مقاله‌های ذخیره‌شده -->
		<section class="sp-dbox">
			<h2><?php echo sp_icon( 'book', 20 ); // phpcs:ignore ?>برای بعد ذخیره کردم<small><?php echo esc_html( sp_fa( count( $sp_saved ) ) ); ?></small></h2>
			<?php
			$sp_posts = $sp_saved ? get_posts( array( 'post__in' => $sp_saved, 'orderby' => 'post__in', 'numberposts' => 24, 'post_status' => 'publish' ) ) : array();
			if ( $sp_posts ) :
				?>
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
				<p class="sp-dempty">هر مطلبی از مجله رو که خواستی بعداً بخونی، با دکمه‌ی «ذخیره برای بعد» این‌جا نگه دار.</p>
				<a class="sp-btn sp-btn--paper sp-btn--sm" href="<?php echo esc_url( sp_blog_url() ); ?>">برو به مجله</a>
			<?php endif; ?>
		</section>

	</div>
</section>
<?php
get_footer();
