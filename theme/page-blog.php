<?php
/* مجله‌ی سودپز (نامک: blog) */
get_header();
$sp_paged = max( 1, (int) get_query_var( 'paged' ) );
$sp_cats  = sp_blog_cats();
$sp_total = (int) wp_count_posts( 'post' )->publish;
$sp_shown = array();

$sp_latest = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 4, 'ignore_sticky_posts' => true ) );
$sp_ids    = wp_list_pluck( $sp_latest->posts, 'ID' );
wp_reset_postdata();
?>

<?php if ( 1 === $sp_paged ) : ?>
<section class="sp-mag-hero">
	<div class="sp-wrap sp-mag-hero__grid">
		<div class="sp-mag-hero__text">
			<?php echo sp_breadcrumb( array( array( 'سودپز', home_url( '/' ) ), array( 'مجله', sp_blog_url() ) ) ); // phpcs:ignore ?>
			<p class="sp-script">مجله‌ی سودپز</p>
			<h1 class="sp-display sp-display--md">بپز، حساب کن،<br><span class="sp-hl">با سود</span> بفروش.</h1>
			<p class="sp-lead">آموزش قیمت‌گذاری کیک و غذای خانگی با عددهای واقعی ۱۴۰۵؛ برای آشپزهایی که از آشپزخونه‌شون درآمد دارن.</p>
			<form class="sp-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="sp-s">جست‌وجو در مجله</label>
				<?php echo sp_icon( 'search', 20 ); ?>
				<input id="sp-s" type="search" name="s" placeholder="مثلاً: قیمت چیزکیک، قیمت هر پرس قرمه‌سبزی…">
				<button class="sp-btn sp-btn--coral sp-btn--sm" type="submit">بگرد</button>
			</form>
			<ul class="sp-mag-stats">
				<li><b><?php echo esc_html( sp_fa( $sp_total ) ); ?></b><span>مطلب</span></li>
				<li><b><?php echo esc_html( sp_fa( count( $sp_cats ) ) ); ?></b><span>سرفصل</span></li>
				<li><b>۱۴۰۵</b><span>قیمت‌های به‌روز</span></li>
			</ul>
		</div>
		<div class="sp-mag-hero__art">
			<div class="sp-mag-hero__frame">
				<?php echo sp_img( 'blog-hero', 'میز آشپزخانه‌ی خانگی با کیک شکلاتی، قرمه‌سبزی، پلو زعفرانی، دفترچه‌ی حساب و ماشین‌حساب', 'sp-mag-hero__img', true ); // phpcs:ignore ?>
			</div>
			<div class="sp-widget sp-mag-hero__note">
				<span class="sp-widget__ico"><?php echo sp_icon( 'calc', 18 ); ?></span>
				<p><b>قیمت تمام‌شده</b><br>= مواد + دستمزد + فر + بسته‌بندی</p>
			</div>
		</div>
	</div>
</section>

<!-- دایرکتوری سرفصل‌ها -->
<section class="sp-dir" aria-labelledby="sp-dir-h">
	<div class="sp-wrap">
		<div class="sp-sechead">
			<h2 id="sp-dir-h" class="sp-sechead__h">سرفصل‌ها</h2>
			<p class="sp-sub">هر چی درباره‌ی قیمت و فروش لازم داری، مرتب و دسته‌بندی‌شده.</p>
		</div>
		<div class="sp-dir__grid">
			<?php foreach ( $sp_cats as $slug => $c ) : ?>
				<a class="sp-dir__tile" href="<?php echo esc_url( sp_blog_url( $slug ) ); ?>" style="--c:<?php echo esc_attr( $c['color'] ); ?>">
					<span class="sp-dir__ico"><?php echo sp_icon( $c['icon'], 26 ); ?></span>
					<span class="sp-dir__name"><?php echo esc_html( $c['name'] ); ?></span>
					<span class="sp-dir__desc"><?php echo esc_html( $c['desc'] ); ?></span>
					<span class="sp-dir__count"><?php echo esc_html( sp_fa( sp_cat_count( $slug ) ) ); ?> مطلب <?php echo sp_icon( 'arrow', 16 ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( $sp_ids ) : ?>
<!-- تازه‌ها -->
<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<div class="sp-sechead sp-sechead--row">
			<h2 class="sp-sechead__h">تازه‌ترین‌ها</h2>
		</div>
		<div class="sp-feature-grid-mag">
			<div class="sp-feature-grid-mag__main"><?php echo sp_blog_card( $sp_ids[0], 'lg', true ); // phpcs:ignore ?></div>
			<div class="sp-feature-grid-mag__side">
				<?php foreach ( array_slice( $sp_ids, 1 ) as $id ) { echo sp_blog_card( $id, 'row' ); } // phpcs:ignore ?>
			</div>
		</div>
	</div>
</section>
<?php $sp_shown = $sp_ids; endif; ?>

<?php
// ردیف هر سرفصل اصلی
foreach ( array( 'cake-pricing', 'home-food' ) as $sp_slug ) :
	$sp_q = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'category_name' => $sp_slug, 'post__not_in' => $sp_shown, 'ignore_sticky_posts' => true ) );
	if ( ! $sp_q->have_posts() ) {
		continue;
	}
	$sp_c = sp_blog_cat( $sp_slug );
	?>
	<section class="sp-rail" style="--c:<?php echo esc_attr( $sp_c['color'] ); ?>">
		<div class="sp-wrap">
			<div class="sp-sechead sp-sechead--row">
				<h2 class="sp-sechead__h"><span class="sp-rail__dot"><?php echo sp_icon( $sp_c['icon'], 20 ); ?></span><?php echo esc_html( $sp_c['name'] ); ?></h2>
				<a class="sp-more" href="<?php echo esc_url( sp_blog_url( $sp_slug ) ); ?>">همه‌ی مطالب این سرفصل <?php echo sp_icon( 'arrow', 16 ); ?></a>
			</div>
			<div class="sp-bgrid">
				<?php foreach ( $sp_q->posts as $p ) { echo sp_blog_card( $p->ID ); $sp_shown[] = $p->ID; } // phpcs:ignore ?>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
endforeach;
?>

<?php
$sp_pillars = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 5, 'meta_key' => '_sp_pillar', 'meta_value' => 1, 'orderby' => 'date', 'order' => 'ASC' ) );
if ( $sp_pillars ) :
	?>
<!-- راهنماهای پایه -->
<section class="sp-start">
	<div class="sp-wrap sp-start__grid">
		<div>
			<p class="sp-script sp-script--light">از اینجا شروع کن</p>
			<h2 class="sp-display sp-display--md">راهنماهای پایه</h2>
			<p class="sp-start__sub">اگه تازه می‌خوای قیمت‌هات رو درست کنی، این‌ها رو به ترتیب بخون.</p>
		</div>
		<ol class="sp-start__list">
			<?php foreach ( $sp_pillars as $i => $p ) : ?>
				<li>
					<a href="<?php echo esc_url( get_permalink( $p ) ); ?>">
						<span class="sp-start__n"><?php echo esc_html( sp_fa( $i + 1 ) ); ?></span>
						<span class="sp-start__t"><?php echo esc_html( get_the_title( $p ) ); ?><small><?php echo wp_kses_post( sp_cat_chip( sp_post_cat_slug( $p->ID ), false ) ); ?> <?php echo esc_html( sp_fa( sp_read_minutes( $p->ID ) ) ); ?> دقیقه</small></span>
						<?php echo sp_icon( 'arrow', 20 ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
<?php endif; ?>
<?php endif; // صفحه‌ی اول ?>

<!-- همه‌ی مطالب -->
<?php
$sp_all = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 9, 'paged' => $sp_paged, 'ignore_sticky_posts' => true ) );
?>
<section class="sp-section sp-section--tight" id="all">
	<div class="sp-wrap">
		<?php if ( $sp_paged > 1 ) : ?>
			<?php echo sp_breadcrumb( array( array( 'سودپز', home_url( '/' ) ), array( 'مجله', sp_blog_url() ), array( 'صفحه‌ی ' . sp_fa( $sp_paged ), '' ) ) ); // phpcs:ignore ?>
			<h1 class="sp-h1">همه‌ی مطالب مجله، صفحه‌ی <?php echo esc_html( sp_fa( $sp_paged ) ); ?></h1>
		<?php else : ?>
			<div class="sp-sechead sp-sechead--row"><h2 class="sp-sechead__h">همه‌ی مطالب</h2></div>
		<?php endif; ?>
		<?php if ( $sp_all->have_posts() ) : ?>
			<div class="sp-bgrid">
				<?php foreach ( $sp_all->posts as $p ) { echo sp_blog_card( $p->ID ); } // phpcs:ignore ?>
			</div>
			<?php
			echo sp_pager( sp_blog_url(), $sp_all->max_num_pages, $sp_paged ); // phpcs:ignore
			wp_reset_postdata();
			?>
		<?php else : ?>
			<p class="sp-sub">مطالب به‌زودی اینجا میان.</p>
		<?php endif; ?>
		<?php sp_cta_box(); ?>
	</div>
</section>
<?php get_footer(); ?>
