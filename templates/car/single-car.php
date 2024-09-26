<?php
/**
 * Template: Single Car (Full Width)
 */

use \Tourfic\Classes\Helper;
use \Tourfic\App\Wishlist;

get_header();

if ( !Helper::tf_is_woo_active() ) {
	?>
	<div class="tf-container">
		<div class="tf-notice tf-notice-danger">
			<?php esc_html_e( 'Please install and activate WooCommerce plugin to view car details.', 'tourfic' ); ?>
		</div>
	</div>
	<?php
	get_footer();
	return;
}

/**
 * Query start
 */
while ( have_posts() ) : the_post();

	// get post id
	$post_id = $post->ID;

	/**
	 * Review query
	 */
	$args           = array(
		'post_id' => $post_id,
		'status'  => 'approve',
		'type'    => 'comment',
	);
	$comments_query = new WP_Comment_Query( $args );
	$comments       = $comments_query->comments;

	/**
	 * Get car meta values
	 */
	$meta = get_post_meta( $post_id, 'tf_carrental_opt', true );

	$disable_share_opt    = ! empty( $meta['c-share'] ) ? $meta['c-share'] : '';
	$disable_wishlist_sec = ! empty( $meta['c-wishlist'] ) ? $meta['c-wishlist'] : 0;

	/**
	 * Get global settings value
	 */
	$s_share  = ! empty( Helper::tfopt( 'disable-car-share' ) ) ? Helper::tfopt( 'disable-car-share' ) : 0;

	/**
	 * Disable Share Option
	 */
	$disable_share_opt = ! empty( $disable_share_opt ) ? $disable_share_opt : $s_share;


	/**
	 * Assign all values to variables
	 *
	 */

	// Wishlist
	$post_type       = str_replace( 'tf_', '', get_post_type() );
	$has_in_wishlist = Wishlist::tf_has_item_in_wishlist( $post_id );

	/**
	 * Get locations
	 *
	 * hotel_location
	 */
	$locations = ! empty( get_the_terms( $post_id, 'hotel_location' ) ) ? get_the_terms( $post_id, 'hotel_location' ) : '';
	if ( $locations ) {
		$first_location_id   = $locations[0]->term_id;
		$first_location_term = get_term( $first_location_id );
		$first_location_name = $locations[0]->name;
		$first_location_slug = $locations[0]->slug;
		$first_location_url  = get_term_link( $first_location_term );
	}

	/**
	 * Get features
	 * hotel_feature
	 */
	$features = ! empty( get_the_terms( $post_id, 'hotel_feature' ) ) ? get_the_terms( $post_id, 'hotel_feature' ) : '';

	// Location

	if( !empty($meta['map']) && Helper::tf_data_types($meta['map'])){
		$address = !empty( Helper::tf_data_types($meta['map'])['address'] ) ? Helper::tf_data_types($meta['map'])['address'] : '';

		$address_latitude = !empty( Helper::tf_data_types($meta['map'])['latitude'] ) ? Helper::tf_data_types($meta['map'])['latitude'] : '';
		$address_longitude = !empty( Helper::tf_data_types($meta['map'])['longitude'] ) ? Helper::tf_data_types($meta['map'])['longitude'] : '';
		$address_zoom = !empty( Helper::tf_data_types($meta['map'])['zoom'] ) ? Helper::tf_data_types($meta['map'])['zoom'] : '';

    }

	// Car Detail
	$gallery = ! empty( $meta['car_gallery'] ) ? $meta['car_gallery'] : '';
	if ( $gallery ) {
		$gallery_ids = explode( ',', $gallery ); // Comma seperated list to array
	}

	// Car Info 
	$passengers = ! empty( $meta['passengers'] ) ? $meta['passengers'] : '';
	$baggage = ! empty( $meta['baggage'] ) ? $meta['baggage'] : '';
	$car_custom_info = ! empty( $meta['car_custom_info'] ) ? $meta['car_custom_info'] : '';

	// Benefits 
	$benefits_status = ! empty( $meta['benefits_section'] ) ? $meta['benefits_section'] : '';
	$benefits = ! empty( $meta['benefits'] ) ? $meta['benefits'] : '';

	// Include Exclude 
	$inc_exc_status = ! empty( $meta['inc_exc_section'] ) ? $meta['inc_exc_section'] : '';
	$includes = ! empty( $meta['inc'] ) ? $meta['inc'] : '';
	$include_icon = ! empty( $meta['inc_icon'] ) ? $meta['inc_icon'] : '';
	$excludes = ! empty( $meta['exc'] ) ? $meta['exc'] : '';
	$exclude_icon = ! empty( $meta['exc_icon'] ) ? $meta['exc_icon'] : '';

	// Driver Info 
	$car_driverinfo_status = ! empty( $meta['car_driverinfo_section'] ) ? $meta['car_driverinfo_section'] : '';
	$driver_name = ! empty( $meta['driver_name'] ) ? $meta['driver_name'] : '';
	$driver_email = ! empty( $meta['driver_email'] ) ? $meta['driver_email'] : '';
	$driver_phone = ! empty( $meta['driver_phone'] ) ? $meta['driver_phone'] : '';
	$driver_age = ! empty( $meta['driver_age'] ) ? $meta['driver_age'] : '';
	$driver_address = ! empty( $meta['driver_address'] ) ? $meta['driver_address'] : '';
	$driver_image = ! empty( $meta['driver_image'] ) ? $meta['driver_image'] : '';

	// Booking
	$car_booking_by = ! empty( $meta['booking-by'] ) ? $meta['booking-by'] : '1';
	
	// Protection
	$car_protection_section_status = ! empty( $meta['protection_section'] ) ? $meta['protection_section'] : '';
	$car_protection_content = ! empty( $meta['protection_content'] ) ? $meta['protection_content'] : '';
	$car_protections = ! empty( $meta['protections'] ) ? $meta['protections'] : '';

	//instructions
	$car_instructions_section_status = ! empty( $meta['instructions_section'] ) ? $meta['instructions_section'] : '';
	$car_instructions_content = ! empty( $meta['instructions_content'] ) ? $meta['instructions_content'] : '';

	// Information
	$car_information_section_status = ! empty( $meta['information_section'] ) ? $meta['information_section'] : '';
	$car_owner_name = ! empty( $meta['owner_name'] ) ? $meta['owner_name'] : '';
	$car_owner_email = ! empty( $meta['email'] ) ? $meta['email'] : '';
	$car_owner_phone = ! empty( $meta['phone'] ) ? $meta['phone'] : '';
	$car_owner_website = ! empty( $meta['website'] ) ? $meta['website'] : '';
	$car_owner_fax = ! empty( $meta['fax'] ) ? $meta['fax'] : '';
	$car_owner_owner_image = ! empty( $meta['owner_image'] ) ? $meta['owner_image'] : '';

	// Car Extras
	$car_extras = ! empty( $meta['extras'] ) ? $meta['extras'] : '';

	// FAQ
	$faqs = ! empty( $meta['faq'] ) ? $meta['faq'] : '';

	// Terms & condition
	$tc_title = ! empty( $meta['car-tc-section-title'] ) ? $meta['car-tc-section-title'] : '';
	$tc = ! empty( $meta['terms_conditions'] ) ? $meta['terms_conditions'] : '';

	$share_text = get_the_title();
	$share_link = get_permalink( $post_id );
	// Map Type
	$tf_openstreet_map = ! empty( Helper::tfopt( 'google-page-option' ) ) ? Helper::tfopt( 'google-page-option' ) : "default";

	// Single Template Style
	$tf_car_layout_conditions = ! empty( $meta['tf_single_car_layout_opt'] ) ? $meta['tf_single_car_layout_opt'] : 'global';
	if("single"==$tf_car_layout_conditions){
		$tf_car_single_template = ! empty( $meta['tf_single_car_template'] ) ? $meta['tf_single_car_template'] : 'design-1';
	}
	$tf_car_global_template = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['single-car'] ) ? Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['single-car'] : 'design-1';

	$tf_car_selected_check = !empty($tf_car_single_template) ? $tf_car_single_template : $tf_car_global_template;

	$tf_car_selected_template = $tf_car_selected_check;

    if( $tf_car_selected_template == "design-1" ){
		include TF_TEMPLATE_PART_PATH . 'car/design-1.php';
	}else{
		include TF_TEMPLATE_PART_PATH . 'car/design-1.php';
	}
endwhile;
get_footer();
