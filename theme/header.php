<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#3B2418">
	<link rel="preload" href="<?php echo esc_url( SP_URI . '/assets/fonts/Vazirmatn-var.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo esc_url( SP_URI . '/assets/fonts/NotoNaskh-700.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<script>
	/* آشپزخونه‌ی انتخابی (شیرینی/غذا) قبل از نمایش صفحه، تا چیزی جابه‌جا نپرد. */
	(function () {
		var k = <?php echo wp_json_encode( sp_kitchen_from_request() ); ?>;
		try { if (!k) { k = localStorage.getItem('sp_k') || ''; } } catch (e) {}
		document.documentElement.setAttribute('data-k', k === 'food' ? 'food' : 'sweet');
	})();
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="sp-skip" href="#main">رفتن به محتوا</a>
<header class="sp-header">
	<div class="sp-wrap sp-header__inner">
		<a class="sp-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="سودپز، صفحه‌ی اصلی">
			<?php echo sp_logo_svg( 36 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="sp-brand__name">سودپز</span>
		</a>
		<nav class="sp-nav" aria-label="منوی اصلی">
			<a href="<?php echo esc_url( home_url( '/#features' ) ); ?>">امکانات</a>
			<a href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>"<?php echo is_page( 'calculator' ) ? ' aria-current="page"' : ''; ?>>ماشین‌حساب رایگان</a>
			<a href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>"<?php echo is_page( 'pricing' ) ? ' aria-current="page"' : ''; ?>>قیمت‌ها</a>
			<a href="<?php echo esc_url( sp_blog_url() ); ?>"<?php echo sp_is_blog_view() ? ' aria-current="page"' : ''; ?>>مجله</a>
		</nav>
		<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>">بنیان‌گذار شو</a>
	</div>
</header>
<main id="main">
