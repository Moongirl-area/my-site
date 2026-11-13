<?php
/* یک سرفصل مجله: /blog/{سرفصل}/ */
get_header();
$sp_term  = get_queried_object();
$sp_c     = sp_blog_cat( $sp_term->slug );
$sp_paged = max( 1, (int) get_query_var( 'paged' ) );
global $wp_query;
?>
<section class="sp-cat-hero" style="--c:<?php echo esc_attr( $sp_c['color'] ); ?>">
	<div class="sp-wrap">
		<?php
		$sp_crumbs = array( array( 'سودپز', home_url( '/' ) ), array( 'مجله', sp_blog_url() ), array( $sp_c['short'], get_term_link( $sp_term ) ) );
		if ( $sp_paged > 1 ) {
			$sp_crumbs[] = array( 'صفحه‌ی ' . sp_fa( $sp_paged ), '' );
		}
		echo sp_breadcrumb( $sp_crumbs ); // phpcs:ignore
		?>
		<div class="sp-cat-hero__row">
			<span class="sp-cat-hero__ico"><?php echo sp_icon( $sp_c['icon'], 34 ); ?></span>
			<div>
				<h1 class="sp-display sp-display--md"><?php echo esc_html( $sp_c['name'] ); ?><?php echo $sp_paged > 1 ? '<small> صفحه‌ی ' . esc_html( sp_fa( $sp_paged ) ) . '</small>' : ''; ?></h1>
				<p class="sp-lead"><?php echo esc_html( $sp_c['desc'] ); ?></p>
				<p class="sp-small"><?php echo esc_html( sp_fa( $sp_term->count ) ); ?> مطلب</p>
			</div>
		</div>
		<nav class="sp-cat-tabs" aria-label="سرفصل‌های دیگر">
			<a href="<?php echo esc_url( sp_blog_url() ); ?>">همه</a>
			<?php foreach ( sp_blog_cats() as $slug => $c ) : ?>
				<a href="<?php echo esc_url( sp_blog_url( $slug ) ); ?>" style="--c:<?php echo esc_attr( $c['color'] ); ?>"<?php echo $slug === $sp_term->slug ? ' aria-current="page"' : ''; ?>><?php echo sp_icon( $c['icon'], 16 ); ?><?php echo esc_html( $c['short'] ); ?></a>
			<?php endforeach; ?>
		</nav>
	</div>
</section>

<section class="sp-section sp-section--tight">
	<div class="sp-wrap">
		<?php if ( have_posts() ) : ?>
			<?php
			$sp_ids = wp_list_pluck( $wp_query->posts, 'ID' );
			if ( 1 === $sp_paged && count( $sp_ids ) >= 4 ) :
				?>
				<div class="sp-feature-grid-mag">
					<div class="sp-feature-grid-mag__main"><?php echo sp_blog_card( $sp_ids[0], 'lg', true ); // phpcs:ignore ?></div>
					<div class="sp-feature-grid-mag__side">
						<?php foreach ( array_slice( $sp_ids, 1, 3 ) as $id ) { echo sp_blog_card( $id, 'row' ); } // phpcs:ignore ?>
					</div>
				</div>
				<?php
				$sp_ids = array_slice( $sp_ids, 4 );
			endif;
			if ( $sp_ids ) :
				?>
				<div class="sp-bgrid" style="margin-top:32px">
					<?php foreach ( $sp_ids as $id ) { echo sp_blog_card( $id ); } // phpcs:ignore ?>
				</div>
			<?php endif; ?>
			<?php echo sp_pager( get_term_link( $sp_term ), $wp_query->max_num_pages, $sp_paged ); // phpcs:ignore ?>
		<?php else : ?>
			<div class="sp-empty">
				<span class="sp-cat-hero__ico" style="--c:<?php echo esc_attr( $sp_c['color'] ); ?>"><?php echo sp_icon( $sp_c['icon'], 30 ); ?></span>
				<p>اولین مطلب این سرفصل به‌زودی منتشر می‌شه.</p>
				<a class="sp-btn sp-btn--line sp-btn--sm" href="<?php echo esc_url( sp_blog_url() ); ?>">برگرد به مجله</a>
			</div>
		<?php endif; ?>
		<?php sp_cta_box(); ?>
	</div>
</section>
<?php get_footer(); ?>
