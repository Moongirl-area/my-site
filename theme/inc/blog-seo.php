<?php
/**
 * سئوی مجله: عنوان، توضیح متا، Open Graph و داده‌ی ساختاریافته.
 * اگر Yoast یا Rank Math فعال باشد، عنوان و متا و OG را به آن‌ها می‌سپاریم
 * (مقدارها را واردکننده در فیلدهای خودشان هم نوشته) و فقط اسکیمای FAQ را اضافه می‌کنیم.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp', function () {
	if ( is_page( 'blog' ) && ! sp_seo_plugin_active() ) {
		remove_action( 'wp_head', 'rel_canonical' ); // خودمان با شماره‌ی صفحه می‌گذاریم
	}
} );

function sp_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' );
}

/** توضیح متای صفحه‌ی فعلی. */
function sp_meta_description() {
	if ( is_singular( 'post' ) ) {
		$d = get_post_meta( get_the_ID(), '_sp_meta_desc', true );
		return $d ? $d : wp_trim_words( get_the_excerpt(), 30, '' );
	}
	if ( is_page( 'blog' ) ) {
		return 'مجله‌ی سودپز: آموزش قیمت‌گذاری کیک خانگی و غذای خانگی، قیمت تمام‌شده‌ی دستورها با قیمت مواد ۱۴۰۵، و نکته‌های فروش برای آشپزهای خانگی.';
	}
	if ( is_category() ) {
		$c = sp_blog_cat( get_queried_object()->slug );
		return $c['name'] . ': ' . $c['desc'];
	}
	return '';
}

add_filter( 'pre_get_document_title', function ( $title ) {
	if ( sp_seo_plugin_active() ) {
		return $title;
	}
	if ( is_singular( 'post' ) ) {
		$t = get_post_meta( get_the_ID(), '_sp_seo_title', true );
		if ( $t ) {
			return $t . ' | سودپز';
		}
	}
	if ( is_page( 'blog' ) ) {
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		return 'مجله‌ی سودپز: قیمت‌گذاری کیک و غذای خانگی' . ( $paged > 1 ? ' – صفحه‌ی ' . sp_fa( $paged ) : '' );
	}
	if ( is_category() ) {
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		return sp_blog_cat( get_queried_object()->slug )['name'] . ( $paged > 1 ? ' – صفحه‌ی ' . sp_fa( $paged ) : '' ) . ' | مجله‌ی سودپز';
	}
	return $title;
} );

add_action( 'wp_head', function () {
	if ( ! sp_is_blog_view() ) {
		return;
	}
	if ( ! sp_seo_plugin_active() ) {
		$desc = sp_meta_description();
		if ( $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		if ( is_search() ) {
			echo '<meta name="robots" content="noindex,follow">' . "\n";
		}
		// آدرس اصلی (canonical) برای صفحه‌ی مجله و سرفصل‌ها؛ برای مطلب، خود وردپرس می‌گذارد.
		if ( is_page( 'blog' ) || is_category() ) {
			$paged = max( 1, (int) get_query_var( 'paged' ) );
			$base  = is_category() ? get_term_link( get_queried_object() ) : sp_blog_url();
			echo '<link rel="canonical" href="' . esc_url( $base . ( $paged > 1 ? 'page/' . $paged . '/' : '' ) ) . '">' . "\n";
		}
		// Open Graph
		$og = array(
			'og:locale'    => 'fa_IR',
			'og:site_name' => 'سودپز',
			'og:type'      => is_singular( 'post' ) ? 'article' : 'website',
			'og:title'     => wp_get_document_title(),
			'og:description' => $desc,
			'og:url'       => is_singular() ? get_permalink() : ( is_category() ? get_term_link( get_queried_object() ) : sp_blog_url() ),
		);
		if ( is_singular( 'post' ) && has_post_thumbnail() ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			if ( $img ) {
				$og['og:image']        = $img[0];
				$og['og:image:width']  = $img[1];
				$og['og:image:height'] = $img[2];
				$og['og:image:alt']    = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
			}
			$og['article:published_time'] = get_the_date( 'c' );
			$og['article:modified_time']  = get_the_modified_date( 'c' );
		}
		foreach ( $og as $k => $v ) {
			if ( '' !== (string) $v ) {
				echo '<meta property="' . esc_attr( $k ) . '" content="' . esc_attr( $v ) . '">' . "\n";
			}
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	}

	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$id    = get_the_ID();
	$graph = array();
	if ( ! sp_seo_plugin_active() ) {
		$img     = has_post_thumbnail() ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) : null;
		$graph[] = array(
			'@type'            => 'BlogPosting',
			'headline'         => get_the_title(),
			'description'      => sp_meta_description(),
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'inLanguage'       => 'fa-IR',
			'mainEntityOfPage' => get_permalink(),
			'image'            => $img ? array( '@type' => 'ImageObject', 'url' => $img[0], 'width' => $img[1], 'height' => $img[2] ) : null,
			'author'           => array( '@type' => 'Organization', 'name' => 'سودپز', 'url' => home_url( '/' ) ),
			'publisher'        => array( '@type' => 'Organization', 'name' => 'سودپز', 'url' => home_url( '/' ) ),
			'articleSection'   => sp_blog_cat( sp_post_cat_slug( $id ) )['name'],
			'keywords'         => get_post_meta( $id, '_sp_focus_kw', true ),
			'wordCount'        => count( preg_split( '/\s+/u', wp_strip_all_tags( get_post_field( 'post_content', $id ) ), -1, PREG_SPLIT_NO_EMPTY ) ),
		);
	}
	$faq = (array) get_post_meta( $id, '_sp_faq', true );
	if ( $faq && ! empty( $faq[0]['q'] ) ) {
		$items = array();
		foreach ( $faq as $f ) {
			$items[] = array( '@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ) );
		}
		$graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $items );
	}
	if ( $graph ) {
		echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => array_values( array_filter( $graph ) ) ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}, 5 );

// عکس شاخص مطلب در سایت‌مپ وردپرس (wp-sitemap.xml) نیاز به افزونه ندارد؛ فقط صفحه‌ی learn را از سایت‌مپ حذف کن.
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $type ) {
	if ( 'page' === $type ) {
		$learn = get_page_by_path( 'learn' );
		if ( $learn ) {
			$args['post__not_in'] = array_merge( (array) ( $args['post__not_in'] ?? array() ), array( $learn->ID ) );
		}
	}
	return $args;
}, 10, 2 );
