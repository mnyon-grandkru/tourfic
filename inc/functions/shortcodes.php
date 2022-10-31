<?php
/**
 * Hotel Locations Shortcode
 */
function hotel_locations_shortcode( $atts, $content = null ) {

	// Shortcode extract
	extract(
		shortcode_atts(
			array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => 0,
				'ids'        => '',
				'limit'     => - 1,
			),
			$atts
		)
	);

	// 1st search on hotel_location taxonomy
	$locations = get_terms( array(
		'taxonomy'     => 'hotel_location',
		'orderby'      => $orderby,
		'order'        => $order,
		'hide_empty'   => $hide_empty,
		'hierarchical' => 0,
		'search'       => '',
		'number'       => $limit == - 1 ? false : $limit,
		'include'      => $ids,
	) );

	ob_start();

	if ( $locations ) { ?>
        <section id="recomended_section_wrapper">
            <div class="recomended_inner">

				<?php foreach ( $locations as $term ) {

					$meta      = get_term_meta( $term->term_id, 'hotel_location', true );
					$image_url = ! empty( $meta['image']['url'] ) ? $meta['image']['url'] : TF_ASSETS_URL . 'img/img-not-available.svg';
					$term_link = get_term_link( $term ); ?>

                    <div class="single_recomended_item">
                        <a href="<?php echo $term_link; ?>">
                            <div class="single_recomended_content" style="background-image: url(<?php echo $image_url; ?>);">
                                <div class="recomended_place_info_header">
                                    <h3><?php echo esc_html( $term->name ); ?></h3>
                                    <p><?php printf( _n( '%s hotel', '%s hotels', $term->count, 'tourfic' ), $term->count ); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

				<?php } ?>

            </div>
        </section>

	<?php }

	return ob_get_clean();
}

add_shortcode( 'hotel_locations', 'hotel_locations_shortcode' );
// Old compatibility
add_shortcode( 'tourfic_destinations', 'hotel_locations_shortcode' );


/**
 * Tour destinations shortcode
 */
function shortcode_tour_destinations( $atts, $content = null ) {

	// Shortcode extract
	extract(
		shortcode_atts(
			array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => 0,
				'ids'        => '',
				'limit'     => - 1,
			),
			$atts
		)
	);

	// 1st search on Destination taxonomy
	$destinations = get_terms( array(
		'taxonomy'     => 'tour_destination',
		'orderby'      => $orderby,
		'order'        => $order,
		'hide_empty'   => $hide_empty,
		'hierarchical' => 0,
		'search'       => '',
		'number'       => $limit == - 1 ? false : $limit,
		'include'      => $ids,
	) );

	shuffle( $destinations );
	ob_start();

	if ( $destinations ) { ?>
        <section id="recomended_section_wrapper">
            <div class="recomended_inner">

				<?php foreach ( $destinations as $term ) {

					$meta      = get_term_meta( $term->term_id, 'tour_destination', true );
					$image_url = ! empty( $meta['image']['url'] ) ? $meta['image']['url'] : TF_ASSETS_URL . 'img/img-not-available.svg';
					$term_link = get_term_link( $term );

					if ( is_wp_error( $term_link ) ) {
						continue;
					} ?>

                    <div class="single_recomended_item">
                        <a href="<?php echo $term_link; ?>">
                            <div class="single_recomended_content" style="background-image: url(<?php echo $image_url; ?>);">
                                <div class="recomended_place_info_header">
                                    <h3><?php echo esc_html( $term->name ); ?></h3>
                                    <p><?php printf( _n( '%s tour', '%s tours', $term->count, 'tourfic' ), $term->count ); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

				<?php } ?>

            </div>
        </section>
	<?php }

	return ob_get_clean();
}

add_shortcode( 'tour_destinations', 'shortcode_tour_destinations' );

/**
 * Recent Hotel Slider
 */
function tf_recent_hotel_shortcode( $atts, $content = null ) {
	extract(
		shortcode_atts(
			array(
				'title'        => '',  //title populer section
				'subtitle'     => '',   // Sub title populer section
				'count'        => 10,
				'slidestoshow' => 5,
			),
			$atts
		)
	);

	$args = array(
		'post_type'      => 'tf_hotel',
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $count,
	);

	ob_start();

	$hotel_loop = new WP_Query( $args );

	// Generate an Unique ID
	$thisid = uniqid( 'tfpopular_' );

	?>
	<?php if ( $hotel_loop->have_posts() ) : ?>
        <div class="tf-widget-slider recent-hotel-slider">
            <div class="tf-heading">
				<?php
				if ( ! empty( $title ) ) {
					echo '<h2>' . esc_html( $title ) . '</h2>';
				}
				if ( ! empty( $subtitle ) ) {
					echo '<p>' . esc_html( $subtitle ) . '</p>';
				}
				?>
            </div>

            <div class="tf-slider-items-wrapper">
				<?php while ( $hotel_loop->have_posts() ) {
					$hotel_loop->the_post();
					$post_id                = get_the_ID();
					$related_comments_hotel = get_comments( array( 'post_id' => $post_id ) );
					$meta = get_post_meta( $post_id, 'tf_hotel', true );
					$rooms = !empty($meta['room']) ? $meta['room'] : '';
					//get and store all the prices for each room
					$room_price = [];
					foreach( $rooms as $room ){
						$room_price[] = $room['price'];
					}

					?>
                    <div class="tf-slider-item" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>);">
                        <div class="tf-slider-content">
                            <div class="tf-slider-desc">
                                <h3>
                                    <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                </h3>
								<?php if ( $related_comments_hotel ) { ?>
                                    <div class="tf-slider-rating-star">
                                        <i class="fas fa-star"></i> <span style="color:#fff;"><?php echo tf_total_avg_rating( $related_comments_hotel ); ?></span>
                                    </div>
								<?php } ?>
                                <p><?php echo wp_trim_words( get_the_content(), 10 ); ?></p>
								<?php if(!empty($rooms)): ?>
								<div class="tf-recent-room-price">
								<?php
									//get the lowest price from all available room price
									$lowest_price = wc_price( min($room_price) );
									echo __("From ","tourfic") . $lowest_price; 
										
								?>
								</div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php endif;
	wp_reset_postdata(); ?>

	<?php return ob_get_clean();
}

add_shortcode( 'tf_recent_hotel', 'tf_recent_hotel_shortcode' );
// old
add_shortcode( 'tf_tours', 'tf_recent_hotel_shortcode' );

/**
 * Recent Tour
 */
function tf_recent_tour_shortcode( $atts, $content = null ) {
	extract(
		shortcode_atts(
			array(
				'title'        => '',  //title populer section
				'subtitle'     => '',   // Sub title populer section
				'count'        => 10,
				'slidestoshow' => 5,
			),
			$atts
		)
	);

	$args = array(
		'post_type'      => 'tf_tours',
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $count,
	);

	ob_start();

	$tour_loop = new WP_Query( $args );

	// Generate an Unique ID
	$thisid = uniqid( 'tfpopular_' );

	?>
	<?php if ( $tour_loop->have_posts() ) : ?>
        <div class="tf-widget-slider recent-tour-slider">
            <div class="tf-heading">
				<?php
				if ( ! empty( $title ) ) {
					echo '<h2>' . esc_html( $title ) . '</h2>';
				}
				if ( ! empty( $subtitle ) ) {
					echo '<p>' . esc_html( $subtitle ) . '</p>';
				}
				?>
            </div>


            <div class="tf-slider-items-wrapper">
				<?php while ( $tour_loop->have_posts() ) {
					$tour_loop->the_post();
					$post_id          = get_the_ID();
					$related_comments = get_comments( array( 'post_id' => $post_id ) );
					?>
                    <div class="tf-slider-item" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>);">
                        <div class="tf-slider-content">
                            <div class="tf-slider-desc">
                                <h3>
                                    <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                </h3>
								<?php if ( $related_comments ) { ?>
                                    <div class="tf-slider-rating-star">
                                        <i class="fas fa-star"></i> <span style="color:#fff;"><?php echo tf_total_avg_rating( $related_comments ); ?></span>
                                    </div>
								<?php } ?>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 10 ); ?></p>

                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php endif;
	wp_reset_postdata(); ?>

	<?php return ob_get_clean();
}

add_shortcode( 'tf_recent_tour', 'tf_recent_tour_shortcode' );
// Old
add_shortcode( 'tf_tours_grid', 'tf_recent_tour_shortcode' );

/**
 * Search form
 */
function tf_search_form_shortcode( $atts, $content = null ) {
	extract(
		shortcode_atts(
			array(
				'style'     => 'default', //recomended, populer
				'type'      => 'all',
				'title'     => '',  //title populer section
				'subtitle'  => '',   // Sub title populer section
				'classes'   => '',
				'fullwidth' => '',
				'advanced'  => '',
			),
			$atts
		)
	);

	if ( $style == 'default' ) {
		$classes = " default-form ";
	}

	$type             = explode( ',', $type );
	$disable_services = tfopt( 'disable-services' ) ? tfopt( 'disable-services' ) : array();
	$child_age_limit = tfopt( 'enable_child_age_limit' ) ? tfopt( 'enable_child_age_limit' ) : '';
	if($child_age_limit == '1'){
		$child_age_limit = ' child-age-limited';
	}else{
		$child_age_limit = '';
	}

	ob_start();
	?>

	<?php tourfic_fullwidth_container_start( $fullwidth ); ?>
    <div id="tf-booking-search-tabs">

        <!-- Booking Form Tabs -->
        <div class="tf-booking-form-tab">
			<?php do_action( 'tf_before_booking_form_tab', $type ) ?>

			<?php if ( ! in_array( 'hotel', $disable_services ) && tf_is_search_form_tab_type( 'hotel', $type ) && ! tf_is_search_form_single_tab( $type ) ) : ?>
                <button class="tf-tablinks active" onclick="tfOpenForm(event, 'tf-hotel-booking-form')"><?php _e( 'Hotel', 'tourfic' ); ?></button>
			<?php endif; ?>

			<?php if ( ! in_array( 'tour', $disable_services ) && tf_is_search_form_tab_type( 'tour', $type ) && ! tf_is_search_form_single_tab( $type ) ) : ?>
                <button class="tf-tablinks" onclick="tfOpenForm(event, 'tf-tour-booking-form')"><?php _e( 'Tour', 'tourfic' ); ?></button>
			<?php endif ?>

			<?php do_action( 'tf_after_booking_form_tab', $type ) ?>
        </div>

        <?php if(! tf_is_search_form_single_tab( $type )): ?>
            <!-- Booking Form tabs mobile version -->
            <div class="tf-booking-form-tab-mobile">
                <select name="tf-booking-form-tab-select" id="">
                    <?php do_action( 'tf_before_booking_form_mobile_tab', $type ) ?>

                    <?php if ( ! in_array( 'hotel', $disable_services ) && tf_is_search_form_tab_type( 'hotel', $type ) && ! tf_is_search_form_single_tab( $type ) ) : ?>
                        <option value="tf-hotel-booking-form"><?php _e( 'Hotel', 'tourfic' ); ?></option>
                    <?php endif; ?>
                    <?php if ( ! in_array( 'tour', $disable_services ) && tf_is_search_form_tab_type( 'tour', $type ) && ! tf_is_search_form_single_tab( $type ) ) : ?>
                        <option value="tf-tour-booking-form"><?php _e( 'Tour', 'tourfic' ); ?></option>
                    <?php endif ?>

                    <?php do_action( 'tf_after_booking_form_mobile_tab', $type ) ?>
                </select>
            </div>
        <?php endif; ?>

        <!-- Booking Forms -->
        <div class="tf-booking-forms-wrapper">
			<?php
			do_action( 'tf_before_booking_form', $classes, $title, $subtitle, $type );

			if ( ! in_array( 'hotel', $disable_services ) && tf_is_search_form_tab_type( 'hotel', $type ) ) {
				?>
                <div id="tf-hotel-booking-form" style="display:block" class="tf-tabcontent <?php echo esc_attr( $child_age_limit ); ?>">
					<?php
					if ( $advanced == "enabled" ) {
						tf_hotel_advanced_search_form_horizontal( $classes, $title, $subtitle );
					} else {
						tf_hotel_search_form_horizontal( $classes, $title, $subtitle );
					}
					?>
                </div>
				<?php
			}
			if ( ! in_array( 'tour', $disable_services ) && tf_is_search_form_tab_type( 'tour', $type ) ) {
				?>
                <div id="tf-tour-booking-form" class="tf-tabcontent" <?php echo tf_is_search_form_single_tab( $type ) ? 'style="display:block"' : '' ?><?php echo esc_attr( $child_age_limit ); ?>>
					<?php
					if ( $advanced == "enabled" ) {
						tf_tour_advanced_search_form_horizontal( $classes, $title, $subtitle );
					} else {
						tf_tour_search_form_horizontal( $classes, $title, $subtitle );
					}
					?>
                </div>
				<?php
			}

			do_action( 'tf_after_booking_form', $classes, $title, $subtitle, $type );
			?>
        </div>

    </div>
	<?php tourfic_fullwidth_container_end( $fullwidth );

	return ob_get_clean();
}

add_shortcode( 'tf_search_form', 'tf_search_form_shortcode' );
// Old shortcode
add_shortcode( 'tf_search', 'tf_search_form_shortcode' );

/**
 * Search Result Shortcode Function
 */
function tf_search_result_shortcode( $atts, $content = null ){

    // Unwanted Slashes Remove
    if ( isset( $_GET ) ) {
        $_GET = array_map( 'stripslashes_deep', $_GET );
    }
    
    // Get post type
    $post_type = isset( $_GET['type'] ) ? sanitize_text_field($_GET['type']) : '';
    if(empty($post_type)) {
        _e('<h3>Please select fields from the search form!</h3>', 'tourfic');
        return;
    }
    // Get hotel location or tour destination
    $taxonomy = $post_type == 'tf_hotel' ? 'hotel_location' : 'tour_destination';
    // Get place
    $place = isset( $_GET['place'] ) ? sanitize_text_field($_GET['place']) : '';
    // Get Adult
    $adults = isset( $_GET['adults'] ) ? sanitize_text_field($_GET['adults']) : '';
    // Get Child
    $child = isset( $_GET['children'] ) ? sanitize_text_field($_GET['children']) : '';
    //get children ages
    //$children_ages = isset( $_GET['children_ages'] ) ? sanitize_text_field($_GET['children_ages']) : '';
    // Get Room
    $room = isset( $_GET['room'] ) ? sanitize_text_field($_GET['room']) : '';
    // Get date
    $check_in_out = isset( $_GET['check-in-out-date'] ) ? sanitize_text_field($_GET['check-in-out-date']) : '';

    
    // Price Range
    $startprice = isset( $_GET['from'] ) ? absint(sanitize_key($_GET['from'])) : '';
    $endprice = isset( $_GET['to'] ) ? absint(sanitize_key($_GET['to'])) : '';

    if(!empty($startprice) && !empty($endprice)){
        if($_GET['type']=="tf_tours"){
            $data = array($adults, $child, $check_in_out, $startprice, $endprice);
        }else{
            $data = array($adults, $child, $room, $check_in_out, $startprice, $endprice);
        }
    }else{
        $data = array($adults, $child, $room, $check_in_out);
    }



    $paged          = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
    $checkInOutDate = !empty( $_GET['check-in-out-date']) ? explode( ' - ', $_GET['check-in-out-date'] ) : '';
    if(!empty($checkInOutDate)) {
        $period         = new DatePeriod(
            new DateTime( $checkInOutDate[0] ),
            new DateInterval( 'P1D' ),
            new DateTime( $checkInOutDate[1] .  '23:59' )
        );
    } else {
        $period = '';
    }
	
    $post_per_page = tfopt('posts_per_page') ? tfopt('posts_per_page') : 10;
    // Main Query args
    $args = array(
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => $post_per_page,
        'paged'          => $paged,
    );

    $taxonomy_query = new WP_Term_Query(array(
        'taxonomy'   => $taxonomy,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
        'slug'       => sanitize_title($place, ''),
    ));

    if ($taxonomy_query) {

        $place_ids = array();

        // Place IDs array
        foreach($taxonomy_query->get_terms() as $term){ 
            $place_ids[] = $term->term_id;
        }

        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => $taxonomy,
                'terms'    => $place_ids,
            )
        );

    } else {
        $args['s'] = $place;
    }

    
    // Hotel Features

    if (!empty($_GET['features'])) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'hotel_feature',
                'field' => 'slug',
                'terms'    => $_GET['features'],
            )
        );
    }

    $loop = new WP_Query( $args );
	$total_posts = $loop->found_posts;
    ob_start(); ?>
    <!-- Start Content -->
    <div class="tf_search_result">
        <div class="tf-action-top">
			<div class="tf-total-results">
				<span><?php echo esc_html__( 'Total Results ', 'tourfic' ) . '(' . $total_posts . ')'; ?> </span>
			</div>
            <div class="tf-list-grid">
                <a href="#list-view" data-id="list-view" class="change-view" title="<?php _e( 'List View', 'tourfic' ); ?>"><i class="fas fa-list"></i></a>
                <a href="#grid-view" data-id="grid-view" class="change-view" title="<?php _e( 'Grid View', 'tourfic' ); ?>"><i class="fas fa-border-all"></i></a>
            </div>
        </div>
        <div class="archive_ajax_result">
			<?php
			if ( $loop->have_posts() ) {
				$not_found = [];

				while ( $loop->have_posts() ) {
					$loop->the_post();

					if ( $post_type == 'tf_hotel' ) {

						if ( empty( $check_in_out ) ) {
							$not_found[] = 0;
							tf_hotel_archive_single_item();
						} else {
							tf_filter_hotel_by_date( $period, $not_found, $data );
						}

					} else {

						if ( empty( $check_in_out ) ) {
							$not_found[] = 0;
							tf_tour_archive_single_item();
						} else {
							tf_filter_tour_by_date( $period, $not_found, $data );
						}

					}

				}

				if ( ! in_array( 0, $not_found ) ) {
					echo '<div class="tf-nothing-found">' . __( 'Nothing Found! Select another dates', 'tourfic' ) . '</div>';
				}
			} else {
				echo '<div class="tf-nothing-found">' . __( 'Nothing Found!', 'tourfic' ) . '</div>';
			}
			?>
        </div>
        <div class="tf_posts_navigation">
			<?php 
			tourfic_posts_navigation( $loop );
			
			 ?>
        </div>

    </div>
    <!-- End Content -->

	<?php
	 wp_reset_postdata();?>
	<?php return ob_get_clean();
}

add_shortcode( 'tf_search_result', 'tf_search_result_shortcode' );

/**
 * Hotel, Tour review slider shortcode
 * @author Abu Hena
 * @since 2.8.9
 */
add_shortcode( 'tf_reviews', 'tf_reviews_shortcode' );
function tf_reviews_shortcode($atts, $content = null){
	extract(
		shortcode_atts(
			array(
				'type'      => 'tf_hotel',
				'number' => '10',
				'count' => '3',
				'speed' => '2000',
				'arrows' => 'false',
				'autoplay' => 'false',
				'slidesToShow' => '3',
				'slidesToScroll' => 1,
				'infinite' => 'false',
			),
			$atts
		)
	);
	$type == "hotel" ? $type = "tf_hotel" : $type == '';
	$type == "tour" ? $type = "tf_tours" : $type == '';
	ob_start();
	?>
	<div class="tf-single-review tf-reviews-slider">

		<?php
		$args = array(
			'post_type' => $type,
			'number' => $number,
		);
		$comments = get_comments($args);
		
		
		if ( $comments ) {
			foreach ( $comments as $comment ) {
				// Get rating details
				$tf_overall_rate = get_comment_meta( $comment->comment_ID, TF_TOTAL_RATINGS, true );
				if ( $tf_overall_rate == false ) {
					$tf_comment_meta = get_comment_meta( $comment->comment_ID, TF_COMMENT_META, true );
					$tf_overall_rate = tf_average_ratings( $tf_comment_meta );
				}
				$base_rate = get_comment_meta( $comment->comment_ID, TF_BASE_RATE, true );
				$c_rating  = tf_single_rating_change_on_base( $tf_overall_rate, $base_rate );

				// Comment details
				$c_avatar      = get_avatar( $comment, '56' );
				$c_author_name = $comment->comment_author;
				$c_date        = $comment->comment_date;
				$c_content     = $comment->comment_content;
				?>
				<div class="tf-single-details">
					<div class="tf-review-avatar"><?php echo $c_avatar; ?></div>
					<div class="tf-review-details">
						<div class="tf-name"><?php echo $c_author_name; ?></div>
						<div class="tf-date"><?php echo $c_date; ?></div>
						<div class="tf-rating-stars">
							<?php echo $c_rating; ?>
						</div>
						<div class="tf-description"><?php echo $c_content; ?></div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
	<script>		
		/**
		 * Init the reviews slider
		 */
		jQuery('document').ready(function($){

			$(".tf-reviews-slider").each(function(){
				var $this = $(this);
			$this.slick({
				dots: true,
				arrows: <?php echo esc_attr( $arrows ); ?>,
				slidesToShow: <?php echo esc_attr( $count ); ?>,
				infinite: <?php echo esc_attr( $infinite ); ?>,
				speed: <?php echo esc_attr( $speed ); ?>,
				autoplay: <?php echo esc_attr( $autoplay ); ?>,
				autoplaySpeed: <?php echo esc_attr( $speed ); ?>,
				slidesToScroll: <?php echo esc_attr( $slidesToScroll ); ?>,
				responsive: [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 1,
							infinite: true,
							dots: true
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
				]
			});
		})
	})
	</script>
	<?php 
	return ob_get_clean();
}

/**
 * Hotel Grid/Slider by locations shortcode
 * @author Abu Hena
 * @since 2.8.9
 */
add_shortcode( 'tf_hotel', 'tf_hotels_grid_slider' );
function tf_hotels_grid_slider($atts, $content = null){
	extract(
		shortcode_atts(
			array(
				'title'   => '',
				'subtitle'   => '',
				'locations'   => '',
				'count'       => '3',
				'style'       => 'grid',
			),
			$atts
		)
	);
	
	$args = array(
		'post_type'      => 'tf_hotel',
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $count,
	);

	$locations = explode(',',$locations);
	if( !empty( $locations )){
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'hotel_location',
				'field'    => 'term_id',
				'terms'    => $locations,
			)
		);
	}
	ob_start();

	if( $style == 'slider' ){
		$slider_activate = 'tf-slider-activated';
	}else{
		$slider_activate = 'tf-hotel-grid';
	}
	$hotel_loop = new WP_Query( $args );

	?>
	<?php if ( $hotel_loop->have_posts() ) : ?>
        <div class="tf-widget-slider recent-hotel-slider">
            <div class="tf-heading">
				<?php
				if ( ! empty( $title ) ) {
					echo '<h2>' . esc_html( $title ) . '</h2>';
				}
				if ( ! empty( $subtitle ) ) {
					echo '<p>' . esc_html( $subtitle ) . '</p>';
				}
				?>
            </div>

            <div class="<?php echo esc_attr( $slider_activate ); ?>">
				<?php while ( $hotel_loop->have_posts() ) {
					$hotel_loop->the_post();
					$post_id                = get_the_ID();
					$related_comments_hotel = get_comments( array( 'post_id' => $post_id ) );
					$meta = get_post_meta( $post_id, 'tf_hotel', true );
					$rooms = !empty($meta['room']) ? $meta['room'] : '';
					//get and store all the prices for each room
					$room_price = [];
					if($rooms){
						foreach( $rooms as $room ){
							$room_price[] = $room['price'];
						}
					}	
					?>
                    <div class="tf-slider-item" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>);">
                        <div class="tf-slider-content">
                            <div class="tf-slider-desc">
                                <h3> 
                                    <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                </h3>
								<?php if ( $related_comments_hotel ) { ?>
                                    <div class="tf-slider-rating-star">
                                        <i class="fas fa-star"></i> <span style="color:#fff;"><?php echo tf_total_avg_rating( $related_comments_hotel ); ?></span>
                                    </div>
								<?php } ?>
                                <p><?php echo wp_trim_words( get_the_content(), 10 ); ?></p>
								<?php if(!empty($rooms)): ?>
								<div class="tf-recent-room-price">
								<?php
									//get the lowest price from all available room price
									$lowest_price = wc_price( min($room_price) );
									echo __("From ","tourfic") . $lowest_price; 
										
								?>
								</div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php endif;
	wp_reset_postdata(); 
	return ob_get_clean();
}

/**
 * Tour Grid/Slider by locations shortcode
 * @author Abu Hena
 * @since 2.8.9
 */
add_shortcode( 'tf_tour', 'tf_tours_grid_slider' );
function tf_tours_grid_slider($atts, $content = null){
	extract(
		shortcode_atts(
			array(
				'title'   => '',
				'subtitle'   => '',
				'destinations'   => '',
				'count'       => '3',
				'style'       => 'grid',
			),
			$atts
		)
	);
	
	$args = array(
		'post_type'      => 'tf_tours',
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $count,
	);
	//Check if destination selected/choosen
	if( !empty( $destinations )){
		$destinations = explode(',',$destinations);
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'tour_destination',
				'field'    => 'term_id',
				'terms'    => $destinations,
			)
		);
	}
	ob_start();

	if( $style == 'slider' ){
		$slider_activate = 'tf-slider-activated';
	}else{
		$slider_activate = 'tf-hotel-grid';
	}
	$tour_loop = new WP_Query( $args );

	?>
	<?php if ( $tour_loop->have_posts() ) : ?>
        <div class="tf-widget-slider recent-tour-slider">
            <div class="tf-heading">
				<?php
				if ( ! empty( $title ) ) {
					echo '<h2>' . esc_html( $title ) . '</h2>';
				}
				if ( ! empty( $subtitle ) ) {
					echo '<p>' . esc_html( $subtitle ) . '</p>';
				}
				?>
            </div>


            <div class="<?php echo esc_attr( $slider_activate ); ?>">
				<?php while ( $tour_loop->have_posts() ) {
					$tour_loop->the_post();
					$post_id          = get_the_ID();
					$related_comments = get_comments( array( 'post_id' => $post_id ) );
					?>
                    <div class="tf-slider-item" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>);">
                        <div class="tf-slider-content">
                            <div class="tf-slider-desc">
                                <h3>
                                    <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                </h3>
								<?php if ( $related_comments ) { ?>
                                    <div class="tf-slider-rating-star">
                                        <i class="fas fa-star"></i> <span style="color:#fff;"><?php echo tf_total_avg_rating( $related_comments ); ?></span>
                                    </div>
								<?php } ?>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 10 ); ?></p>

                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php endif;
	wp_reset_postdata(); 
	return ob_get_clean();
}


