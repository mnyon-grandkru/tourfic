<?php
/**
 * Adds Filter widget.
 */
class Tourfic_TourFilter extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'tf_tour_filter', // Base ID
            'Tourfic - Filters', // Name
            array( 'description' => __( 'Filter Tourfic tour on archive.', 'tourfic' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        //check if is Hotel
        $posttype = isset( $_GET['type'] ) ? $_GET['type'] : get_post_type();
        if( $posttype == 'tourfic' ) :
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        $terms = isset( $instance[ 'terms' ] ) ? $instance[ 'terms' ] : 'all';
        $show_count = isset( $instance[ 'show_count' ] ) ? $instance[ 'show_count' ] : null;
        $hide_empty = ( $instance[ 'hide_empty' ] == "on" ) ? true : false;

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

		$taxonomy = array(
			'hide_empty' => $hide_empty,
		    'taxonomy' => 'tf_filters',
		    'include' => $terms
		);

		$get_terms = get_terms( $taxonomy );

        $destination_name = !empty( $_GET['destination'] ) ? $_GET['destination'] : '';

		echo "<ul class='tf-popular_filter'>";
		foreach ( $get_terms as $key => $term ) {
			$name = $term->name;
			$id = $term->term_id;
			$default_count = $term->count;
			$count = $show_count ? '<span>'.tf_term_count( $term->slug, $destination_name, $default_count ).'</span>' : '';

			echo "<li><label><input type='checkbox' name='tf_filters[]' value='{$id}'/> {$name}</label> {$count}</li>";
		}
		echo "</ul>";

        echo $after_widget;
    endif;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Popular Filters', 'tourfic' );
        $terms = isset( $instance[ 'terms' ] ) ? $instance[ 'terms' ] : 'all';

        $show_count = isset( $instance[ 'show_count' ] ) ? $instance[ 'show_count' ] : '';
        $hide_empty = isset( $instance[ 'hide_empty' ] ) ? $instance[ 'hide_empty' ] : '';

        ?>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

		<p class="tf-widget-field">
	        <label for="<?php echo $this->get_field_id( 'terms' ); ?>">Select Terms:</label>
	        <br>
			<?php
			    wp_dropdown_categories( array(
			        'taxonomy'          => 'tf_filters',
			        'hierarchical'      => false,
			        //'show_option_none'  => esc_html_x( '', 'All Terms', 'tourfic' ),
			        //'option_none_value' => '',
			        'name'              => $this->get_field_name( 'terms' ),
			        'id'                => $this->get_field_id( 'terms' ),
			        'selected'          => $terms, // e.x 86,110,786
			        'multiple'          => true,
			        'class'          	=> 'widefat tf-select2',
			        'show_count' 		=> true,
			        'hide_empty'        => 0
			    ) );
			?>
			<br>
			<span>Leave this field empty if you want to show all terms.</span>
        </p>
        <p class="tf-widget-field">
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>">Show Count:</label>
			<input id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" type="checkbox" <?php checked('on', $show_count); ?>>
		</p>
		<p class="tf-widget-field">
			<label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>">Hide Empty Categories:</label>
			<input id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" type="checkbox" <?php checked('on', $hide_empty); ?>>
		</p>
		<style>
			.tf-widget-field label{
				font-weight: 600;
			}
		</style>
		<script>
			jQuery('#<?php echo $this->get_field_id( 'terms' ); ?>').select2({ width: '100%' });
			jQuery(document).trigger('tf_select2');
		</script>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['terms'] = ( !empty( $new_instance['terms'] ) ) ? implode( ",", $new_instance['terms'] ) : 'all';
        $instance['show_count'] = ( !empty( $new_instance['show_count'] ) ) ? strip_tags( $new_instance['show_count'] ) : '';
        $instance['hide_empty'] = ( !empty( $new_instance['hide_empty'] ) ) ? strip_tags( $new_instance['hide_empty'] ) : '';

        return $instance;
    }

}


/**
 * Adds Tour Feature Filter widget.
 */
class Tourfic_Tour_FeatureFilter extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'tf_tour_feature_filter', // Base ID
            'Tourfic - Features', // Name
            array( 'description' => __( 'Filter Tourfic tour on archive with features.', 'tourfic' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        //check if is Tours
        $posttype = isset( $_GET['type'] ) ? $_GET['type'] : get_post_type();
        if( $posttype == 'tf_tours' ) :

        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        $terms = isset( $instance[ 'terms' ] ) ? $instance[ 'terms' ] : 'all';
        $show_count = isset( $instance[ 'show_count' ] ) ? $instance[ 'show_count' ] : null;
        $hide_empty = ( $instance[ 'hide_empty' ] == "on" ) ? true : false;

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

		$taxonomy = array(
			'hide_empty' => $hide_empty,
		    'taxonomy' => 'tf_feature',
		    'include' => $terms
		);

		$get_terms = get_terms( $taxonomy );

        $destination_name = !empty( $_GET['tour_destination'] ) ? $_GET['tour_destination'] : '';

		echo "<ul class='tf-popular_filter'>";
		foreach ( $get_terms as $key => $term ) {
			$name = $term->name;
			$id = $term->term_id;
			$default_count = $term->count;
			$count = $show_count ? '<span>'.tf_term_count( $term->slug, $destination_name, $default_count ).'</span>' : '';

			echo "<li><label><input type='checkbox' name='tf_features[]' value='{$id}'/> {$name}</label> {$count}</li>";
		}
		echo "</ul>";

        echo $after_widget;
    endif;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Popular Filters', 'tourfic' );
        $terms = isset( $instance[ 'terms' ] ) ? $instance[ 'terms' ] : 'all';

        $show_count = isset( $instance[ 'show_count' ] ) ? $instance[ 'show_count' ] : '';
        $hide_empty = isset( $instance[ 'hide_empty' ] ) ? $instance[ 'hide_empty' ] : '';

        ?>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

		<p class="tf-widget-field">
	        <label for="<?php echo $this->get_field_id( 'terms' ); ?>">Select Terms:</label>
	        <br>
			<?php
			    wp_dropdown_categories( array(
			        'taxonomy'          => 'tf_feature',
			        'hierarchical'      => false,
			        //'show_option_none'  => esc_html_x( '', 'All Terms', 'tourfic' ),
			        //'option_none_value' => '',
			        'name'              => $this->get_field_name( 'terms' ),
			        'id'                => $this->get_field_id( 'terms' ),
			        'selected'          => $terms, // e.x 86,110,786
			        'multiple'          => true,
			        'class'          	=> 'widefat tf-select2',
			        'show_count' 		=> true,
			        'hide_empty'        => 0
			    ) );
			?>
			<br>
			<span>Leave this field empty if you want to show all terms.</span>
        </p>
        <p class="tf-widget-field">
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>">Show Count:</label>
			<input id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" type="checkbox" <?php checked('on', $show_count); ?>>
		</p>
		<p class="tf-widget-field">
			<label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>">Hide Empty Categories:</label>
			<input id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" type="checkbox" <?php checked('on', $hide_empty); ?>>
		</p>
		<style>
			.tf-widget-field label{
				font-weight: 600;
			}
		</style>
		<script>
			jQuery('#<?php echo $this->get_field_id( 'terms' ); ?>').select2({ width: '100%' });
			jQuery(document).trigger('tf_select2');
		</script>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['terms'] = ( !empty( $new_instance['terms'] ) ) ? implode( ",", $new_instance['terms'] ) : 'all';
        $instance['show_count'] = ( !empty( $new_instance['show_count'] ) ) ? strip_tags( $new_instance['show_count'] ) : '';
        $instance['hide_empty'] = ( !empty( $new_instance['hide_empty'] ) ) ? strip_tags( $new_instance['hide_empty'] ) : '';

        return $instance;
    }

}

/**
 * Similar Tours
 */
class Tourfic_Similar_Tours extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'tf_similar_tours', // Base ID
            'Tourfic - Similar Tours', // Name
            array( 'description' => __( 'Show more tours button on single tour page.', 'tourfic' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $btn_label = isset( $instance[ 'btn_label' ] ) ? $instance[ 'btn_label' ] : __( 'Show more hotels', 'tourfic' );

        if ( !is_singular( 'tourfic' ) ) {
        	return;
        }


        $terms = get_the_terms( get_the_ID(), 'destination' );

        echo $before_widget;
        ?>
		<!-- Start similar tour widget -->
		<div class="tf-similar-tour-wrap">
			<?php
			if ( ! empty( $title ) ) {
	            echo "<div class='not-impressive'>{$title}</div>";
	        }
			?>
			<div class="ni-buttons">
				<a href="<?php echo tourfic_booking_search_action().'?destination='.esc_attr( $terms[0]->name ).'&adults='.$_GET['adults'].'&children='.$_GET['children'].'&room='.$_GET['room'].'&check-in-date='.$_GET['check-in-date'].'&check-out-date='.$_GET['check-out-date']; ?>" class="button tf_button btn-outline"><?php esc_html_e( $btn_label ); ?></a>
			</div>
		</div>
		<!-- End similar tour widget -->
        <?php

        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Not impressive?', 'tourfic' );
        $btn_label = isset( $instance[ 'btn_label' ] ) ? $instance[ 'btn_label' ] : __( 'Show more hotels', 'tourfic' );
        ?>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tourfic' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'btn_label' ); ?>"><?php _e( 'Button Label', 'tourfic' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'btn_label' ); ?>" name="<?php echo $this->get_field_name( 'btn_label' ); ?>" type="text" value="<?php echo esc_attr( $btn_label ); ?>" />
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['btn_label'] = ( !empty( $new_instance['btn_label'] ) ) ? strip_tags( $new_instance['btn_label'] ) : '';

        return $instance;
    }

}


/**
 * Show On Map
 */
class Tourfic_Show_On_Map extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'tf_show_on_map', // Base ID
            'Tourfic - Show On Map', // Name
            array( 'description' => __( 'Show On Map tours button on single tour page.', 'tourfic' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        if ( !is_singular( 'tourfic' ) ) {
        	return;
        }

        echo $before_widget;

        ?>
		<!-- Start map tour widget -->
		<div class="tf-map-tour-wrap">
			<?php $location = get_field('formatted_location') ? get_field('formatted_location') : null; ?>
			<div class="map-bg"><img src="<?php echo TOURFIC_PLUGIN_URL; ?>/assets/map.png"></div>
			<div class="map-buttons">
				<a href="https://www.google.com/maps/search/<?php _e( $location ); ?>" target="_blank" class="button tf_button"><?php esc_html_e( $title ); ?></a>
			</div>
		</div>
		<!-- End map tour widget -->
        <?php

        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Show on map', 'tourfic' );
        ?>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Button Label', 'tourfic' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

}


/**
 * Ask Question
 */
class Tourfic_Ask_Question extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'tf_ask_question', // Base ID
            'Tourfic - Ask Question', // Name
            array( 'description' => __( 'Ask a question button on single tour page.', 'tourfic' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $subtitle = isset( $instance[ 'subtitle' ] ) ? $instance[ 'subtitle' ] : __( 'Find more info in the FAQ section.', 'tourfic' );
        $btn_label = isset( $instance[ 'btn_label' ] ) ? $instance[ 'btn_label' ] : __( 'Ask a question', 'tourfic' );

        if ( !is_singular( 'tourfic' ) ) {
        	return;
        }

        echo $before_widget;

        ?>
		<!-- Start ask ques tour widget -->
		<div class="tf-gotq-tour-wrap">
			<div class="gotq-top">
				<?php
				if ( ! empty( $title ) ) {
		            echo "<h4>{$title}</h4>";
		        }
				?>
				<?php
				if ( ! empty( $subtitle ) ) {
		            echo "<p>{$subtitle}</p>";
		        }
				?>
			</div>
			<div class="ni-buttons">
				<a href="#" id="tf-ask-question-trigger" class="button tf_button btn-outline"><?php esc_html_e( $btn_label ); ?></a>
			</div>
		</div>
		<!-- End ask ques tour widget -->
        <?php

        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Got a question?', 'tourfic' );
        $subtitle = isset( $instance[ 'subtitle' ] ) ? $instance[ 'subtitle' ] : __( 'Find more info in the FAQ section.', 'tourfic' );
        $btn_label = isset( $instance[ 'btn_label' ] ) ? $instance[ 'btn_label' ] : __( 'Ask a question', 'tourfic' );
        ?>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tourfic' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><?php _e( 'Subtitle', 'tourfic' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" />
        </p>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id( 'btn_label' ); ?>"><?php _e( 'Button Label', 'tourfic' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'btn_label' ); ?>" name="<?php echo $this->get_field_name( 'btn_label' ); ?>" type="text" value="<?php echo esc_attr( $btn_label ); ?>" />
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['btn_label'] = ( !empty( $new_instance['btn_label'] ) ) ? strip_tags( $new_instance['btn_label'] ) : '';
        $instance['subtitle'] = ( !empty( $new_instance['subtitle'] ) ) ? strip_tags( $new_instance['subtitle'] ) : '';

        return $instance;
    }

}



/**
 * Tourfic Hotel features filter
 * @author KK
 */
class TF_Hotel_Feature_Filter extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {

        parent::__construct(
            'tf_hotel_filter', // Base ID
            'Tourfic - Hotels Feature Filters', // Name
            array('description' => __('Filter Tourfic hotel feature on archive.', 'tourfic'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {

        //check if is Hotel
        $posttype = isset($_GET['type']) ? $_GET['type'] : get_post_type();
        if ($posttype == 'tf_hotel') :
            extract($args);
            $title = apply_filters('widget_title', $instance['title']);

            $terms = isset($instance['terms']) ? $instance['terms'] : 'all';
            $show_count = isset($instance['show_count']) ? $instance['show_count'] : null;
            $hide_empty = ($instance['hide_empty'] == "on") ? true : false;

            echo $before_widget;
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }

            $taxonomy = array(
                'hide_empty' => $hide_empty,
                'taxonomy' => 'hotel_feature',
                'include' => $terms
            );

            $get_terms = get_terms($taxonomy);

            $destination_name = !empty($_GET['destination']) ? $_GET['destination'] : '';

            echo "<div class='tf-filter'><ul>";
            foreach ($get_terms as $key => $term) {
                $feature_meta = get_term_meta($term->term_taxonomy_id, 'hotel_feature', true);
                if ($feature_meta['icon-type'] == 'fa') {
                    $feature_icon = '<i class="' . $feature_meta['icon-fa'] . '"></i>';
                } elseif ($feature_meta['icon-type'] == 'c') {
                    $feature_icon = '<img src="' . $feature_meta['icon-c']["url"] . '" style="width: ' . $feature_meta['dimention']["width"] . 'px; height: ' . $feature_meta['dimention']["width"] . 'px;" />';
                }
                $id = $term->term_id;
                $name = $term->name;
                $default_count = $term->count;
                $count = $show_count ? '<span>' . tf_term_count($term->slug, $destination_name, $default_count) . '</span>' : '';

                echo "<li><label><input type='checkbox' name='tf_filters[]' value='{$id}'/> {$feature_icon} {$name}</label> {$count}</li>";
            }
            echo "</ul></div>";

            echo $after_widget;
        endif;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {

        $title = isset($instance['title']) ? $instance['title'] : __('Popular Filters', 'tourfic');
        $terms = isset($instance['terms']) ? $instance['terms'] : 'all';

        $show_count = isset($instance['show_count']) ? $instance['show_count'] : '';
        $hide_empty = isset($instance['hide_empty']) ? $instance['hide_empty'] : '';

    ?>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id('terms'); ?>">Select Terms:</label>
            <br>
            <?php
            wp_dropdown_categories(array(
                'taxonomy'          => 'tf_filters',
                'hierarchical'      => false,
                //'show_option_none'  => esc_html_x( '', 'All Terms', 'tourfic' ),
                //'option_none_value' => '',
                'name'              => $this->get_field_name('terms'),
                'id'                => $this->get_field_id('terms'),
                'selected'          => $terms, // e.x 86,110,786
                'multiple'          => true,
                'class'              => 'widefat tf-select2',
                'show_count'         => true,
                'hide_empty'        => 0
            ));
            ?>
            <br>
            <span>Leave this field empty if you want to show all terms.</span>
        </p>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id('show_count'); ?>">Show Count:</label>
            <input id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>" type="checkbox" <?php checked('on', $show_count); ?>>
        </p>
        <p class="tf-widget-field">
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>">Hide Empty Categories:</label>
            <input id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" type="checkbox" <?php checked('on', $hide_empty); ?>>
        </p>
        <style>
            .tf-widget-field label {
                font-weight: 600;
            }
        </style>
        <script>
            jQuery('#<?php echo $this->get_field_id('terms'); ?>').select2({
                width: '100%'
            });
            jQuery(document).trigger('tf_select2');
        </script>
<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['terms'] = (!empty($new_instance['terms'])) ? implode(",", $new_instance['terms']) : 'all';
        $instance['show_count'] = (!empty($new_instance['show_count'])) ? strip_tags($new_instance['show_count']) : '';
        $instance['hide_empty'] = (!empty($new_instance['hide_empty'])) ? strip_tags($new_instance['hide_empty']) : '';

        return $instance;
    }
}
