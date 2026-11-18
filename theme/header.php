<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#F6F3EE">
	<link rel="preload" href="<?php echo esc_url( SP_URI . '/assets/fonts/Vazirmatn-var.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo esc_url( SP_URI . '/assets/fonts/Estedad-800.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<script>
	/* آشپزخونه‌ی انتخابی (شیرینی/غذا) قبل از نمایش صفحه، تا چیزی جابه‌جا نپرد. */
	(function () {
		var k = <?php echo wp_json_encode( sp_kitchen_from_request() ); ?>;
		try { if (!k) { k = localStorage.getItem('sp_k') || ''; } } catch (e) {}
		document.documentElement.setAttribute('data-k', k === 'food' ? 'food' : 'sweet');
	})();
	</script>
	<?php if ( ! has_site_icon() ) : ?>
	<link rel="icon" href="<?php echo esc_url( SP_URI . '/assets/img/logo/favicon.svg' ); ?>" type="image/svg+xml">
	<link rel="icon" href="<?php echo esc_url( SP_URI . '/assets/img/logo/favicon-32.png' ); ?>" sizes="32x32" type="image/png">
	<link rel="apple-touch-icon" href="<?php echo esc_url( SP_URI . '/assets/img/logo/apple-touch-icon.png' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="sp-skip" href="#main">رفتن به محتوا</a>
<header class="sp-header">
	<div class="sp-wrap sp-header__inner">
		<a class="sp-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="سودپز، صفحه‌ی اصلی">
			<?php echo sp_logo_svg( 42 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="sp-brand__txt"><span class="sp-brand__name">سودپز</span><span class="sp-brand__tag">حسابدار خوش‌اخلاق آشپزخونه</span></span>
		</a>
		<nav class="sp-nav" aria-label="منوی اصلی">
			<a href="<?php echo esc_url( home_url( '/#features' ) ); ?>"><?php echo sp_icon( 'list', 17 ); ?>امکانات</a>
			<a href="<?php echo esc_url( sp_url( 'calculator' ) ); ?>"<?php echo is_page( 'calculator' ) ? ' aria-current="page"' : ''; ?>><?php echo sp_icon( 'calc', 17 ); ?>ماشین‌حساب<span class="sp-nav__free">رایگان</span></a>
			<a href="<?php echo esc_url( sp_url( 'pricing' ) ); ?>"<?php echo is_page( 'pricing' ) ? ' aria-current="page"' : ''; ?>><?php echo sp_icon( 'tag', 17 ); ?>قیمت‌ها</a>
			<a href="<?php echo esc_url( sp_blog_url() ); ?>"<?php echo sp_is_blog_view() ? ' aria-current="page"' : ''; ?>><?php echo sp_icon( 'book', 17 ); ?>مجله</a>
		</nav>
		<?php if ( is_user_logged_in() ) : ?>
			<a class="sp-hacc" href="<?php echo esc_url( sp_url( 'login' ) ); ?>" title="حساب من"><?php echo sp_icon( 'user', 18 ); ?><span><?php echo esc_html( wp_get_current_user()->display_name ); ?></span></a>
		<?php else : ?>
			<a class="sp-hacc" href="<?php echo esc_url( sp_url( 'login' ) ); ?>"><?php echo sp_icon( 'user', 18 ); ?><span>ورود</span></a>
		<?php endif; ?>
		<a class="sp-btn sp-btn--coral sp-btn--sm" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>"<?php echo is_page( 'founders' ) ? ' aria-current="page"' : ''; ?>><?php echo sp_icon( 'spark', 17 ); ?>بنیان‌گذار شو</a>
	</div>
	<script>(function(){var h=document.querySelector('.sp-header');if(!h)return;var f=function(){h.classList.toggle('is-scrolled',window.scrollY>8);};f();window.addEventListener('scroll',f,{passive:true});})();</script>
</header>
<main id="main">
