<?php
//can't access directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Control core classes for avoid errors
if ( class_exists( 'CSF' ) ) {

    // Set a unique slug-like ID
    $prefix = 'tf_tours_option';

    // Create a metabox
    CSF::createMetabox( $prefix, array(
        'title'     => __( 'Tours Setting', 'tourfic' ),
        'post_type' => 'tf_tours',
        'context'   => 'advanced',
        'priority'  => 'high',
        'theme'     => 'dark',
    ) );

    // Create a section
    CSF::createSection( $prefix, array(
        'title'  => __( 'General', 'tourfic' ),
        'fields' => array(

            // A text field
            array(
                'id'       => 'tour_as_featured',
                'type'     => 'switcher',
                'title'    => __( 'Set this tour as featured', 'tourfic' ),
                'subtitle' => __( 'To show the feature label', 'tourfic' ),
            ),

            array(
                'id'      => 'booking_type',
                'type'    => 'select',
                'title'   => __( 'Booking type', 'tourfic' ),
                'options' => array(
                    'instant'         => __( 'Instant Booking', 'tourfic' ),
                    apply_filters('tf_external_booking','')    => __( 'External Booking (Pro)', 'tourfic' ),
                ),
            ),
            array(
                'id'      => 'tour_single_page',
                'type'    => 'select',
                'title'   => __( 'Tour single page layout', 'tourfic' ),
                'options' => array(
                    'instant' => __( 'Default', 'tourfic' ),
                ),
            ),
            array(
                'id'          => 'tour_feature',
                'type'        => 'select',
                'multiple'    => true,
                'chosen'      => true,
                'options'     => 'categories',
                'query_args'  => [
                    'taxonomy' => 'tf_feature',
                ],
                'placeholder' => __( 'Add features', 'tourfic' ),
                'title'       => __( 'Tour features', 'tourfic' ),
            ),
            array(
                'id'    => 'tour_gallery',
                'type'  => 'gallery',
                'title' => __( 'Tour Gallery', 'tourfic' ),
            ),
            array(
                'id'       => 'tour_video',
                'type'     => 'text',
                'title'    => __( 'Tour video', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
                'attributes'  => [
                    'disabled' => 'disabled',
                ],
            ),

        ),
    ) );

    // Create a section
    CSF::createSection( $prefix, array(
        'title'  => __( 'Location', 'tourfic' ),
        'fields' => array(

            array(
                'id'       => 'text_location',
                'type'     => 'text',
                'title'    => __( 'Tour Location manual', 'tourfic' ),
                'subtitle' => __( 'Input tour location', 'tourfic' ),
            ),

            array(
                'id'       => 'location',
                'type'     => 'map',
                'title'    => __( 'Tour Location Map', 'tourfic' ),
                'subtitle' => __( 'Select tour location <strong class="tf-pro">Pro</strong>', 'tourfic' ),
                'attributes'  => [
                    'disabled' => 'disabled',
                ],
            ),
            array(
                'id'       => 'nearby_properties',
                'type'     => 'text',
                'title'    => __( 'Nearby properties', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
                'attributes'  => [
                    'disabled' => 'disabled',
                ],
            ),

        ),
    ) );

    // Create a section
    CSF::createSection( $prefix, array(
        'title'  => __( 'Hightlights', 'tourfic' ),
        'fields' => array(

            array(
                'id'    => 'additional_information',
                'type'  => 'wp_editor',
                'title' => __( 'Hightlights', 'tourfic' ),
            ),

        ),
    ) );

    // Create a section
    CSF::createSection( $prefix, array(
        'title'  => __( 'Information', 'tourfic' ),
        'fields' => array(

            array(
                'id'       => 'duration',
                'type'     => 'text',
                'title'    => __( 'Tour duration', 'tourfic' ),
                'subtitle' => __( 'Tour duration eg. 3 days', 'tourfic' ),
            ),
            array(
                'id'       => 'group_size',
                'type'     => 'text',
                'title'    => __( 'Group size', 'tourfic' ),
                'subtitle' => __( 'Group size eg. 10 people', 'tourfic' ),
            ),
            array(
                'id'       => 'language',
                'type'     => 'text',
                'title'    => __( 'Languages', 'tourfic' ),
                'subtitle' => __( 'Input languages seperated by comma(,)', 'tourfic' ),
            ),
            array(
                'id'       => 'min_people',
                'type'     => 'number',
                'title'    => __( 'Minimum person', 'tourfic' ),
                'subtitle' => __( 'Minimum person to travel', 'tourfic' ),
            ),
            array(
                'id'       => 'max_people',
                'type'     => 'number',
                'title'    => __( 'Maximum person', 'tourfic' ),
                'subtitle' => __( 'Maximum person to travel', 'tourfic' ),
            ),

        ),
    ) );

    // Create a section
    CSF::createSection( $prefix, array(
        'title'  => __( 'Contact Info', 'tourfic' ),
        'fields' => array(

            array(
                'id'       => apply_filters('tf_contact_email',''),
                'type'     => 'text',
                'title'    => __( 'Email address', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
            ),
            array(
                'id'       => 'phone',
                'type'     => 'text',
                'title'    => __( 'Phone Number', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
            ),
            array(
                'id'       => 'website',
                'type'     => 'text',
                'title'    => __( 'Website Url', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
            ),
            array(
                'id'       => 'fax',
                'type'     => 'text',
                'title'    => __( 'Fax Number', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
            ),

        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Tour Extra', 'tourfic' ),
        'fields' => array(
            array(
                'id'     => '',
                'type'   => 'repeater',
                'title'  => __( 'Extra service', 'tourfic' ),
                'subtitle'   => __( '<strong class="tf-pro">Pro</strong>','tourfic'),
                'fields' => array(
                    array(
                        'id'    => 'title',
                        'type'  => 'text',
                        'title' => __( 'Title', 'tourfic' ),
                        'attributes' => array(
                            'disabled' => 'disabled',
                        ),
                    ),
                    array(
                        'id'    => 'desc',
                        'type'  => 'textarea',
                        'title' => __( 'Short description', 'tourfic' ),
                        'attributes' => array(
                            'disabled' => 'disabled',
                        ),
                    ),
                    array(
                        'id'         => 'price',
                        'type'       => 'text',
                        'title'      => __( 'Price', 'tourfic' ),
                        'attributes' => array(
                            'min' => '0',
                            'disabled' => 'disabled'
                        ),
                    ),
                ),
            ),
        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Price Settings', 'tourfic' ),
        'fields' => array(
            array(
                'id'       => 'pricing',
                'type'     => 'select',
                'title'    => __( 'Pricing rule', 'tourfic' ),
                'subtitle' => __( 'Input pricing rule', 'tourfic' ),
                'class'    => 'pricing',
                'options'  => [
                    'person' => __( 'Person', 'tourfic' ),
                    ''  => __( 'Group (Pro)', 'tourfic' ),
                ],
                'default' => 'person',
            ),
            array(
                'id'         => 'adult_price',
                'type'       => 'number',
                'title'      => __( 'Adult Price', 'tourfic' ),
                'subtitle'   => __( 'Input adult price', 'tourfic' ),
                'dependency' => array( 'pricing', '==', 'person' ),
                'attributes' => array(
                    'min' => '0',
                ),
            ),
            array(
                'id'         => 'child_price',
                'type'       => 'number',
                'dependency' => array( 'pricing', '==', 'person' ),
                'title'      => __( 'Child price', 'tourfic' ),
                'subtitle'   => __( 'Input child price', 'tourfic' ),
                'attributes' => array(
                    'min' => '0',
                ),
            ),
            array(
                'id'         => 'infant_price',
                'type'       => 'number',
                'dependency' => array( 'pricing', '==', 'person' ),
                'title'      => __( 'Infant price', 'tourfic' ),
                'subtitle'   => __( 'Input infant price', 'tourfic' ),
                'attributes' => array(
                    'min' => '0',
                ),
            ),
            array(
                'id'         => 'group_price',
                'type'       => 'number',
                'dependency' => array( 'pricing', '==', 'group' ),
                'title'      => __( 'Group price', 'tourfic' ),
                'subtitle'   => __( 'Input group price', 'tourfic' ),
                'attributes' => array(
                    'min' => '0',
                ),
            ),
            array(
                'id'       => 'discount_type',
                'type'     => 'select',
                'title'    => __( 'Discount type', 'tourfic' ),
                'subtitle' => __( 'Select discount type Percent or Fixed', 'tourfic' ),
                'options'  => array(
                    'none'    => __( 'None', 'tourfic' ),
                    'percent' => __( 'Percent', 'tourfic' ),
                    'fixed'   => __( 'Fixed', 'tourfic' ),
                ),
                'default'  => 'none',
            ),
            array(
                'id'         => 'discount_price',
                'type'       => 'number',
                'title'      => __( 'Discount price', 'tourfic' ),
                'subtitle'   => __( 'Input discount price in number', 'tourfic' ),
                'attributes' => array(
                    'min' => '0',
                ),
                'dependency' => array(
                    array( 'discount_type', '!=', 'none' ),
                ),
            ),

        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Availability', 'tourfic' ),
        'fields' => array(
            array(
                'id'       => 'type',
                'type'     => 'select',
                'title'    => __( 'Tour type', 'tourfic' ),
                'subtitle' => __( 'Select availability', 'tourfic' ),
                'class'    => 'tour-type',
                'options'  => [
                    apply_filters('tf_continuous_availability','') => __( 'Continuous (Pro)', 'tourfic' ),
                    'fixed'      => __( 'Fixed', 'tourfic' ),
                ],
                'default' => 'fixed',
            ),
            //Fixed availability
            array(
                'id'         => 'fixed_availability',
                'type'       => 'fieldset',
                'title'      => __( 'Availability', 'tourfic' ),
                'subtitle'   => __( 'Input your availability', 'tourfic' ),
                'dependency' => array( 'type', '==', 'fixed' ),
                'class'      => 'fixed_availability',
                'fields'     => array(
                    array(
                        'id'       => 'check_in',
                        'type'     => 'date',
                        'title'    => __( 'Check In', 'tourfic' ),
                        'subtitle' => __( 'Select check in date', 'tourfic' ),
                        'class'    => 'check-in',
                        'settings' => array(
                            'dateFormat'      => 'dd/mm/yy'
                        )
                    ),
                    array(
                        'id'       => 'check_out',
                        'type'     => 'date',
                        'title'    => __( 'Check Out', 'tourfic' ),
                        'subtitle' => __( 'Select check out date', 'tourfic' ),
                        'class'    => 'check-out',
                        'settings' => array(
                            'dateFormat'      => 'dd/mm/yy'
                        )
                    ),
                    array(
                        'id'       => 'min_seat',
                        'type'     => 'number',
                        'title'    => __( 'Minimum people', 'tourfic' ),
                        'subtitle' => __( 'Minimum seat number', 'tourfic' ),
                    ),
                    array(
                        'id'       => 'max_seat',
                        'type'     => 'number',
                        'title'    => __( 'Maximum people', 'tourfic' ),
                        'subtitle' => __( 'Maximum seat number', 'tourfic' ),
                    ),
                ),
            ),

            //continuous availability
            array(
                'id'         => 'custom_availability',
                'type'       => 'radio',
                'title'      => __( 'Custom availability', 'tourfic' ),
                'inline'     => true,
                'dependency' => array( 'type', '==', 'continuous' ),
                'options'    => [
                    'yes' => __( 'Yes', 'tourfic' ),
                    'no'  => __( 'No', 'tourfic' ),
                ],
            ),
            array(
                'id'         => 'continuous_availability',
                'type'       => 'repeater',
                'title'      => 'Continuous Availability',
                'class'      => 'continuous_availability',
                'dependency' => array(
                    array( 'custom_availability', '==', 'yes' ),
                    array( 'type', '==', 'continuous' ),
                ),
                'fields'     => array(
                    array(
                        'id'    => 'check_in',
                        'type'  => 'date',
                        'title' => __( 'Check In', 'tourfic' ),
                        'class' => 'check-in',
                        'settings' => array(
                            'dateFormat'      => 'dd/mm/yy'
                        )
                    ),
                    array(
                        'id'    => 'check_out',
                        'type'  => 'date',
                        'title' => __( 'Check Out', 'tourfic' ),
                        'class' => 'check-out',
                        'settings' => array(
                            'dateFormat'      => 'dd/mm/yy'
                        )
                    ),
                    array(
                        'id'    => 'min_seat',
                        'type'  => 'number',
                        'title' => __( 'Min people', 'tourfic' ),
                    ),
                    array(
                        'id'    => 'max_seat',
                        'type'  => 'number',
                        'title' => __( 'Maximum people', 'tourfic' ),
                    ),
                ),
            ),

        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Booking', 'tourfic' ),
        'fields' => array(

            array(
                'id'       => 'min_days',
                'type'     => 'slider',
                'max'      => '30',
                'title'    => __( 'Minimum days to book before departure', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
                'attributes' => array(
                    'disabled' => 'disabled',
                ),
            ),
            array(
                'id'       => 'external_booking',
                'type'     => 'switcher',
                'title'    => __( 'Allow external booking', 'tourfic' ),
                'subtitle' => __( '<strong class="tf-pro">Pro</strong>', 'tourfic' ),
                'attributes' => array(
                    'disabled' => 'disabled',
                ),
            ),
            array(
                'id'         => 'external_booking_link',
                'type'       => 'text',
                'title'      => __( 'External booking link', 'tourfic' ),
                'subtitle'   => __( 'Input external booking link', 'tourfic' ),
                'dependency' => array( 'external_booking', '==', 'true' ),
            ),
        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Itinerary', 'tourfic' ),
        'fields' => array(
            array(
                'id'     => 'itinerary',
                'type'   => 'repeater',
                'title'  => __( 'Itinerary', 'tourfic' ),
                'max'    => apply_filters( 'tf_repeater_limit', '1'),
                'fields' => array(
                    array(
                        'id'       => 'time',
                        'type'     => 'text',
                        'title'    => __( 'Time or Day', 'tourfic' ),
                        'subtitle' => __( 'You can place the tour plan', 'tourfic' ),
                    ),
                    array(
                        'id'       => 'title',
                        'type'     => 'text',
                        'title'    => __( 'Title', 'tourfic' ),
                        'subtitle' => __( 'Input the title here', 'tourfic' ),
                    ),
                    array(
                        'id'           => 'image',
                        'type'         => 'upload',
                        'title'        => 'Upload Image',
                        'library'      => 'image',
                        'placeholder'  => 'http://',
                        'button_title' => 'Add Image',
                        'remove_title' => 'Remove Image',
                    ),
                    array(
                        'id'    => 'desc',
                        'type'  => 'textarea',
                        'title' => __( 'Description', 'tourfic' ),
                    ),
                ),
            ),
        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Exclude/Include', 'tourfic' ),
        'fields' => array(
            array(
                'id'     => 'inc',
                'type'   => 'repeater',
                'title'  => __( 'Include', 'tourfic' ),
                'fields' => array(
                    array(
                        'id'       => 'inc',
                        'type'     => 'text',
                        'title'    => __( 'Included', 'tourfic' ),
                        'subtitle' => __( 'Included facilites', 'tourfic' ),
                    ),
                ),
            ),
            array(
                'id'     => 'exc',
                'type'   => 'repeater',
                'title'  => __( 'Exclude', 'tourfic' ),
                'fields' => array(
                    array(
                        'id'       => 'exc',
                        'type'     => 'text',
                        'title'    => __( 'Excluded', 'tourfic' ),
                        'subtitle' => __( 'Excluded facilites', 'tourfic' ),
                    ),
                ),
            ),
        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'FAQs', 'tourfic' ),
        'fields' => array(
            array(
                'id'     => 'faqs',
                'type'   => 'repeater',
                'title'  => __( 'FAQs', 'tourfic' ),
                'max'    => apply_filters( 'tf_repeater_limit', '5'),
                'fields' => array(
                    array(
                        'id'    => 'title',
                        'type'  => 'text',
                        'title' => __( 'FAQ title', 'tourfic' ),
                    ),
                    array(
                        'id'    => 'desc',
                        'type'  => 'textarea',
                        'title' => __( 'FAQ description', 'tourfic' ),
                    ),
                ),
            ),
        ),
    ) );

    CSF::createSection( $prefix, array(
        'title'  => __( 'Terms & Conditions', 'tourfic' ),
        'fields' => array(
            array(
                'id'    => 'terms_conditions',
                'type'  => 'wp_editor',
                'title' => __( 'Terms & Conditions', 'tourfic' ),
            ),
        ),
    ) );

}
