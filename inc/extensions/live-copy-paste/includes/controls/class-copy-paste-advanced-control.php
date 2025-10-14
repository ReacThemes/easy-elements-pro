<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Live_Copy_Paste_Section {

	public static function register_controls( $element ) {
		
		$element->add_control(
			'easy_enable_copy_paste',
			[
				'label'        => __( 'Enable Live Copy', 'easy-elements-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'easy-elements-pro' ),
				'label_off'    => __( 'Off', 'easy-elements-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

	}
}