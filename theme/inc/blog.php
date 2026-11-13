<?php
/**
 * مجله‌ی سودپز: سرفصل‌ها، آدرس‌ها، تاریخ شمسی و قطعه‌های مشترک قالب.
 *
 * ساختار آدرس‌ها:
 *   /blog/                         صفحه‌ی اصلی مجله
 *   /blog/page/2/                  همه‌ی مطالب، صفحه‌ی ۲
 *   /blog/{سرفصل}/                 فهرست یک سرفصل
 *   /blog/{سرفصل}/{نامک}/          یک مطلب
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SP_BLOG_VERSION', 5 );

/**
 * سرفصل‌های مجله (دایرکتوری). ترتیب همین‌جا ترتیب نمایش است.
 * نامک = آدرس لاتین سرفصل.
 */
function sp_blog_cats() {
	return array(
		'cake-pricing'   => array(
			'name'  => 'قیمت‌گذاری کیک و شیرینی',
			'short' => 'کیک و شیرینی',
			'desc'  => 'فرمول قیمت، سود واقعی و قیمت کیلویی کیک‌های خانگی.',
			'color' => '#E8673C',
			'icon'  => 'cake',
		),
		'home-food'      => array(
			'name'  => 'قیمت‌گذاری غذای خانگی',
			'short' => 'غذای خانگی',
			'desc'  => 'قیمت هر پرس، حساب برنج و گوشت، و فروش غذای خونگی بدون ضرر.',
			'color' => '#149C98',
			'icon'  => 'pot',
		),
		'cost-breakdown' => array(
			'name'  => 'قیمت تمام‌شده‌ی دستورها',
			'short' => 'قیمت تمام‌شده',
			'desc'  => 'یک دستور، همه‌ی مواد، عدد دقیق: این کیک یا این غذا واقعاً چند تموم می‌شه.',
			'color' => '#E9A23B',
			'icon'  => 'calc',
		),
		'ingredients'    => array(
			'name'  => 'قیمت مواد اولیه',
			'short' => 'مواد اولیه',
			'desc'  => 'کره، خامه، برنج، گوشت؛ اثر گرونی هر ماده روی قیمت تو.',
			'color' => '#8A4FBF',
			'icon'  => 'tag',
		),
		'home-business'  => array(
			'name'  => 'کسب‌وکار خانگی',
			'short' => 'کسب‌وکار خانگی',
			'desc'  => 'مجوز، فروش اینستاگرامی، حرف زدن با مشتری و رشد آشپزخونه‌ی خانگی.',
			'color' => '#1E8E5A',
			'icon'  => 'users',
		),
	);
}

function sp_blog_cat( $slug ) {
	$c = sp_blog_cats();
	return isset( $c[ $slug ] ) ? $c[ $slug ] : array( 'name' => $slug, 'short' => $slug, 'desc' => '', 'color' => '#77736C', 'icon' => 'book' );
}

/** سرفصل اصلی یک مطلب (نامک). */
function sp_post_cat_slug( $post_id ) {
	$primary = get_post_meta( $post_id, '_sp_primary_cat', true );
	if ( $primary && term_exists( $primary, 'category' ) ) {
		return $primary;
	}
	$terms = get_the_category( $post_id );
	foreach ( $terms as $t ) {
		if ( isset( sp_blog_cats()[ $t->slug ] ) ) {
			return $t->slug;
		}
	}
	return $terms ? $terms[0]->slug : 'cake-pricing';
}

function sp_blog_url( $cat = '' ) {
	return home_url( '/blog/' . ( $cat ? $cat . '/' : '' ) );
}

/* ---------- ساختن سرفصل‌ها و صفحه‌ی مجله، یک بار برای هر نسخه ---------- */

add_action( 'init', function () {
	sp_blog_rewrites();
	if ( (int) get_option( 'sp_blog_version', 0 ) >= SP_BLOG_VERSION ) {
		return;
	}
	if ( ! add_option( 'sp_blog_lock', time(), '', 'no' ) ) {
		if ( time() - (int) get_option( 'sp_blog_lock' ) < 60 ) {
			return;
		}
		update_option( 'sp_blog_lock', time() );
	}
	sp_blog_setup_terms();
	if ( ! get_page_by_path( 'blog', OBJECT, 'page' ) ) {
		wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_name'   => 'blog',
			'post_title'  => 'مجله‌ی سودپز: قیمت‌گذاری کیک و غذای خانگی',
			'post_author' => function_exists( 'sp_owner_id' ) ? sp_owner_id() : 0,
		) );
	}
	flush_rewrite_rules();
	update_option( 'sp_blog_version', SP_BLOG_VERSION );
	delete_option( 'sp_blog_lock' );
}, 15 );

function sp_blog_setup_terms() {
	// سرفصل قدیمی «قیمت‌گذاری» (pricing-tips) همان «قیمت‌گذاری کیک و شیرینی» می‌شود.
	$old = get_term_by( 'slug', 'pricing-tips', 'category' );
	if ( $old ) {
		$new = get_term_by( 'slug', 'cake-pricing', 'category' );
		if ( ! $new ) {
			wp_update_term( $old->term_id, 'category', array( 'slug' => 'cake-pricing' ) );
		} else {
			$ids = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'category' => $old->term_id, 'numberposts' => -1, 'fields' => 'ids' ) );
			foreach ( $ids as $id ) {
				wp_set_post_categories( $id, array( (int) $new->term_id ), false );
			}
			wp_delete_term( $old->term_id, 'category' );
		}
	}
	foreach ( sp_blog_cats() as $slug => $c ) {
		$t = get_term_by( 'slug', $slug, 'category' );
		if ( $t ) {
			wp_update_term( $t->term_id, 'category', array( 'name' => $c['name'], 'description' => $c['desc'] ) );
		} else {
			wp_insert_term( $c['name'], 'category', array( 'slug' => $slug, 'description' => $c['desc'] ) );
		}
	}
}

/* ---------- آدرس‌ها ---------- */

function sp_blog_rewrites() {
	add_rewrite_rule( '^blog/?$', 'index.php?pagename=blog', 'top' );
	add_rewrite_rule( '^blog/page/([0-9]{1,})/?$', 'index.php?pagename=blog&paged=$matches[1]', 'top' );
	add_rewrite_rule( '^blog/([a-z0-9-]+)/page/([0-9]{1,})/?$', 'index.php?category_name=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( '^blog/([a-z0-9-]+)/?$', 'index.php?category_name=$matches[1]', 'top' );
	add_rewrite_rule( '^blog/([a-z0-9-]+)/([a-z0-9-]+)/?$', 'index.php?name=$matches[2]&sp_blog_cat=$matches[1]', 'top' );
}

add_filter( 'query_vars', function ( $v ) {
	$v[] = 'sp_blog_cat';
	return $v;
} );

function sp_pretty() {
	return (bool) get_option( 'permalink_structure' );
}

add_filter( 'post_link', function ( $url, $post ) {
	if ( ! sp_pretty() || 'post' !== $post->post_type || in_array( $post->post_status, array( 'draft', 'auto-draft', 'pending' ), true ) ) {
		return $url;
	}
	return home_url( '/blog/' . sp_post_cat_slug( $post->ID ) . '/' . $post->post_name . '/' );
}, 10, 2 );

add_filter( 'term_link', function ( $url, $term, $tax ) {
	if ( sp_pretty() && 'category' === $tax && 0 === (int) $term->parent ) {
		return home_url( '/blog/' . $term->slug . '/' );
	}
	return $url;
}, 10, 3 );

/** آدرس‌های قدیمی (/learn/، /category/x/، /نامک/) با ۳۰۱ به آدرس جدید می‌روند. */
add_action( 'template_redirect', function () {
	if ( ! sp_pretty() || is_admin() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ); // phpcs:ignore
	$path = rawurldecode( $path );

	if ( 'learn' === $path || 0 === strpos( $path, 'learn/' ) ) {
		wp_safe_redirect( sp_blog_url(), 301 );
		exit;
	}
	if ( preg_match( '#^category/([^/]+)/?(page/(\d+))?#', $path, $m ) ) {
		$slug = 'pricing-tips' === $m[1] ? 'cake-pricing' : $m[1];
		wp_safe_redirect( sp_blog_url( $slug ) . ( ! empty( $m[3] ) ? 'page/' . (int) $m[3] . '/' : '' ), 301 );
		exit;
	}
	if ( is_singular( 'post' ) ) {
		$want = trim( (string) wp_parse_url( get_permalink(), PHP_URL_PATH ), '/' );
		if ( rawurldecode( $want ) !== $path && ! is_preview() ) {
			wp_safe_redirect( get_permalink(), 301 );
			exit;
		}
	}
	if ( is_page( 'blog' ) && (int) get_query_var( 'paged' ) > max( 1, (int) ceil( wp_count_posts( 'post' )->publish / 9 ) ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		return;
	}
	if ( is_category() && 0 !== strpos( $path, 'blog/' ) ) {
		wp_safe_redirect( get_term_link( get_queried_object() ), 301 );
		exit;
	}
}, 1 );

// صفحه‌ی آموزش‌ها جایش را به مجله داده؛ لینک‌های قدیمی sp_url('learn') هم به مجله بروند.
add_filter( 'page_link', function ( $url, $id ) {
	$p = get_post( $id );
	return ( $p && 'learn' === $p->post_name ) ? sp_blog_url() : $url;
}, 10, 2 );

/* ---------- تاریخ شمسی ---------- */

function sp_jalali( $gy, $gm, $gd ) {
	$g_d_m = array( 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );
	$gy2   = ( $gm > 2 ) ? ( $gy + 1 ) : $gy;
	$days  = 355666 + ( 365 * $gy ) + (int) ( ( $gy2 + 3 ) / 4 ) - (int) ( ( $gy2 + 99 ) / 100 ) + (int) ( ( $gy2 + 399 ) / 400 ) + $gd + $g_d_m[ $gm - 1 ];
	$jy    = -1595 + ( 33 * (int) ( $days / 12053 ) );
	$days %= 12053;
	$jy   += 4 * (int) ( $days / 1461 );
	$days %= 1461;
	if ( $days > 365 ) {
		$jy  += (int) ( ( $days - 1 ) / 365 );
		$days = ( $days - 1 ) % 365;
	}
	$jm = ( $days < 186 ) ? 1 + (int) ( $days / 31 ) : 7 + (int) ( ( $days - 186 ) / 30 );
	$jd = 1 + ( ( $days < 186 ) ? ( $days % 31 ) : ( ( $days - 186 ) % 30 ) );
	return array( $jy, $jm, $jd );
}

/** «۲۲ آبان ۱۴۰۵» از یک زمان (timestamp محلی وردپرس). */
function sp_jdate( $ts ) {
	$months = array( '', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند' );
	list( $y, $m, $d ) = sp_jalali( (int) gmdate( 'Y', $ts ), (int) gmdate( 'n', $ts ), (int) gmdate( 'j', $ts ) );
	return sp_fa( $d, false ) . ' ' . $months[ $m ] . ' ' . sp_fa( $y, false );
}

function sp_post_jdate( $post_id, $modified = false ) {
	$p = get_post( $post_id );
	return sp_jdate( strtotime( $modified ? $p->post_modified : $p->post_date ) );
}

/* ---------- فهرست مطالب (از تیترهای h2) ---------- */

/** به هر h2 یک شناسه می‌دهد و فهرست را برمی‌گرداند. */
function sp_toc_prepare( $html, &$toc ) {
	$toc = array();
	$i   = 0;
	return preg_replace_callback( '#<h2([^>]*)>(.*?)</h2>#su', function ( $m ) use ( &$toc, &$i ) {
		$i++;
		$id = 's' . $i;
		if ( preg_match( '#id="([^"]+)"#', $m[1], $idm ) ) {
			$id = $idm[1];
			$attrs = $m[1];
		} else {
			$attrs = $m[1] . ' id="' . $id . '"';
		}
		$toc[] = array( 'id' => $id, 'text' => wp_strip_all_tags( $m[2] ) );
		return '<h2' . $attrs . '>' . $m[2] . '</h2>';
	}, $html );
}

/* ---------- قطعه‌های قالب ---------- */

/** برچسب رنگی سرفصل. */
function sp_cat_chip( $slug, $link = true ) {
	$c   = sp_blog_cat( $slug );
	$tag = $link ? 'a href="' . esc_url( sp_blog_url( $slug ) ) . '"' : 'span';
	return '<' . $tag . ' class="sp-catchip" style="--c:' . esc_attr( $c['color'] ) . '">' . sp_icon( $c['icon'], 14 ) . esc_html( $c['short'] ) . '</' . ( $link ? 'a' : 'span' ) . '>';
}

/** کارت مطلب. $size: md | lg | row */
function sp_blog_card( $post_id, $size = 'md', $eager = false ) {
	$cat = sp_post_cat_slug( $post_id );
	$url = get_permalink( $post_id );
	ob_start();
	?>
	<article class="sp-bcard sp-bcard--<?php echo esc_attr( $size ); ?>" style="--c:<?php echo esc_attr( sp_blog_cat( $cat )['color'] ); ?>">
		<a class="sp-bcard__media" href="<?php echo esc_url( $url ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			$sizes = array( 'lg' => '(max-width: 1000px) 100vw, 720px', 'row' => '140px', 'md' => '(max-width: 680px) 100vw, (max-width: 1000px) 50vw, 380px' );
			echo sp_post_img( $post_id, 'sp-bcard__img', $eager, $sizes[ $size ] ?? $sizes['md'] ); // phpcs:ignore
			?>
		</a>
		<div class="sp-bcard__body">
			<div class="sp-bcard__top"><?php echo sp_cat_chip( $cat ); // phpcs:ignore ?><span class="sp-bcard__time"><?php echo sp_icon( 'clock', 14 ); ?><?php echo esc_html( sp_fa( sp_read_minutes( $post_id ) ) ); ?> دقیقه</span></div>
			<h3 class="sp-bcard__title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
			<?php if ( 'row' !== $size ) : ?>
				<p class="sp-bcard__ex"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 'lg' === $size ? 40 : 22, '…' ) ); ?></p>
			<?php endif; ?>
			<span class="sp-bcard__date"><?php echo esc_html( sp_post_jdate( $post_id ) ); ?></span>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/** آدرس‌دهی کوچک بالای صفحه. */
function sp_breadcrumb( $items ) {
	$ld  = array();
	$out = '<nav class="sp-crumbs" aria-label="مسیر"><ol>';
	foreach ( $items as $i => $it ) {
		$last = $i === count( $items ) - 1;
		$out .= '<li>' . ( $last ? '<span aria-current="page">' . esc_html( $it[0] ) . '</span>' : '<a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a>' ) . '</li>';
		$ld[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $it[0], 'item' => $it[1] );
	}
	$out .= '</ol></nav>';
	$out .= '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $ld ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
	return $out;
}

/** شماره‌صفحه‌ها با عدد فارسی (فقط متن لینک‌ها، نه آدرس‌شان). */
function sp_pager( $base, $total, $current ) {
	$links = paginate_links( array(
		'base'      => $base . 'page/%#%/',
		'format'    => '',
		'total'     => (int) $total,
		'current'   => (int) $current,
		'prev_text' => 'قبلی',
		'next_text' => 'بعدی',
	) );
	if ( ! $links ) {
		return '';
	}
	// صفحه‌ی ۱ آدرس پایه است، نه page/1/
	$links = str_replace( $base . 'page/1/', $base, $links );
	$links = preg_replace_callback( '#>([^<]*)<#u', function ( $m ) {
		return '>' . sp_fa( $m[1], false ) . '<';
	}, $links );
	return '<nav class="sp-pager" aria-label="صفحه‌ها">' . $links . '</nav>';
}

/** تعداد مطالب منتشرشده‌ی یک سرفصل. */
function sp_cat_count( $slug ) {
	$t = get_term_by( 'slug', $slug, 'category' );
	return $t ? (int) $t->count : 0;
}

/** کادر دعوت مناسب هر مطلب: کیک ← ماشین‌حساب، غذا ← بنیان‌گذار. */
function sp_post_cta( $post_id ) {
	$type = get_post_meta( $post_id, '_sp_cta', true );
	if ( 'food' === $type ) {
		?>
		<div class="sp-cta-box sp-cta-box--food">
			<div>
				<h3>قیمت‌گذاری غذای خانگی داره به سودپز اضافه می‌شه</h3>
				<p>بنیان‌گذار شو تا اولین نفری باشی که قیمت هر پرس رو با یه کلیک حساب می‌کنه.</p>
			</div>
			<a class="sp-btn sp-btn--coral" href="<?php echo esc_url( sp_url( 'founders' ) ); ?>"><?php echo sp_icon( 'spark', 20 ); ?>بنیان‌گذار شو</a>
		</div>
		<?php
		return;
	}
	sp_cta_box();
}

/* ---------- استایل و اسکریپت مجله ---------- */

function sp_is_blog_view() {
	return is_page( 'blog' ) || is_singular( 'post' ) || is_category() || is_tag() || ( is_search() && ! is_admin() );
}

add_action( 'wp_enqueue_scripts', function () {
	if ( ! sp_is_blog_view() ) {
		return;
	}
	$css = SP_DIR . '/assets/css/blog.css';
	wp_enqueue_style( 'sp-blog', SP_URI . '/assets/css/blog.css', array( 'sp-main' ), file_exists( $css ) ? filemtime( $css ) : SP_VERSION );
	if ( is_singular( 'post' ) ) {
		$js = SP_DIR . '/assets/js/blog.js';
		wp_enqueue_script( 'sp-blog', SP_URI . '/assets/js/blog.js', array(), file_exists( $js ) ? filemtime( $js ) : SP_VERSION, true );
	}
}, 25 );

// جست‌وجوی سایت فقط بین مطالب مجله.
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && $q->is_search() ) {
		$q->set( 'post_type', 'post' );
	}
} );

/**
 * عددهای اعشاری و هزارگان فارسی (۱٫۳ یا ۱٬۳۵۵) در متن راست‌به‌چپ گاهی جابه‌جا نمایش داده می‌شوند.
 * هر عدد را در یک span چپ‌به‌راست جدا می‌کنیم؛ فقط متن بین تگ‌ها، نه داخل آدرس‌ها.
 */
function sp_ltr_numbers( $html ) {
	return preg_replace_callback( '#>([^<]+)<#u', function ( $m ) {
		return '>' . preg_replace( '#[۰-۹]+(?:[٫٬.,/][۰-۹]+)+#u', '<span class="sp-n">$0</span>', $m[1] ) . '<';
	}, '>' . $html . '<' );
}
add_filter( 'the_content', function ( $html ) {
	if ( ! is_singular( 'post' ) ) {
		return $html;
	}
	$out = sp_ltr_numbers( $html );
	return substr( $out, 1, -1 );
}, 30 );
