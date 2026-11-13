<?php
/* یک مطلب مجله: /blog/{سرفصل}/{نامک}/ */
get_header();
while ( have_posts() ) :
	the_post();
	$sp_id      = get_the_ID();
	$sp_slug    = sp_post_cat_slug( $sp_id );
	$sp_c       = sp_blog_cat( $sp_slug );
	$sp_summary = (array) get_post_meta( $sp_id, '_sp_summary', true );
	$sp_faq     = (array) get_post_meta( $sp_id, '_sp_faq', true );
	$sp_sources = (array) get_post_meta( $sp_id, '_sp_sources', true );
	$sp_html    = apply_filters( 'the_content', get_the_content() );
	$sp_toc     = array();
	$sp_html    = sp_toc_prepare( $sp_html, $sp_toc );
	$sp_updated = get_the_modified_date( 'U' ) - get_the_date( 'U' ) > DAY_IN_SECONDS;
	$sp_url     = get_permalink();
	$sp_thumb   = get_post_thumbnail_id();
	?>
	<div class="sp-progress" aria-hidden="true"><span></span></div>

	<article class="sp-post" style="--c:<?php echo esc_attr( $sp_c['color'] ); ?>">
		<header class="sp-post__head">
			<div class="sp-wrap sp-post__headwrap">
				<?php echo sp_breadcrumb( array( array( 'سودپز', home_url( '/' ) ), array( 'مجله', sp_blog_url() ), array( $sp_c['short'], sp_blog_url( $sp_slug ) ), array( get_the_title(), $sp_url ) ) ); // phpcs:ignore ?>
				<?php echo sp_cat_chip( $sp_slug ); // phpcs:ignore ?>
				<h1 class="sp-post__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="sp-post__dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<div class="sp-post__meta">
					<span class="sp-post__by"><?php echo sp_logo_svg( 34 ); // phpcs:ignore ?><span><b>تیم سودپز</b><small>رفیق حسابگر آشپزخونه</small></span></span>
					<span><?php echo sp_icon( 'clock', 16 ); ?><?php echo esc_html( sp_fa( sp_read_minutes( $sp_id ) ) ); ?> دقیقه مطالعه</span>
					<span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( sp_post_jdate( $sp_id ) ); ?></time></span>
					<?php if ( $sp_updated ) : ?>
						<span class="sp-post__upd"><?php echo sp_icon( 'refresh', 16 ); ?>به‌روزرسانی <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( sp_post_jdate( $sp_id, true ) ); ?></time></span>
					<?php endif; ?>
				</div>
			</div>
		</header>

		<?php if ( $sp_thumb || get_post_meta( $sp_id, '_sp_img', true ) ) : ?>
			<figure class="sp-wrap sp-post__cover">
				<?php echo sp_post_img( $sp_id, 'sp-post__img', true, '(max-width: 1220px) 100vw, 1180px' ); // phpcs:ignore ?>
				<?php $sp_cap = $sp_thumb ? wp_get_attachment_caption( $sp_thumb ) : ''; ?>
				<?php if ( $sp_cap ) : ?>
					<figcaption><?php echo esc_html( $sp_cap ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="sp-wrap sp-post__layout">
			<div class="sp-post__main">
				<?php if ( array_filter( $sp_summary ) ) : ?>
					<aside class="sp-glance" aria-label="خلاصه">
						<p class="sp-glance__h"><?php echo sp_icon( 'spark', 18 ); ?>در یک نگاه</p>
						<ul>
							<?php foreach ( $sp_summary as $s ) : ?>
								<li><?php echo substr( sp_ltr_numbers( wp_kses( $s, array( 'b' => array(), 'strong' => array() ) ) ), 1, -1 ); // phpcs:ignore ?></li>
							<?php endforeach; ?>
						</ul>
					</aside>
				<?php endif; ?>

				<?php if ( count( $sp_toc ) >= 3 ) : ?>
					<details class="sp-toc-mobile">
						<summary><?php echo sp_icon( 'list', 18 ); ?>فهرست این مطلب</summary>
						<ol><?php foreach ( $sp_toc as $t ) : ?><li><a href="#<?php echo esc_attr( $t['id'] ); ?>"><?php echo esc_html( $t['text'] ); ?></a></li><?php endforeach; ?></ol>
					</details>
				<?php endif; ?>

				<div class="sp-prose sp-post__body">
					<?php echo $sp_html; // phpcs:ignore -- محتوای خود مطلب ?>
				</div>

				<?php if ( $sp_faq && ! empty( $sp_faq[0]['q'] ) ) : ?>
					<section class="sp-pfaq" aria-labelledby="sp-faq-h">
						<h2 id="sp-faq-h">سؤال‌های رایج</h2>
						<?php foreach ( $sp_faq as $i => $f ) : ?>
							<details<?php echo 0 === $i ? ' open' : ''; ?>>
								<summary><?php echo esc_html( $f['q'] ); ?></summary>
								<div><?php echo wp_kses_post( wpautop( $f['a'] ) ); ?></div>
							</details>
						<?php endforeach; ?>
					</section>
				<?php endif; ?>

				<?php if ( $sp_sources && ! empty( $sp_sources[0]['url'] ) ) : ?>
					<section class="sp-sources">
						<p class="sp-sources__h">منبع‌ها</p>
						<ul>
							<?php foreach ( $sp_sources as $s ) : ?>
								<li><a href="<?php echo esc_url( $s['url'] ); ?>" rel="nofollow noopener" target="_blank"><?php echo esc_html( $s['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endif; ?>

				<?php
				$sp_tags = get_the_tags();
				if ( $sp_tags ) :
					?>
					<p class="sp-post__tags"><?php foreach ( $sp_tags as $t ) : ?><span>#<?php echo esc_html( str_replace( ' ', '_', $t->name ) ); ?></span><?php endforeach; ?></p>
				<?php endif; ?>

				<div class="sp-share">
					<span>این مطلب رو بفرست برای یه آشپز دیگه:</span>
					<?php
					$sp_enc = rawurlencode( $sp_url );
					$sp_tt  = rawurlencode( get_the_title() );
					?>
					<a class="sp-share__btn" href="https://t.me/share/url?url=<?php echo $sp_enc; // phpcs:ignore ?>&text=<?php echo $sp_tt; // phpcs:ignore ?>" target="_blank" rel="noopener"><?php echo sp_icon( 'send', 18 ); ?>تلگرام</a>
					<a class="sp-share__btn" href="https://wa.me/?text=<?php echo $sp_tt . '%20' . $sp_enc; // phpcs:ignore ?>" target="_blank" rel="noopener"><?php echo sp_icon( 'phone', 18 ); ?>واتس‌اپ</a>
					<button class="sp-share__btn" type="button" data-copy="<?php echo esc_attr( $sp_url ); ?>"><?php echo sp_icon( 'link', 18 ); ?><span>کپی لینک</span></button>
				</div>

				<?php sp_post_cta( $sp_id ); ?>
			</div>

			<aside class="sp-post__side">
				<?php if ( count( $sp_toc ) >= 3 ) : ?>
					<nav class="sp-toc" aria-label="فهرست این مطلب">
						<p class="sp-toc__h"><?php echo sp_icon( 'list', 18 ); ?>فهرست</p>
						<ol>
							<?php foreach ( $sp_toc as $t ) : ?>
								<li><a href="#<?php echo esc_attr( $t['id'] ); ?>"><?php echo esc_html( $t['text'] ); ?></a></li>
							<?php endforeach; ?>
						</ol>
					</nav>
				<?php endif; ?>
				<?php $sp_food = 'food' === get_post_meta( $sp_id, '_sp_cta', true ); ?>
				<div class="sp-side-cta">
					<?php echo sp_icon( 'calc', 26 ); ?>
					<p><b><?php echo $sp_food ? 'قیمت هر پرس غذات رو حساب کن' : 'قیمت کیکت رو حساب کن'; ?></b><br>رایگان، بدون ثبت‌نام</p>
					<a class="sp-btn sp-btn--coral sp-btn--sm sp-btn--block" href="<?php echo esc_url( add_query_arg( 'k', $sp_food ? 'food' : 'sweet', sp_url( 'calculator' ) ) ); ?>">ماشین‌حساب</a>
				</div>
			</aside>
		</div>
	</article>

	<?php
	$sp_rel = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'post__not_in'   => array( $sp_id ),
		'category_name'  => $sp_slug,
	) );
	if ( count( $sp_rel ) < 3 ) {
		$sp_rel = array_merge( $sp_rel, get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3 - count( $sp_rel ), 'post__not_in' => array_merge( array( $sp_id ), wp_list_pluck( $sp_rel, 'ID' ) ) ) ) );
	}
	if ( $sp_rel ) :
		?>
		<section class="sp-related">
			<div class="sp-wrap">
				<div class="sp-sechead sp-sechead--row">
					<h2 class="sp-sechead__h">اینا رو هم بخون</h2>
					<a class="sp-more" href="<?php echo esc_url( sp_blog_url( $sp_slug ) ); ?>"><?php echo esc_html( $sp_c['short'] ); ?> <?php echo sp_icon( 'arrow', 16 ); ?></a>
				</div>
				<div class="sp-bgrid">
					<?php foreach ( $sp_rel as $p ) { echo sp_blog_card( $p->ID ); } // phpcs:ignore ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>
<?php get_footer(); ?>
