<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Visibility_Module {

	private static $instance = null;

	private $prefix = 'easy_';

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		$tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_visibility_control = isset( $extensions_settings['enable_visibility_control'] ) ? $extensions_settings['enable_visibility_control'] : 0;

        if(  (int) $enable_visibility_control !== 1 ) {
            return;
        }

		add_filter( 'elementor/frontend/widget/should_render', [ $this, 'easy_check_visibility' ], 10, 2 );
		add_filter( 'elementor/frontend/section/should_render', [ $this, 'easy_check_visibility' ], 10, 2 );
		add_filter( 'elementor/frontend/column/should_render', [ $this, 'easy_check_visibility' ], 10, 2 );
		add_filter( 'elementor/frontend/container/should_render', [ $this, 'easy_check_visibility' ], 10, 2 );

		add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'easy_register_section'] );
		add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'easy_register_section'] );
		add_action('elementor/element/common/easy_visibility_section/before_section_end', [ $this, 'easy_register_controls'], 10, 2 );
 		add_action('elementor/element/section/easy_visibility_section/before_section_end', [ $this, 'easy_register_controls'], 10, 2 );

		add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'easy_register_section'] );

		add_action('elementor/element/container/easy_visibility_section/before_section_end', [ $this, 'easy_register_controls'], 10, 2 );
	
	}

	public function easy_register_section( $element ) {
		$element->start_controls_section(
			'easy_visibility_section',
			[
				'label' => __( 'Easy Visibility Control', 'easy-elements-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Register visibility controls in Elementor panel
	 */
	public function easy_register_controls( $element, $args ) {

		require_once EASYELEMENTS_PRO_PATH . 'inc/extensions/visibility-control/controls/class-visibility-section.php';
		Easy_Visibility_Section::register_controls( $element );
	}

	/**
	 * Check visibility conditions before rendering
	 */
	public function easy_check_visibility( $should_render, $element ) {

		$settings = $element->get_settings_for_display();

		if ( empty( $settings[ $this->prefix . 'enable_visibility' ] ) || $settings[ $this->prefix . 'enable_visibility' ] !== 'yes' ) {
			return $should_render;
		}

		$logic      = $settings[ $this->prefix . 'display_on' ] ?? 'all'; 
		$conditions = $settings[ $this->prefix . 'conditions' ] ?? [];

		if ( empty( $conditions ) ) {
			return $should_render;
		}

		$results = [];

		foreach ( $conditions as $condition ) {
			$visible = true;
			$type    = $condition['type'] ?? '';
			$compare = $condition['compare'] ?? '';

			$value_field = 'value_' . $type;
			$value = $condition[ $value_field ] ?? '';

			// --- Login Status ---
			if ( $type === 'login_status' ) {
				$is_logged_in = is_user_logged_in();

				if ( $compare === 'is' && $value === 'logged_in' && ! $is_logged_in ) {
					$visible = false;
				}
				
				if ( $compare === 'is_not' && $value === 'logged_in' && $is_logged_in ) {
					$visible = false;
				}
			}

			// --- User Role ---
			if ( $type === 'user_role' && is_user_logged_in() ) {
				$user = wp_get_current_user();
				$has_role = in_array( $value, (array) $user->roles, true );

				if ( $compare === 'is' && ! $has_role ) {
					$visible = false;
				}
				if ( $compare === 'is_not' && $has_role ) {
					$visible = false;
				}
			}

			// --- Operating System ---
			if ( $type === 'os' ) {
				$user_os = $this->get_user_os();

				if ( $compare === 'is' && ! in_array( $user_os, (array) $value, true ) ) {
					$visible = false;
				}
				if ( $compare === 'is_not' && in_array( $user_os, (array) $value, true ) ) {
					$visible = false;
				}
			}

			// --- Browser ---
			if ( $type === 'browser' ) {
				$user_browser = $this->get_user_browser();

				error_log( print_r( $user_browser, true ) );

				if ( $compare === 'is' && ! in_array( $user_browser, (array) $value, true ) ) {
					$visible = false;
				}
				if ( $compare === 'is_not' && in_array( $user_browser, (array) $value, true ) ) {
					$visible = false;
				}
			}

			// --- Day of Week ---
			if ( $type === 'day' ) {
				$today = date( 'l' );

				if ( $compare === 'is' && ! in_array( $today, (array) $value, true ) ) {
					$visible = false;
				}
				if ( $compare === 'is_not' && in_array( $today, (array) $value, true ) ) {
					$visible = false;
				}
			}

			// --- Date ---
			if ( $type === 'date' && ! empty( $value ) ) {
				$today = date( 'Y-m-d' );
				if ( $compare === 'is' && $today !== $value ) {
					$visible = false;
				}
				if ( $compare === 'is_not' && $today === $value ) {
					$visible = false;
				}
			}

			// --- Time ---
			if ( $type === 'time' && ! empty( $value ) ) {
				
				$now = current_time('H:i');
				$input_time = $value;

				if ( $compare === 'is' && $now !== $input_time ) {
					$visible = false;
				}
				if ( $compare === 'is_not' && $now === $input_time ) {
					$visible = false;
				}
			}

			$results[] = $visible;
		}

		$final_visible = ( $logic === 'any' )
			? in_array( true, $results, true )
			: ! in_array( false, $results, true );

		return $final_visible ? $should_render : false;
	}

	/**
	 * user agent function..
	 */
	private function get_user_agent() {
		return $_SERVER['HTTP_USER_AGENT'] ?? '';
	}

	/**
	 * Helper: detect user OS
	 */
	private function get_user_os() {
		$agent = $this->get_user_agent();
		$os = 'Unknown';

		switch (true) {
			case stripos($agent, 'Windows') !== false:
				$os = 'Windows';
				break;

			case stripos($agent, 'Mac') !== false:
				$os = 'Mac';
				break;

			case stripos($agent, 'Linux') !== false:
				$os = 'Linux';
				break;

			case stripos($agent, 'Android') !== false:
				$os = 'Android';
				break;

			case stripos($agent, 'iPhone') !== false:
			case stripos($agent, 'iPad') !== false:
				$os = 'iOS';
				break;

			default:
				$os = 'Unknown';
				break;
		}

		return $os;
	}

	/**
	 * Helper: detect user Browser
	 */
	private function get_user_browser() {
		$agent = $this->get_user_agent();
		$browser = 'Unknown';

		switch (true) {
			case (stripos($agent, 'Chrome') !== false && stripos($agent, 'Edge') === false):
				$browser = 'Chrome';
				break;

			case (stripos($agent, 'Firefox') !== false):
				$browser = 'Firefox';
				break;

			case (stripos($agent, 'Safari') !== false && stripos($agent, 'Chrome') === false):
				$browser = 'Safari';
				break;

			case (stripos($agent, 'Edge') !== false):
				$browser = 'Edge';
				break;

			case (stripos($agent, 'Opera') !== false || stripos($agent, 'OPR') !== false):
				$browser = 'Opera';
				break;

			case (stripos($agent, 'Trident') !== false):
				$browser = 'IE';
				break;

			default:
				$browser = 'Unknown';
				break;
		}

		return $browser;
	}

}

// Initialize
Easy_Visibility_Module::instance();
