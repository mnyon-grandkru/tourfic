<?php 

use \Tourfic\Classes\Helper;

if ( ! function_exists( 'tf_taxable_option_callback' ) ) {
	function tf_taxable_option_callback() {
		if ( Helper::tf_is_woo_active() ) {
			$tax_classes = WC_Tax::get_tax_classes();
			$all_classes = array(
				'standard' => 'Standard Rates'
			);
			foreach ( $tax_classes as $tax ) {
				$all_classes [ sanitize_title( $tax ) ] = $tax . ' Rates';
			}
		} else {
			$all_classes = array(
				'standard' => 'Standard Rates'
			);
		}

		return $all_classes;
	}
}
if( Helper::tf_is_woo_active() && ! defined( 'TF_PRO' ) ){
	add_action('woocommerce_before_calculate_totals', 'tf_cart_item_tax_class_for_default_taxs');
}
function tf_cart_item_tax_class_for_default_taxs( $cart ){
    if ((is_admin() && !defined('DOING_AJAX')))
        return;

    if (did_action('woocommerce_before_calculate_totals') >= 2)
        return;

    foreach ($cart->get_cart() as $item) {

        if(!empty($item['tf_hotel_data'])){
            $item['data']->set_tax_class('zero-rate');
        }elseif(!empty($item['tf_tours_data'])){
            $item['data']->set_tax_class('zero-rate');
        }elseif(!empty($item['tf_apartment_data'])){
            $item['data']->set_tax_class('zero-rate');
        }else{

        }

    }
}