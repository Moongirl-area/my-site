<?php
/* صفحه‌ی آموزش‌ها (نامک: learn). هر نوشته‌ای که در پیشخوان منتشر کنی، اینجا می‌آید. */
get_header();
$sp_paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$sp_q     = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 9, 'paged' => $sp_paged ) );
?>
<section class="sp-page-head">
	<div class="sp-wrap">
		<p class="sp-script">آموزش</p>
		<h1 class="sp-h1">قیمت‌گذاری کیک خانگی، ساده و بدون ضرر</h1>
		<p class="sp-sub">آموزش‌های کوتاه درباره‌ی حساب قیمت تمام‌شده، سود، و حرف زدن با مشتری درباره‌ی قیمت.</p>
	</div>
</section>
<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<?php if ( $sp_q->have_posts() ) : ?>
			<div class="sp-posts">
				<?php while ( $sp_q->have_posts() ) : $sp_q->the_post(); ?>
					<a class="sp-post-card" href="<?php the_permalink(); ?>">
						<?php echo sp_post_img( get_the_ID(), 'sp-post-card__img' ); // phpcs:ignore ?>
						<div class="sp-post-card__body">
							<small class="sp-small"><?php echo esc_html( sp_fa( sp_read_minutes( get_the_ID() ) ) ); ?> دقیقه مطالعه</small>
							<h2><?php the_title(); ?></h2>
							<p><?php echo esc_html( get_the_excerpt() ); ?></p>
							<span class="sp-post-card__more">بخون <?php echo sp_icon( 'arrow', 16 ); ?></span>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
			<?php
			$sp_links = paginate_links( array( 'total' => $sp_q->max_num_pages, 'current' => $sp_paged ) );
			if ( $sp_links ) {
				echo '<nav class="sp-center" style="margin-top:32px">' . $sp_links . '</nav>'; // phpcs:ignore
			}
			wp_reset_postdata();
			?>
		<?php else : ?>
			<p class="sp-sub">آموزش‌ها به‌زودی اینجا میان.</p>
		<?php endif; ?>
		<?php sp_cta_box(); ?>
	</div>
</section>
<?php get_footer(); ?>
