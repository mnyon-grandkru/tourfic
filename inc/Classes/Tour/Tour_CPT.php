<?php

namespace Tourfic\Classes\Tour;
defined( 'ABSPATH' ) || exit;

class Tour_CPT extends \Tourfic\Classes\Post_Type {

	use \Tourfic\Traits\Singleton;
	use \Tourfic\Traits\Helper;

	/**
	 * Initialize custom post type
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$tour_args = array(
			'name'          => esc_html__('Tours', 'tourfic' ),
			'singular_name' => esc_html__('Tour', 'tourfic' ),
			'slug'          => 'tf_tours',
			'menu_icon'     => 'dashicons-location-alt',
			'menu_position' => 26.3,
			'supports'      => apply_filters( 'tf_tours_supports', array( 'title', 'editor', 'thumbnail', 'comments', 'author' ) ),
			'capability'    => array( 'tf_tours', 'tf_tourss' ),
			'rewrite_slug'  => $this->get_tour_slug(),
		);

		$tax_args = array(
			array(
				'name'          => esc_html__('Destinations', 'tourfic' ),
				'singular_name' => esc_html__('Destination', 'tourfic' ),
				'taxonomy'      => 'tour_destination',
				'rewrite_slug'  => apply_filters( 'tf_tour_destination_slug', 'tour-destination' ),
				'capability'    => array(
					'assign_terms' => 'edit_tf_tours',
					'edit_terms'   => 'edit_tf_tours',
				),
			),
			array(
				'name'          => esc_html__('Attractions', 'tourfic' ),
				'singular_name' => esc_html__('Attraction', 'tourfic' ),
				'taxonomy'      => 'tour_attraction',
				'rewrite_slug'  => apply_filters( 'tf_tour_attraction_slug', 'tour-attraction' ),
				'capability'    => array(
					'assign_terms' => 'edit_tf_tours',
					'edit_terms'   => 'edit_tf_tours',
				),
			),
			array(
				'name'          => esc_html__('Activities', 'tourfic' ),
				'singular_name' => esc_html__('Activity', 'tourfic' ),
				'taxonomy'      => 'tour_activities',
				'rewrite_slug'  => apply_filters( 'tf_tour_activities_slug', 'tour-activities' ),
				'capability'    => array(
					'assign_terms' => 'edit_tf_tours',
					'edit_terms'   => 'edit_tf_tours',
				),
			),
			array(
				'name'          => esc_html__('Features', 'tourfic' ),
				'singular_name' => esc_html__('Feature', 'tourfic' ),
				'taxonomy'      => 'tour_features',
				'rewrite_slug'  => apply_filters( 'tf_tour_features_slug', 'tour-features' ),
				'capability'    => array(
					'assign_terms' => 'edit_tf_tours',
					'edit_terms'   => 'edit_tf_tours',
				),
			),
			array(
				'name'          => esc_html__('Types', 'tourfic'),
				'singular_name' => esc_html__('Type', 'tourfic'),
				'taxonomy'      => 'tour_type',
				'rewrite_slug'  => apply_filters( 'tf_tour_type_slug', 'tour-type' ),
				'capability'    => array(
					'assign_terms' => 'edit_tf_tours',
					'edit_terms'   => 'edit_tf_tours',
				),
			)
		);

		parent::__construct( $tour_args, $tax_args );

		add_action( 'init', array( $this, 'tf_post_type_taxonomy_register' ) );
	}

	private function get_tour_slug() {
		$tf_tour_setting_permalink_slug = ! empty( self::tfopt( 'tour-permalink-setting' ) ) ? self::tfopt( 'tour-permalink-setting' ) : "tours";

		update_option( "tour_slug", $tf_tour_setting_permalink_slug );

		return apply_filters( 'tf_tours_slug', get_option( "tour_slug" ) );
	}

}
