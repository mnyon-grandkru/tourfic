<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

/**
 * Dequeue frontend scripts to avoid conflict
 * 
 * @since 1.0
 */
if ( !function_exists('tf_dequeue_scripts') ) {
    function tf_dequeue_scripts(){

        wp_deregister_script( 'flatpickr' );
		wp_dequeue_script( 'flatpickr' );
        wp_deregister_style('flatpickr');
        wp_dequeue_style( 'flatpickr' );
		wp_deregister_script( 'fancyBox' );
		wp_dequeue_script( 'fancyBox' );
		
    }
    add_filter( 'wp_enqueue_scripts', 'tf_dequeue_scripts', 9999 );
}

/**
 * Enqueue Frontend scripts
 * 
 * @since 1.0
 */
if ( !function_exists('tf_enqueue_scripts') ) {
	function tf_enqueue_scripts(){

        $flatpickr_cdn = !empty(tfopt( 'flatpickr-cdn' )) ? tfopt( 'flatpickr-cdn' ) : false ;
        $flatpickr_locale = !empty(tfopt( 'flatpickr-cdn' )) ? tfopt( 'flatpickr-cdn' ) : false ;
        $fancy_cdn = !empty(tfopt( 'fancy-cdn' )) ? tfopt( 'fancy-cdn' ) : false ;
        $fa_cdn = !empty(tfopt( 'fa-cdn' )) ? tfopt( 'fa-cdn' ) : false ;
        $min_css = !empty(tfopt( 'css-min' )) ? '.min' : '';
		$min_js = !empty(tfopt( 'js-min' )) ? '.min' : '';





        wp_enqueue_style( 'magnific-popup-css', TF_ASSETS_URL . 'css/magnific-popup.css', null, '' );

        wp_enqueue_style( 'tourfic-styles', TF_ASSETS_URL . 'css/tourfic-styles.min.css', null, '' );

        wp_enqueue_style( 'tourfic-autocomplete', TF_ASSETS_URL . 'css/tourfic-autocomplete.css', null, '' );
        wp_enqueue_style( 'my-style', TF_ASSETS_URL . 'css/my-style.css', null, '' );
        wp_enqueue_style( 'owl-carousel', TF_ASSETS_URL . 'css/owl.carousel.min.css', null, '' );
        wp_enqueue_style( 'tf-style', TF_ASSETS_URL . 'css/style.css', null, '' );
        wp_enqueue_style( 'tf-responsive', TF_ASSETS_URL . 'css/responsive.css', null, '' );

        wp_enqueue_script( 'slick', TF_ASSETS_URL . 'slick/slick.min.js', array( 'jquery' ), '' );

        wp_enqueue_script( 'magnific-popup-js', TF_ASSETS_URL . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), '' );
        wp_enqueue_script( 'owl-carousel', TF_ASSETS_URL . 'js/owl.carousel.min.js', array( 'jquery' ), '' );

        wp_enqueue_style( 'daterangepicker', TF_ASSETS_URL . 'daterangepicker/daterangepicker.css', null, TOURFIC );

        wp_enqueue_script( 'moment', TF_ASSETS_URL . 'daterangepicker/moment.min.js', array( 'jquery' ), TOURFIC, true );
        wp_enqueue_script( 'daterangepicker', TF_ASSETS_URL . 'daterangepicker/daterangepicker.js', array( 'jquery' ), TOURFIC, true );

        wp_enqueue_script( 'tourfic-script', TF_ASSETS_URL . 'js/tourfic-script.js', array( 'jquery' ), '', true );

        wp_localize_script( 'tourfic-script', 'tf_params',
            array(
                'nonce'        => wp_create_nonce( 'tf_ajax_nonce' ),
                'ajax_url'     => admin_url( 'admin-ajax.php' ),
                'destinations' => get_hotel_destinations(),
                'tour_destinations' => get_tour_destinations(),
            )
        );




        

        /**
         * Flatpickr
         * 
         * v4.6.9
         */
        if ($flatpickr_cdn == true) {
			wp_enqueue_style( 'flatpickr', '//cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css', array(), '4.6.9' );
			wp_enqueue_script( 'flatpickr', '//cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js', array( 'jquery' ), '4.6.9', true );
		} else {
            wp_enqueue_style( 'flatpickr', TF_ASSETS_URL . 'flatpickr/flatpickr.min.css', '', '4.6.9' );
			wp_enqueue_script( 'flatpickr', TF_ASSETS_URL . 'flatpickr/flatpickr.min.js', array( 'jquery' ), '4.6.9', true );
            if ($flatpickr_locale) {
                wp_enqueue_script( 'flatpickr-locale', TF_ASSETS_URL . 'flatpickr/l10n/' .$flatpickr_locale. '.min.js', array( 'jquery' ), '4.6.9', true );
            }
		}


        /**
         * Fancybox
         * 
         * v3.5.7
         */
        if ($fancy_cdn == true) {
			wp_enqueue_style( 'fancyBox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', array(), '3.5.7' );
			wp_enqueue_script( 'fancyBox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		} else {
            wp_enqueue_style( 'fancybox', TF_ASSETS_URL . 'css/jquery.fancybox.min.css', '', '3.5.7' );
			wp_enqueue_script( 'fancybox', TF_ASSETS_URL . 'js/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		}       

        /**
         * Font Awesome Free
         * 
         * v5.15.4
         */ 
        if ($fa_cdn == true) {
            wp_enqueue_style( 'font-awesome-5', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', '', '5.15.4' );
        } else {
            wp_enqueue_style( 'font-awesome-5', TF_ASSETS_URL . 'font-awesome/css/all.min.css', '', '5.15.4' );
        }

        /**
         * Custom
         */       
        wp_enqueue_style( 'tourfic', TF_ASSETS_URL . 'css/tourfic' . $min_css . '.css', '', TOURFIC );
        wp_enqueue_script( 'tourfic', TF_ASSETS_URL . 'js/tourfic' . $min_js . '.js', '', TOURFIC, true );
        wp_localize_script( 'tourfic', 'tf_params',
            array(
                'nonce'        => wp_create_nonce( 'tf_ajax_nonce' ),
                'ajax_url'     => admin_url( 'admin-ajax.php' ),
                'destinations' => get_hotel_destinations(),
                'tour_destinations' => get_tour_destinations(),
            )
        );

        /**
         * Inline scripts
         */
        // Get single tour meta data
        global $post;
        if(!is_404()) {
            $meta = !empty(get_post_meta( $post->ID, 'tf_tours_option', true )) ? get_post_meta( $post->ID, 'tf_tours_option', true ) : '';
        }
        $tour_type = !empty($meta['type']) ? $meta['type'] : '';

        # Inline scripts
        $inline_scripts = '';
        // JS Start
        $inline_scripts .= '(function ($) { $(document).ready(function () {';

        if ($tour_type == 'fixed') {
            // Disable date selection in calendar
            $inline_scripts .= '$(".flatpickr-day").css("pointer-events", "none"); ';
        }

        // JS end
        $inline_scripts .= '}); })(jQuery);';

        wp_add_inline_script( 'tourfic', $inline_scripts );

    }
    add_action( 'wp_enqueue_scripts', 'tf_enqueue_scripts', 99999 );
}

/**
 * Enqueue Admin scripts
 * 
 * @since 1.0
 */
if ( !function_exists('tf_enqueue_admin_scripts') ) {
    function tf_enqueue_admin_scripts($hook){ 

        if ( $hook == "widgets.php" && function_exists( 'is_woocommerce' ) ) {

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ?: '.min';

            $assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
            wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
            wp_register_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css' );

            wp_enqueue_script( 'select2' );
            wp_enqueue_style( 'select2' );

            $output = "
            (function($) {
                'use strict';
                jQuery(document).ready(function() { ";

            $output .= "$(document).on('tf_select2 widget-added widget-updated', function() {

                        jQuery('.tf-select2').each(function(){
                            if( !$(this).hasClass('select2-hidden-accessible') ){
                                $(this).select2({ width: '100%' });
                            }
                        });

                    });";

            $output .= "
                });
            })(jQuery);";

            wp_add_inline_script( 'select2', $output );

        }
            
    }
    add_action( 'admin_enqueue_scripts', 'tf_enqueue_admin_scripts' );
}
?>