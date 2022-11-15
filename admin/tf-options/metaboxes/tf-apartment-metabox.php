<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

TF_Metabox::metabox( 'tf_apartment_opt', array(
	'title'     => __( 'Apertment Options', 'tourfic' ),
	'post_type' => 'tf_apartment',
	'sections'  => array(
		// General
		'general'     => array(
			'title'  => __( 'General', 'tourfic' ),
			'icon'   => 'fa fa-cog',
			'fields' => array(
				array(
					'id'       => 'apartment_as_featured',
					'type'     => 'switch',
					'label'    => __( 'Set this apartment as featured', 'tourfic' ),
					'subtitle' => __( 'Apartment will be shown under featured sections', 'tourfic' ),
				),
				array(
					'id'    => 'apartment_gallery',
					'type'  => 'gallery',
					'label' => __( 'Apartment Gallery', 'tourfic' ),
				),

				array(
					'id'        => 'disable-apartment-review',
					'type'      => 'switch',
					'label'     => __( 'Disable Review Section', 'tourfic' ),
					'label_on'  => __( 'Yes', 'tourfic' ),
					'label_off' => __( 'No', 'tourfic' ),
					'default'   => false,
					'field_width' => 50,
				),

				array(
					'id'        => 'disable-apartment-share',
					'type'      => 'switch',
					'label'     => __( 'Disable Share Option', 'tourfic' ),
					'label_on'  => __( 'Yes', 'tourfic' ),
					'label_off' => __( 'No', 'tourfic' ),
					'default'   => false,
					'field_width' => 50,
				),

				array(
					'id'     => 'notice',
					'type'   => 'notice',
					'notice' => 'info',
					'content'  => __( 'These settings will overwrite global settings', 'tourfic' ),
				),
			)
		),
		'location'         => array(
			'title'  => __( 'Location', 'tourfic' ),
			'icon'   => 'fa-solid fa-location-dot',
			'fields' => array(
				array(
					'id'          => 'address',
					'type'        => 'text',
					'label'       => __( 'Apartment Address', 'tourfic' ),
					'subtitle'    => __( 'Enter apartment adress', 'tourfic' ),
					'placeholder' => __( 'Address', 'tourfic' ),
					'attributes'  => array(
						'required' => 'required',
					),
				),
				array(
					'id'       => 'map',
					'type'     => 'map',
					'label'    => __( 'Location on Map', 'tourfic' ),
					'subtitle' => __( 'Select one location on the map to see latitude and longitude', 'tourfic' ),
					'height'   => '250px',
					'settings' => array(
						'scrollWheelZoom' => true,
					),
				),
				array(
					'id'      => 'loaction_heading',
					'type'    => 'heading',
					'content' => __( 'Location Details', 'tourfic' ),
				),
				array(
					'id'       => 'location_sec_title',
					'type'     => 'text',
					'label'    => __( 'Section Title', 'tourfic' ),
					'subtitle' => __( 'Enter location section title', 'tourfic' ),
				),
				array(
					'id'       => 'location_title',
					'type'     => 'text',
					'label'    => __( 'Location Title', 'tourfic' ),
					'subtitle' => __( 'Enter location title', 'tourfic' ),
				),
				array(
					'id'       => 'location_description',
					'type'     => 'textarea',
					'label'    => __( 'Location Description', 'tourfic' ),
					'subtitle' => __( 'Enter location description', 'tourfic' ),
				),
			),
		),
		// Information
		'information' => array(
			'title'  => __( 'Informations', 'tourfic' ),
			'icon'   => 'fa-solid fa-circle-info',
			'fields' => array(
				array(
					'id'      => 'Amenities_heading',
					'type'    => 'heading',
					'content' => __( 'Amenities', 'tourfic' ),
				),
				array(
					'id'       => 'amenities_title',
					'type'     => 'text',
					'label'    => __( 'Amenities Title', 'tourfic' ),
					'subtitle' => __( 'Enter amenities title', 'tourfic' ),
				),
				array(
					'id'           => 'amenities',
					'type'         => 'repeater',
					'button_title' => __( 'Add New', 'tourfic' ),
					'label'        => __( 'Amenities', 'tourfic' ),
					'fields'       => array(
						array(
							'id'    => 'title',
							'type'  => 'text',
							'label' => __( 'Title', 'tourfic' ),
						),
						array(
							'id'    => 'icon',
							'type'  => 'icon',
							'label' => __( 'Icon', 'tourfic' ),
						),
					),
				),

				array(
					'id'      => 'facilities_heading',
					'type'    => 'heading',
					'content' => __( 'Facilities', 'tourfic' ),
				),
				array(
					'id'       => 'facilities_title',
					'type'     => 'text',
					'label'    => __( 'Facilities Title', 'tourfic' ),
					'subtitle' => __( 'Enter facilities title', 'tourfic' ),
				),
				array(
					'id'           => 'facilities',
					'type'         => 'repeater',
					'button_title' => __( 'Add New', 'tourfic' ),
					'label'        => __( 'Facilities', 'tourfic' ),
					'fields'       => array(
						array(
							'id'    => 'title',
							'type'  => 'text',
							'label' => __( 'Title', 'tourfic' ),
						),
						array(
							'id'    => 'subtitle',
							'type'  => 'text',
							'label' => __( 'Sub Title', 'tourfic' ),
						),
						array(
							'id'    => 'thumbnail',
							'type'  => 'image',
							'label' => __( 'Thumbnail', 'tourfic' ),
						),
					),
				),
			),
		)
	),
) );
