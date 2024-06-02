<?php

namespace Tourfic\App\Shortcodes;

defined( 'ABSPATH' ) || exit;

class Tour_Destinations {

	use \Tourfic\Traits\Singleton;

	public function __construct() {
		add_shortcode( 'hotel_locations', [ $this, 'render' ] );
	}

	function render( $atts, $content = null ) {

	}
}