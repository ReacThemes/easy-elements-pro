<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Visibility_Section {

	public static function register_controls( $element ) {

		$element->start_controls_section(
			'easy_visibility_section',
			[
				'label' => __( 'Easy Visibility Control', 'easy-elements-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'easy_enable_visibility',
			[
				'label'        => __( 'Enable Visibility', 'easy-elements-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'easy-elements-pro' ),
				'label_off'    => __( 'Off', 'easy-elements-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$element->add_control(
			'easy_display_on',
			[
				'label'   => __( 'Condition Logic', 'easy-elements-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'all' => __( 'All Conditions Met (AND)', 'easy-elements-pro' ),
					'any' => __( 'Any Condition Met (OR)', 'easy-elements-pro' ),
				],
				'default' => 'all',
				'condition' => [ 'easy_enable_visibility' => 'yes' ],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'type',
			[
				'label'   => __( 'Condition Type', 'easy-elements-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'login_status',
				'options' => [
					'login_status' => __( 'Login Status', 'easy-elements-pro' ),
					'user_role'    => __( 'User Role', 'easy-elements-pro' ),
					'os'           => __( 'Operating System', 'easy-elements-pro' ),
					'browser'      => __( 'Browser', 'easy-elements-pro' ),
					'date'         => __( 'Date', 'easy-elements-pro' ),
					'day'          => __( 'Day of Week', 'easy-elements-pro' ),
					'time'         => __( 'Time', 'easy-elements-pro' ),
				],
			]
		);


		$repeater->add_control(
			'compare',
			[
				'label'   => __( 'Compare', 'easy-elements-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'is',
				'options' => [
					'is'     => __( 'Is', 'easy-elements-pro' ),
					'is_not' => __( 'Is Not', 'easy-elements-pro' ),
				],
			]
		);

		$repeater->add_control(
			'value_login_status',
			[
				'label'   => __( 'Value', 'easy-elements-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'logged_in',
				'options' => [
					'logged_in'  => __( 'Logged In', 'easy-elements-pro' ),
				],
				'condition' => [
					'type' => 'login_status',
				],
			]
		);

		$repeater->add_control(
			'value_user_role',
			[
				'label'   => __( 'Value', 'easy-elements-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'administrator' => __( 'Administrator', 'easy-elements-pro' ),
					'editor'        => __( 'Editor', 'easy-elements-pro' ),
					'author'        => __( 'Author', 'easy-elements-pro' ),
					'subscriber'    => __( 'Subscriber', 'easy-elements-pro' ),
				],
				'condition' => [
					'type' => 'user_role',
				],
			]
		);

		$repeater->add_control(
			'value_os',
			[
				'label'       => __( 'Value', 'easy-elements-pro' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default' => 'Windows',
				'options'     => [
					'Windows' => 'Windows',
					'Mac'     => 'Mac',
					'Linux'   => 'Linux',
					'Android' => 'Android',
					'iOS'     => 'iOS',
				],
				'multiple'    => true,
				'condition'   => [
					'type' => 'os',
				],
			]
		);

		$repeater->add_control(
			'value_browser',
			[
				'label'       => __( 'Value', 'easy-elements-pro' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default' => 'Chrome',
				'options'     => [
					'Chrome'   => 'Chrome',
					'Firefox'  => 'Firefox',
					'Safari'   => 'Safari',
					'Edge'     => 'Edge',
					'Opera'    => 'Opera',
					'IE'       => 'IE',
				],
				'condition'   => [
					'type' => 'browser',
				],
			]
		);

		$repeater->add_control(
			'value_day',
			[
				'label'       => __( 'Value', 'easy-elements-pro' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					'Monday'    => 'Monday',
					'Tuesday'   => 'Tuesday',
					'Wednesday' => 'Wednesday',
					'Thursday'  => 'Thursday',
					'Friday'    => 'Friday',
					'Saturday'  => 'Saturday',
					'Sunday'    => 'Sunday',
				],
				'condition'   => [
					'type' => 'day',
				],
			]
		);

		$repeater->add_control(
			'value_date',
			[
				'label'       => __( 'Date', 'easy-elements-pro' ),
				'type'        => \Elementor\Controls_Manager::DATE_TIME,
				'picker_options' => [
					'enableTime' => false, 
				],
				'condition'   => [
					'type' => 'date',
				],
			]
		);

		$repeater->add_control(
			'value_time',
			[
				'label'       => __( 'Time', 'easy-elements-pro' ),
				'type'        => \Elementor\Controls_Manager::DATE_TIME,
				'picker_options' => [
					'noCalendar' => true,   
					'enableTime' => true, 
					'time_24hr'  => false,
				],
				'condition'   => [
					'type' => 'time',
				],
			]
		);

		$element->add_control(
			'easy_conditions',
			[
				'label'       => __( 'Conditions', 'easy-elements-pro' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ type }}}',
				'condition'   => [ 'easy_enable_visibility' => 'yes' ],
			]
		);

		$element->end_controls_section();
	}
}
