<?php
/**
 * فرم «عضو بنیان‌گذار»: هر درخواست در پیشخوان (سودپز ← درخواست‌ها) ذخیره می‌شود
 * و یک ایمیل هم به ایمیل مدیر سایت فرستاده می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'sp_lead', array(
		'labels'          => array(
			'name'          => 'درخواست‌های بنیان‌گذار',
			'singular_name' => 'درخواست',
			'menu_name'     => 'درخواست‌ها',
			'edit_item'     => 'درخواست',
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'soodpaz',
		'supports'        => array( 'title' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
} );

function sp_lead_fields() {
	return array(
		'mobile'    => 'موبایل',
		'kitchen'   => 'آشپزخونه',
		'city'      => 'شهر',
		'instagram' => 'اینستاگرام',
		'note'      => 'توضیح',
	);
}

/* ستون‌های فهرست درخواست‌ها */
add_filter( 'manage_sp_lead_posts_columns', function () {
	return array( 'cb' => '<input type="checkbox">', 'title' => 'نام', 'mobile' => 'موبایل', 'kitchen' => 'آشپزخونه', 'city' => 'شهر', 'instagram' => 'اینستاگرام', 'date' => 'تاریخ' );
} );
add_action( 'manage_sp_lead_posts_custom_column', function ( $col, $post_id ) {
	echo esc_html( get_post_meta( $post_id, '_sp_' . $col, true ) );
}, 10, 2 );

/* نمایش جزئیات در صفحه‌ی ویرایش */
add_action( 'add_meta_boxes_sp_lead', function () {
	add_meta_box( 'sp_lead_info', 'اطلاعات', function ( $post ) {
		echo '<table class="form-table">';
		foreach ( sp_lead_fields() as $key => $label ) {
			echo '<tr><th>' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( get_post_meta( $post->ID, '_sp_' . $key, true ) ) ) . '</td></tr>';
		}
		echo '</table>';
	} );
} );

/* دریافت فرم */
add_action( 'admin_post_nopriv_sp_founder', 'sp_handle_founder_form' );
add_action( 'admin_post_sp_founder', 'sp_handle_founder_form' );

function sp_handle_founder_form() {
	$back = sp_url( 'founders' );

	if ( ! isset( $_POST['sp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sp_nonce'] ) ), 'sp_founder' ) ) {
		wp_safe_redirect( add_query_arg( 'sp', 'expired', $back ) . '#join' );
		exit;
	}
	// تله‌ی ربات: این فیلد برای آدم دیده نمی‌شود.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'sp', 'sent', $back ) . '#join' );
		exit;
	}

	$name      = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$mobile    = isset( $_POST['mobile'] ) ? preg_replace( '/\D/', '', sp_en_digits( wp_unslash( $_POST['mobile'] ) ) ) : '';
	$city      = isset( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
	$instagram = isset( $_POST['instagram'] ) ? sanitize_text_field( wp_unslash( $_POST['instagram'] ) ) : '';
	$kitchens  = array( 'sweet' => 'شیرینی', 'food' => 'غذا', 'both' => 'هر دو' );
	$kitchen   = isset( $_POST['kitchen'] ) ? sanitize_key( wp_unslash( $_POST['kitchen'] ) ) : '';
	$kitchen   = isset( $kitchens[ $kitchen ] ) ? $kitchens[ $kitchen ] : '';
	$note      = isset( $_POST['note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['note'] ) ) : '';

	if ( preg_match( '/^98(9\d{9})$/', $mobile, $m ) ) {
		$mobile = '0' . $m[1];
	}
	if ( '' === $name || ! preg_match( '/^09\d{9}$/', $mobile ) ) {
		wp_safe_redirect( add_query_arg( 'sp', 'invalid', $back ) . '#join' );
		exit;
	}

	$id = wp_insert_post( array(
		'post_type'   => 'sp_lead',
		'post_status' => 'private',
		'post_title'  => $name,
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		foreach ( compact( 'mobile', 'kitchen', 'city', 'instagram', 'note' ) as $key => $val ) {
			update_post_meta( $id, '_sp_' . $key, $val );
		}
		wp_mail(
			get_option( 'admin_email' ),
			'درخواست جدید بنیان‌گذار سودپز: ' . $name,
			"نام: $name\nموبایل: $mobile\nآشپزخونه: $kitchen\nشهر: $city\nاینستاگرام: $instagram\nتوضیح: $note\n\nهمه‌ی درخواست‌ها: " . admin_url( 'edit.php?post_type=sp_lead' )
		);
	}

	wp_safe_redirect( add_query_arg( 'sp', 'sent', $back ) . '#join' );
	exit;
}
