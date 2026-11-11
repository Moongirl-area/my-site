<?php get_header(); ?>
<section class="sp-section">
	<div class="sp-wrap sp-prose">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'sp-entry' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<h1>چیزی پیدا نشد</h1>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">برگرد به صفحه‌ی اصلی</a></p>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
