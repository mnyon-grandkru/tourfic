<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TF_Options' ) ) {
	class TF_Options {

		private static $instance = null;

		/**
		 * Singleton instance
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			//load files
			$this->load_files();

			//load metaboxes
			$this->load_metaboxes();

            //load options
            $this->load_options();

			//enqueue scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'tf_options_enqueue_scripts' ) );
		}

		/**
		 * Load files
		 * @since 1.0.0
		 */
		public function load_files() {
			// Metaboxes Class
			require_once TF_ADMIN_PATH . 'tf-options/Classes/TF_Metabox.php';
			// Settings Class
			require_once TF_ADMIN_PATH . 'tf-options/Classes/TF_Settings.php';
		}

		/**
		 * Load metaboxes
		 * @since 1.0.0
		 */
		public function load_metaboxes() {
			$metaboxes = glob( TF_ADMIN_PATH . 'tf-options/metaboxes/*.php' );
			if ( ! empty( $metaboxes ) ) {
				foreach ( $metaboxes as $metabox ) {
					$metabox_name = basename( $metabox, '.php' );
					if ( ! class_exists( $metabox_name ) ) {
						require_once $metabox;
					}
				}
			}
		}

		/**
		 * Load Options
         * @since 1.0.0
		 */
        public function load_options() {
            $options = glob( TF_ADMIN_PATH . 'tf-options/options/*.php' );
            if ( ! empty( $options ) ) {
                foreach ( $options as $option ) {
                    $option_name = basename( $option, '.php' );
                    if ( ! class_exists( $option_name ) ) {
                        require_once $option;
                    }
                }
            }
        }

		/**
		 * Enqueue scripts
		 * @since 1.0.0
		 */
		public function tf_options_enqueue_scripts() {
			//Css
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'tf-fontawesome-4', '//cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', array(), TOURFIC );
			wp_enqueue_style( 'tf-fontawesome-5', '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', array(), TOURFIC );
			wp_enqueue_style( 'tf-fontawesome-6', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css', array(), TOURFIC );
			wp_enqueue_style( 'tf-remixicon', '//cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css', array(), TOURFIC );
			wp_enqueue_style( 'tf-select2', TF_ADMIN_URL . 'tf-options/assets/css/select2.min.css', array(), TOURFIC );
			wp_enqueue_style( 'tf-flatpickr', TF_ADMIN_URL . 'tf-options/assets/css/flatpickr.min.css', array(), TOURFIC );
			wp_enqueue_style( 'tf-options', TF_ADMIN_URL . 'tf-options/assets/css/tf-options.css', array(), TOURFIC );

            //Js
			wp_enqueue_script( 'tf-flatpickr', TF_ADMIN_URL . 'tf-options/assets/js/flatpickr.min.js', array( 'jquery' ), TOURFIC, true );
			wp_enqueue_script( 'tf-select2', TF_ADMIN_URL . 'tf-options/assets/js/select2.min.js', array( 'jquery' ), TOURFIC, true );
			wp_enqueue_script( 'wp-color-picker-alpha', TF_ADMIN_URL . 'tf-options/assets/js/wp-color-picker-alpha.js', array( 'jquery', 'wp-color-picker' ), TOURFIC, true );
			wp_enqueue_script( 'tf-options', TF_ADMIN_URL . 'tf-options/assets/js/tf-options.js', array( 'jquery', 'wp-color-picker' ), TOURFIC, true );

			wp_enqueue_script( 'tf-leaflet', esc_url( 'https://cdn.jsdelivr.net/npm/leaflet@' . '1.9' .'/dist/leaflet.js' ), array( 'jquery' ), '1.9', true );
			wp_enqueue_style( 'tf-leaflet', esc_url( 'https://cdn.jsdelivr.net/npm/leaflet@' . '1.9' .'/dist/leaflet.css' ), array(), '1.9' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			
		}


		public function field($field, $value, $settings_id = '', $parent = '') {
            if($field['type'] == 'repeater') {
	            $id = ( ! empty( $settings_id ) ) ? $settings_id . '[' . $field['id'] . '][0]' . '[' . $field['id'] . ']' : $field['id'] . '[0]' . '[' . $field['id'] . ']';
            } else {
	            $id = $settings_id . '[' . $field['id'] . ']';
            }

            $class = isset( $field['class'] ) ? $field['class'] : '';
            $is_pro = isset( $field['is_pro'] ) ? $field['is_pro'] : '';
            $badge_up = isset( $field['badge_up'] ) ? $field['badge_up'] : '';

			if(isset( $field['is_pro'] ) || isset( $field['badge_up'] ) ){ 
				$class .= 'tf-csf-disable tf-csf-pro';
			}
			?>
            <div class="tf-field tf-field-<?php echo esc_attr( $field['type'] ); ?> <?php echo esc_attr( $class ); ?>">
				<?php if ( ! empty( $field['label'] ) ): ?>
                    <label for="<?php echo esc_attr( $id ) ?>" class="tf-field-label">
						<?php echo esc_html( $field['label'] ) ?>
						<?php if($is_pro): ?>
							<div class="tf-csf-badge"><span class="tf-pro"><?php _e( "Pro", "tourfic" ); ?></span></div>
						<?php endif; ?>
						<?php if($badge_up): ?>
							<div class="tf-csf-badge"><span class="tf-upcoming"><?php _e( "Upcoming", "tourfic" ); ?></span></div> 
						<?php endif; ?>
					</label>
				<?php endif; ?>
				<?php if ( ! empty( $field['subtitle'] ) ) : ?>
                    <span class="tf-field-sub-title"><?php echo wp_kses_post( $field['subtitle'] ) ?></span>
				<?php endif; ?>

                <div class="tf-fieldset">
					<?php
					$fieldClass = 'TF_' . $field['type'];
					if ( class_exists( $fieldClass ) ) {
						$_field = new $fieldClass( $field, $value, $settings_id, $parent);
						$_field->render();
					} else {
						echo '<p>' . __( 'Field not found!', 'tourfic' ) . '</p>';
					}
					?>
                </div>
				<?php if ( ! empty( $field['description'] ) ): ?>
                    <p class="description"><?php echo wp_kses_post( $field['description'] ) ?></p>
				<?php endif; ?>
            </div>
			<?php
		}

	}
}

TF_Options::instance();