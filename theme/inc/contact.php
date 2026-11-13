<?php
/**
 * فرم «تماس با ما»: پیام‌ها در پیشخوان (سودپز ← پیام‌ها) ذخیره می‌شوند
 * و یک ایمیل هم به ایمیل مدیر سایت می‌رود.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'sp_msg', array(
		'labels'          => array(
			'name'          => 'پیام‌های تماس',
			'singular_name' => 'پیام',
			'menu_name'     => 'پیام‌ها',
			'edit_item'     => 'پیام',
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'soodpaz',
		'supports'        => array( 'title', 'editor' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
} );

add_filter( 'manage_sp_msg_posts_columns', function () {
	return array( 'cb' => '<input type="checkbox">', 'title' => 'نام', 'mobile' => 'موبایل یا ایمیل', 'topic' => 'موضوع', 'date' => 'تاریخ' );
} );
add_action( 'manage_sp_msg_posts_custom_column', function ( $col, $post_id ) {
	echo esc_html( get_post_meta( $post_id, '_sp_' . $col, true ) );
}, 10, 2 );

function sp_contact_topics() {
	return array(
		'question' => 'سؤال درباره‌ی سودپز',
		'founders' => 'عضویت بنیان‌گذار و پرداخت',
		'feature'  => 'پیشنهاد امکان جدید',
		'teach'    => 'همکاری (آموزشگاه، مربی، فروشنده‌ی مواد)',
		'other'    => 'موضوع دیگه',
	);
}

add_action( 'admin_post_nopriv_sp_contact', 'sp_handle_contact_form' );
add_action( 'admin_post_sp_contact', 'sp_handle_contact_form' );

function sp_handle_contact_form() {
	$back = sp_url( 'contact' );

	if ( ! isset( $_POST['sp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sp_nonce'] ) ), 'sp_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'sp', 'expired', $back ) . '#form' );
		exit;
	}
	if ( ! empty( $_POST['website'] ) ) { // تله‌ی ربات
		wp_safe_redirect( add_query_arg( 'sp', 'sent', $back ) . '#form' );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$reach   = isset( $_POST['reach'] ) ? sanitize_text_field( sp_en_digits( wp_unslash( $_POST['reach'] ) ) ) : '';
	$topic   = isset( $_POST['topic'] ) ? sanitize_key( wp_unslash( $_POST['topic'] ) ) : 'other';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$topics  = sp_contact_topics();
	$topic_l = isset( $topics[ $topic ] ) ? $topics[ $topic ] : $topics['other'];

	if ( '' === $name || '' === $reach || mb_strlen( $message ) < 5 ) {
		wp_safe_redirect( add_query_arg( 'sp', 'invalid', $back ) . '#form' );
		exit;
	}

	$id = wp_insert_post( array(
		'post_type'    => 'sp_msg',
		'post_status'  => 'private',
		'post_title'   => $name,
		'post_content' => $message,
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_sp_mobile', $reach );
		update_post_meta( $id, '_sp_topic', $topic_l );
		wp_mail(
			get_option( 'admin_email' ),
			'پیام جدید از سایت سودپز: ' . $topic_l,
			"نام: $name\nراه ارتباط: $reach\nموضوع: $topic_l\n\n$message\n\nهمه‌ی پیام‌ها: " . admin_url( 'edit.php?post_type=sp_msg' )
		);
	}

	wp_safe_redirect( add_query_arg( 'sp', 'sent', $back ) . '#form' );
	exit;
}
