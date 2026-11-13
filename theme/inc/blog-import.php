<?php
/**
 * واردکننده‌ی مطالب مجله از فایل‌های پوسته.
 *
 * هر مطلب یک پوشه است:  content/blog/{سرفصل}/{نامک}/
 *   meta.json   عنوان، سئو، تاریخ انتشار، عکس، سؤال‌های رایج
 *   post.html   متن مطلب
 *   *.webp      عکس شاخص در چند اندازه (از قبل فشرده شده)
 *
 * content/blog/index.json فهرست همه‌ی مطالب است؛ هر بار که عوض شود (آپلود جدید)،
 * اولین بازدید سایت مطالب جدید یا تغییرکرده را وارد وردپرس می‌کند.
 *
 * اگر مطلبی را در پیشخوان ویرایش کنی، دیگر از روی فایل بازنویسی نمی‌شود.
 * اگر پاکش کنی (حتی از زباله‌دان)، دوباره ساخته نمی‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SP_BLOG_SRC', SP_DIR . '/content/blog' );

add_action( 'init', function () {
	$index = SP_BLOG_SRC . '/index.json';
	if ( ! file_exists( $index ) ) {
		return;
	}
	$sig = md5_file( $index );
	if ( get_option( 'sp_blog_sig' ) === $sig ) {
		return;
	}
	if ( ! add_option( 'sp_blog_import_lock', time(), '', 'no' ) ) {
		if ( time() - (int) get_option( 'sp_blog_import_lock' ) < 120 ) {
			return;
		}
		update_option( 'sp_blog_import_lock', time() );
	}
	$log = sp_blog_import_all( $index );
	update_option( 'sp_blog_import_log', array( 'time' => time(), 'log' => $log ), false );
	update_option( 'sp_blog_sig', $sig, false );
	delete_option( 'sp_blog_import_lock' );
}, 30 );

function sp_blog_import_all( $index ) {
	$data = json_decode( (string) file_get_contents( $index ), true );
	$log  = array();
	if ( empty( $data['posts'] ) || ! is_array( $data['posts'] ) ) {
		return array( 'index.json خالی یا خراب است' );
	}
	if ( function_exists( 'sp_blog_setup_terms' ) ) {
		sp_blog_setup_terms();
	}
	foreach ( $data['posts'] as $rel ) {
		$rel = trim( (string) $rel, '/' );
		if ( ! preg_match( '#^[a-z0-9-]+/[a-z0-9-]+$#', $rel ) ) {
			$log[] = $rel . ': نام پوشه نامعتبر';
			continue;
		}
		try {
			$log[] = $rel . ': ' . sp_blog_import_one( SP_BLOG_SRC . '/' . $rel, $rel );
		} catch ( \Throwable $e ) {
			$log[] = $rel . ': خطا ' . $e->getMessage();
		}
	}
	return $log;
}

/** یک مطلب را می‌سازد یا به‌روز می‌کند. */
function sp_blog_import_one( $dir, $rel ) {
	$meta_file = $dir . '/meta.json';
	$html_file = $dir . '/post.html';
	if ( ! file_exists( $meta_file ) || ! file_exists( $html_file ) ) {
		return 'فایل ناقص';
	}
	$raw  = file_get_contents( $meta_file ) . file_get_contents( $html_file );
	$hash = md5( $raw );
	$m    = json_decode( (string) file_get_contents( $meta_file ), true );
	if ( ! is_array( $m ) || empty( $m['slug'] ) || empty( $m['title'] ) ) {
		return 'meta.json نامعتبر';
	}
	$slug = sanitize_title( $m['slug'] );

	// آیا قبلاً ساخته شده؟
	$existing = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'any',
		'meta_key'       => '_sp_src',
		'meta_value'     => $rel,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	$post_id = $existing ? (int) $existing[0] : 0;

	if ( ! $post_id && get_option( 'sp_blog_made_' . md5( $rel ) ) ) {
		return 'قبلاً ساخته و پاک شده؛ رد شد';
	}
	if ( $post_id ) {
		if ( get_post_meta( $post_id, '_sp_src_hash', true ) === $hash ) {
			return 'بدون تغییر';
		}
		$p = get_post( $post_id );
		if ( strtotime( $p->post_modified_gmt ) > (int) get_post_meta( $post_id, '_sp_imported_at', true ) + 5 ) {
			return 'در پیشخوان ویرایش شده؛ بازنویسی نشد';
		}
	}

	// تاریخ انتشار
	$tz   = wp_timezone();
	$when = ! empty( $m['publish_at'] ) ? new DateTimeImmutable( $m['publish_at'] ) : new DateTimeImmutable( 'now', $tz );
	$when = $when->setTimezone( $tz );
	$gmt  = $when->setTimezone( new DateTimeZone( 'UTC' ) );
	$status = $gmt->getTimestamp() > time() + 60 ? 'future' : 'publish';
	if ( ! empty( $m['status'] ) && 'draft' === $m['status'] ) {
		$status = 'draft';
	}

	$cat_slug = sanitize_title( $m['category'] ?? 'cake-pricing' );
	$cat      = get_term_by( 'slug', $cat_slug, 'category' );
	if ( ! $cat ) {
		$c   = function_exists( 'sp_blog_cat' ) ? sp_blog_cat( $cat_slug ) : array( 'name' => $cat_slug );
		$new = wp_insert_term( $c['name'], 'category', array( 'slug' => $cat_slug ) );
		$cat = is_wp_error( $new ) ? null : get_term( $new['term_id'], 'category' );
	}

	$arr = array(
		'post_type'     => 'post',
		'post_status'   => $status,
		'post_name'     => $slug,
		'post_title'    => wp_strip_all_tags( $m['title'] ),
		'post_excerpt'  => $m['excerpt'] ?? '',
		'post_content'  => trim( (string) file_get_contents( $html_file ) ),
		'post_author'   => function_exists( 'sp_owner_id' ) ? sp_owner_id() : 1,
		'post_category' => $cat ? array( (int) $cat->term_id ) : array(),
		'tags_input'    => $m['tags'] ?? array(),
		'post_date'     => $when->format( 'Y-m-d H:i:s' ),
		'post_date_gmt' => $gmt->format( 'Y-m-d H:i:s' ),
		'edit_date'     => true,
	);

	// محتوای مطلب HTML تمیز و خودمان نوشته‌ایم؛ فیلتر kses نباید جدول‌ها و کلاس‌ها را بریزد.
	kses_remove_filters();
	if ( $post_id ) {
		$arr['ID'] = $post_id;
		if ( 'publish' === get_post_status( $post_id ) && 'future' === $status ) {
			$arr['post_status'] = 'publish'; // منتشرشده را دوباره زمان‌دار نکن
		}
		$r = wp_update_post( wp_slash( $arr ), true );
	} else {
		$r = wp_insert_post( wp_slash( $arr ), true );
	}
	kses_init_filters();
	if ( is_wp_error( $r ) ) {
		return 'خطای وردپرس: ' . $r->get_error_message();
	}
	$post_id = (int) $r;
	$was_new = empty( $existing );

	update_post_meta( $post_id, '_sp_src', $rel );
	update_post_meta( $post_id, '_sp_primary_cat', $cat_slug );
	update_post_meta( $post_id, '_sp_cta', ( $m['cta'] ?? '' ) === 'food' ? 'food' : 'calculator' );
	update_post_meta( $post_id, '_sp_pillar', ! empty( $m['pillar'] ) ? 1 : 0 );
	update_post_meta( $post_id, '_sp_summary', array_values( array_filter( (array) ( $m['summary'] ?? array() ) ) ) );
	update_post_meta( $post_id, '_sp_faq', array_values( (array) ( $m['faq'] ?? array() ) ) );
	update_post_meta( $post_id, '_sp_sources', array_values( (array) ( $m['sources'] ?? array() ) ) );

	// سئو: هم برای خود پوسته، هم برای Yoast و Rank Math اگر نصب باشند.
	$seo_title = $m['seo_title'] ?? '';
	$desc      = $m['meta_description'] ?? '';
	$kw        = $m['focus_keyword'] ?? '';
	foreach ( array(
		'_sp_seo_title'          => $seo_title,
		'_sp_meta_desc'          => $desc,
		'_sp_focus_kw'           => $kw,
		'_yoast_wpseo_title'     => $seo_title,
		'_yoast_wpseo_metadesc'  => $desc,
		'_yoast_wpseo_focuskw'   => $kw,
		'rank_math_title'        => $seo_title,
		'rank_math_description'  => $desc,
		'rank_math_focus_keyword' => $kw,
	) as $k => $v ) {
		if ( '' !== $v ) {
			update_post_meta( $post_id, $k, $v );
		}
	}

	// عکس شاخص
	if ( ! empty( $m['image']['file'] ) ) {
		$att = sp_blog_import_image( $dir, $m['image'], $post_id, $slug );
		if ( $att ) {
			set_post_thumbnail( $post_id, $att );
		}
	}

	update_post_meta( $post_id, '_sp_src_hash', $hash );
	// زمان ورود را بعد از همه‌ی تغییرها ثبت کن تا ویرایش‌های بعدی تو تشخیص داده شوند.
	clean_post_cache( $post_id );
	update_post_meta( $post_id, '_sp_imported_at', strtotime( get_post( $post_id )->post_modified_gmt ) );
	update_option( 'sp_blog_made_' . md5( $rel ), $post_id, false );

	return ( $was_new ? 'ساخته شد' : 'به‌روز شد' ) . ' (' . $status . ')';
}

/**
 * عکس‌های آماده (webp فشرده در چند اندازه) را به پوشه‌ی آپلود کپی و در کتابخانه‌ی رسانه ثبت می‌کند.
 * وردپرس دوباره برش و فشرده‌سازی نمی‌کند؛ srcset از همین اندازه‌ها ساخته می‌شود.
 */
function sp_blog_import_image( $dir, $img, $post_id, $slug ) {
	$src = $dir . '/' . basename( $img['file'] );
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$hash = md5_file( $src );
	$old  = (int) get_post_thumbnail_id( $post_id );
	if ( $old && get_post_meta( $old, '_sp_img_hash', true ) === $hash ) {
		sp_blog_update_image_text( $old, $img );
		return $old;
	}

	$up = wp_upload_dir( null, false );
	if ( ! empty( $up['error'] ) ) {
		return 0;
	}
	$sub  = 'blog/' . $slug;
	$dest = trailingslashit( $up['basedir'] ) . $sub;
	wp_mkdir_p( $dest );

	$files = array( $img['file'] );
	foreach ( (array) ( $img['sizes'] ?? array() ) as $s ) {
		$files[] = $s['file'];
	}
	foreach ( $files as $f ) {
		$f = basename( $f );
		if ( file_exists( $dir . '/' . $f ) ) {
			copy( $dir . '/' . $f, $dest . '/' . $f );
		}
	}

	$att_id = wp_insert_attachment( array(
		'post_mime_type' => 'image/webp',
		'post_title'     => $img['title'] ?? '',
		'post_excerpt'   => $img['caption'] ?? '',
		'post_content'   => $img['description'] ?? '',
		'post_status'    => 'inherit',
		'post_name'      => $slug . '-cover',
	), $dest . '/' . basename( $img['file'] ), $post_id, true );
	if ( is_wp_error( $att_id ) ) {
		return 0;
	}

	$sizes = array();
	foreach ( (array) ( $img['sizes'] ?? array() ) as $s ) {
		$name           = 'sp-' . (int) $s['width'];
		$sizes[ $name ] = array(
			'file'      => basename( $s['file'] ),
			'width'     => (int) $s['width'],
			'height'    => (int) $s['height'],
			'mime-type' => 'image/webp',
			'filesize'  => (int) @filesize( $dest . '/' . basename( $s['file'] ) ), // phpcs:ignore
		);
	}
	// اندازه‌های استاندارد وردپرس به نزدیک‌ترین نسخه‌ی موجود اشاره کنند.
	$by_w = $sizes;
	usort( $by_w, function ( $a, $b ) {
		return $a['width'] - $b['width'];
	} );
	$pick = function ( $min ) use ( $by_w ) {
		foreach ( $by_w as $s ) {
			if ( $s['width'] >= $min ) {
				return $s;
			}
		}
		return end( $by_w );
	};
	if ( $by_w ) {
		$sizes['thumbnail']    = $pick( 150 );
		$sizes['medium']       = $pick( 300 );
		$sizes['medium_large'] = $pick( 768 );
	}

	wp_update_attachment_metadata( $att_id, array(
		'width'    => (int) $img['width'],
		'height'   => (int) $img['height'],
		'file'     => $sub . '/' . basename( $img['file'] ),
		'filesize' => (int) @filesize( $dest . '/' . basename( $img['file'] ) ), // phpcs:ignore
		'sizes'    => $sizes,
		'image_meta' => array( 'title' => $img['title'] ?? '', 'caption' => $img['caption'] ?? '' ),
	) );
	update_post_meta( $att_id, '_sp_img_hash', $hash );
	sp_blog_update_image_text( $att_id, $img );
	return $att_id;
}

function sp_blog_update_image_text( $att_id, $img ) {
	update_post_meta( $att_id, '_wp_attachment_image_alt', wp_strip_all_tags( $img['alt'] ?? '' ) );
	wp_update_post( array(
		'ID'           => $att_id,
		'post_title'   => $img['title'] ?? '',
		'post_excerpt' => $img['caption'] ?? '',
	) );
}

/* ---------- گزارش آخرین ورود در پیشخوان ← سودپز ← مجله ---------- */

add_action( 'admin_menu', function () {
	add_submenu_page( 'soodpaz', 'مجله', 'مجله (ورود خودکار)', 'manage_options', 'soodpaz-blog', function () {
		$l = get_option( 'sp_blog_import_log' );
		echo '<div class="wrap"><h1>ورود خودکار مطالب مجله</h1>';
		if ( ! $l ) {
			echo '<p>هنوز چیزی وارد نشده.</p></div>';
			return;
		}
		echo '<p>آخرین اجرا: ' . esc_html( wp_date( 'Y-m-d H:i', $l['time'] ) ) . '</p><ul>';
		foreach ( (array) $l['log'] as $line ) {
			echo '<li>' . esc_html( $line ) . '</li>';
		}
		echo '</ul><p>مطلبی که در پیشخوان ویرایش کنی دیگر از روی فایل بازنویسی نمی‌شود.</p></div>';
	} );
}, 20 );
