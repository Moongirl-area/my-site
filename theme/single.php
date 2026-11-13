<?php
/* یک آموزش */
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article class="sp-section sp-section--tight">
		<div class="sp-wrap sp-article">
			<p class="sp-small"><a href="<?php echo esc_url( sp_url( 'learn' ) ); ?>">→ همه‌ی آموزش‌ها</a></p>
			<h1 class="sp-h1"><?php the_title(); ?></h1>
			<div class="sp-article__meta">
				<span><?php echo sp_icon( 'clock', 16 ); ?> <?php echo esc_html( sp_fa( sp_read_minutes( get_the_ID() ) ) ); ?> دقیقه</span>
			</div>
			<?php echo sp_post_img( get_the_ID(), 'sp-article__img', true ); // phpcs:ignore ?>
			<div class="sp-prose">
				<?php the_content(); ?>
			</div>
			<?php sp_cta_box(); ?>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
