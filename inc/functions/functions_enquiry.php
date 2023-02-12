<?php
defined( 'ABSPATH' ) || exit;

/**
 * Add different enquiry submenu under each post types
 *
 * tf_tours
 * tf_hotel
 */
function tf_add_enquiry_submenu() {
	$current_user = wp_get_current_user();
	// get user role
	$current_user_role = ! empty( $current_user->roles[0] ) ? $current_user->roles[0] : '';
	if ( ! empty( $current_user_role ) && ( $current_user_role == 'administrator' || $current_user_role == 'tf_vendor' ) ) {

		if($current_user_role == 'administrator'){
			// Tour enquiry
			add_submenu_page( 'edit.php?post_type=tf_tours', __( 'Tour Enquiry Details', 'tourfic' ), __( 'Enquiry Details', 'tourfic' ), 'edit_tf_tourss', 'tf_tours_enquiry', 'tf_tour_enquiry_page_callback' );

			// Hotel enquiry
			add_submenu_page( 'edit.php?post_type=tf_hotel', __( 'Hotel Enquiry Details', 'tourfic' ), __( 'Enquiry Details', 'tourfic' ), 'edit_tf_hotels', 'tf_hotel_enquiry', 'tf_hotel_enquiry_page_callback' );
		}
		if($current_user_role == 'tf_vendor'){
			if ( ! empty( tf_data_types(tfopt( 'multi-vendor-setings' ))['vendor-enquiry-history'] ) && tf_data_types(tfopt( 'multi-vendor-setings' ))['vendor-enquiry-history'] == '1' ) {
				// Tour enquiry
				add_submenu_page( 'edit.php?post_type=tf_tours', __( 'Tour Enquiry Details', 'tourfic' ), __( 'Enquiry Details', 'tourfic' ), 'edit_tf_tourss', 'tf_tours_enquiry', 'tf_tour_enquiry_page_callback' );

				// Hotel enquiry
				add_submenu_page( 'edit.php?post_type=tf_hotel', __( 'Hotel Enquiry Details', 'tourfic' ), __( 'Enquiry Details', 'tourfic' ), 'edit_tf_hotels', 'tf_hotel_enquiry', 'tf_hotel_enquiry_page_callback' );
			}
		}
	}
}

add_action( 'admin_menu', 'tf_add_enquiry_submenu' );

/**
 * Tours enquiry page callback function
 *
 * Display all the order details
 */
function tf_tour_enquiry_page_callback() {
	?>
    <div class="wrap" style="margin-right: 20px;">
        <h1 class="wp-heading-inline"><?php _e( 'Tour Enquiry Details', 'tourfic' ); ?></h1>
		<?php
		if ( file_exists( TF_INC_PATH . 'functions/class.tf_enquiry.php' ) ) {
			require_once TF_INC_PATH . 'functions/class.tf_enquiry.php';
		} else {
			tf_file_missing( TF_INC_PATH . 'functions/class.tf_enquiry.php' );
		}
		$current_user = wp_get_current_user();
		// get user id
		$current_user_id = $current_user->ID;
		// get user role
		$current_user_role = $current_user->roles[0];
		global $wpdb;
		$table_name = $wpdb->prefix . 'tf_enquiry_data';

		if ( $current_user_role == 'administrator' && function_exists( 'is_tf_pro' ) && is_tf_pro() ) {
			$tour_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s ORDER BY id DESC", 'tf_tours' ), ARRAY_A );
		} elseif ( $current_user_role == 'administrator' ) {
			$tour_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s ORDER BY id DESC LIMIT 15", 'tf_tours' ), ARRAY_A );
		}
		if ( $current_user_role == 'tf_vendor' && function_exists( 'is_tf_pro' ) && is_tf_pro() ) {
			$tour_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s AND author_id = %d ORDER BY id DESC", 'tf_tours', $current_user_id ), ARRAY_A );
		} elseif ( $current_user_role == 'tf_vendor' ) {
			$tour_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s AND author_id = %d ORDER BY id DESC LIMIT 15", 'tf_tours', $current_user_id ), ARRAY_A );
		}
		$tour_enquiry_results = new DBTFTable( $tour_enquiry_result );
		$tour_enquiry_results->prepare_items();
		$tour_enquiry_results->display();
		?>
    </div>
	<?php
}

/**
 * hotel enquiry page callback function
 *
 * Display all the order details
 */
function tf_hotel_enquiry_page_callback() {
	?>
    <div class="wrap" style="margin-right: 20px;">
        <h1 class="wp-heading-inline"><?php _e( 'Hotel Enquiry Details', 'tourfic' ); ?></h1>
		<?php
		if ( file_exists( TF_INC_PATH . 'functions/class.tf_enquiry.php' ) ) {
			require_once TF_INC_PATH . 'functions/class.tf_enquiry.php';
		} else {
			tf_file_missing( TF_INC_PATH . 'functions/class.tf_enquiry.php' );
		}
		$current_user = wp_get_current_user();
		// get user id
		$current_user_id = $current_user->ID;
		// get user role
		$current_user_role = $current_user->roles[0];
		global $wpdb;
		$table_name = $wpdb->prefix . 'tf_enquiry_data';

		if ( $current_user_role == 'administrator' && function_exists( 'is_tf_pro' ) && is_tf_pro() ) {
			$hotel_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s ORDER BY id DESC", 'tf_hotel' ), ARRAY_A );
		} elseif ( $current_user_role == 'administrator' ) {
			$hotel_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s ORDER BY id DESC LIMIT 15", 'tf_hotel' ), ARRAY_A );
		}

		if ( $current_user_role == 'tf_vendor' && function_exists( 'is_tf_pro' ) && is_tf_pro() ) {
			$hotel_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s AND author_id = %d ORDER BY id DESC", 'tf_hotel', $current_user_id ), ARRAY_A );
		} elseif ( $current_user_role == 'tf_vendor' ) {
			$hotel_enquiry_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = %s AND author_id = %d ORDER BY id DESC LIMIT 15", 'tf_hotel', $current_user_id ), ARRAY_A );
		}
		$hotel_enquiry_results = new DBTFTable( $hotel_enquiry_result );
		$hotel_enquiry_results->prepare_items();
		$hotel_enquiry_results->display();
		?>
    </div>
	<?php
}

/**
 * Enquiry Data
 *
 * @author jahid
 */
add_action( 'admin_init', 'tf_create_enquiry_database_table' );

//Create Enquiry Database
function tf_create_enquiry_database_table() {
	global $wpdb;
	$table_name      = $wpdb->prefix . 'tf_enquiry_data';
	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        post_type varchar(255),
        uname varchar(255),
        uemail varchar(255),  
        udescription text,
        author_id bigint(20) NOT NULL,
        author_roles varchar(255),
        created_at datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
	dbDelta( $sql );

}