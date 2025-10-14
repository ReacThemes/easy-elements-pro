<?php
namespace EasyElements\Includes\Settings;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Easy_Settings_Live_Copy extends Tab_Base {

	public function get_id() {
		return 'easy-settings-live-copy';
	}

	public function get_title() {
		return __( 'Easy Live Copy Paste', 'easy-elements-pro' );
	}

	public function get_group() {
		return 'settings';
	}

	public function get_icon() {
		return 'eicon-copy';
	}

	protected function register_tab_controls() {

		$this->start_controls_section(
			'section_live_copy_settings',
			[
				'label' => __( 'Easy Live Copy Paste', 'easy-elements-pro' ),
				'tab'   => 'easy-settings-live-copy',
			]
		);

		$this->add_control(
			'enable_editor_copy_paste',
			[
				'label'        => __( 'Enable Editor Copy Paste', 'easy-elements-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements-pro' ),
				'label_off'    => __( 'No', 'easy-elements-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_frontend_copy',
			[
				'label'        => __( 'Enable Frontend Copy', 'easy-elements-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements-pro' ),
				'label_off'    => __( 'No', 'easy-elements-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();
	}
}