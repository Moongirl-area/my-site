<?php
/* نتیجه‌ی جست‌وجو در مجله */
get_header();
global $wp_query;
?>
<section class="sp-page-head">
	<div class="sp-wrap">
		<p class="sp-script">جست‌وجو در مجله</p>
		<h1 class="sp-h1">«<?php echo esc_html( get_search_query() ); ?>»</h1>
		<form class="sp-search sp-search--inline" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php echo sp_icon( 'search', 20 ); ?>
			<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="جست‌وجو">
			<button class="sp-btn sp-btn--coral sp-btn--sm" type="submit">بگرد</button>
		</form>
		<p class="sp-small"><?php echo esc_html( sp_fa( $wp_query->found_posts ) ); ?> نتیجه</p>
	</div>
</section>
<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<?php if ( have_posts() ) : ?>
			<div class="sp-bgrid">
				<?php while ( have_posts() ) : the_post(); echo sp_blog_card( get_the_ID() ); endwhile; // phpcs:ignore ?>
			</div>
			<?php the_posts_pagination( array( 'prev_text' => 'قبلی', 'next_text' => 'بعدی' ) ); ?>
		<?php else : ?>
			<div class="sp-empty">
				<p>چیزی پیدا نشد. یه کلمه‌ی ساده‌تر امتحان کن، یا سرفصل‌ها رو ببین.</p>
				<a class="sp-btn sp-btn--line sp-btn--sm" href="<?php echo esc_url( sp_blog_url() ); ?>">مجله‌ی سودپز</a>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
