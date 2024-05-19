<?php
namespace Tourfic\Classes;

defined( 'ABSPATH' ) || exit;

use Tourfic\Admin\Backend_Booking\TF_Apartment_Backend_Booking;
use Tourfic\Admin\Backend_Booking\TF_Hotel_Backend_Booking;
use Tourfic\Admin\Backend_Booking\TF_Tour_Backend_Booking;

use Tourfic\Admin\Emails\TF_Handle_Emails;

class Base {
	use \Tourfic\Traits\Singleton;
	use \Tourfic\Traits\Enquiry;
	use \Tourfic\Traits\Helper;

	public function __construct() {
		$this->init();
	}

	public function init() {
		if ( file_exists( TF_INC_PATH . 'functions.php' ) ) {
			require_once TF_INC_PATH . 'functions.php';
		} else {
			tf_file_missing( TF_INC_PATH . 'functions.php' );
		}

		\Tourfic\Classes\Helper::instance();
		\Tourfic\Classes\Enqueue::instance();

		if(is_admin()) {
			\Tourfic\Classes\TF_Activator::instance();
			\Tourfic\Classes\TF_Deactivator::instance();
			\Tourfic\Admin\TF_Setup_Wizard::instance();
			\Tourfic\Admin\TF_Options\TF_Options::instance();

			// Backend Bookings
			TF_Apartment_Backend_Booking::instance();
			TF_Hotel_Backend_Booking::instance();
			TF_Tour_Backend_Booking::instance();
		}

		if ( self::tfopt( 'disable-services' ) && in_array( 'hotel', self::tfopt( 'disable-services' ) ) ) {
		} else {
			 \Tourfic\Classes\Hotel\Hotel_CPT::instance();
		}
		if ( self::tfopt( 'disable-services' ) && in_array( 'tour', self::tfopt( 'disable-services' ) ) ) {
		} else {
			 \Tourfic\Classes\Tour\Tour_CPT::instance();
		}
		if ( self::tfopt( 'disable-services' ) && in_array( 'apartment', self::tfopt( 'disable-services' ) ) ) {
		} else {
			 \Tourfic\Classes\Apartment\Apartment_CPT::instance();
		}

		\Tourfic\Admin\Emails\TF_Handle_Emails::instance();


		// \Tourfic\Admin\Booking_Details\Hotel\Hotel_Booking_Details::instance();
//		\Tourfic\Admin\Enquiry\Hotel\Hotel_Enquiry::instance();
//		\Tourfic\Admin\Booking_Details\Tour\Tour_Booking_Details::instance();
//		\Tourfic\Admin\Booking_Details\Apartment\Apartment_Booking_Details::instance();
	}

	function init_hooks() {
//		add_action( 'admin_menu', array( $this, 'tf_add_enquiry_submenu' ) );
	}
}

