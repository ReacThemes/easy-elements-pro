<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Elements_Register_Category {

	const VERSION = '1.0.0';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}


	public function init() {
		// Safety checks
		
		add_action( 'elementor/elements/categories_registered', [ $this, 'easy_elementor_categories' ], 50 );

	}

	
	public function easy_elementor_categories( $elements_manager ) {
		$elements_manager->add_category( 'easyelements_category_pro', [
			'title' => esc_html__( 'Easy Elements Pro', 'easy-elements-pro' ),
			'icon'  => 'fa fa-plug',
		] );
	}


}

Easyel_Elements_Register_Category::instance();