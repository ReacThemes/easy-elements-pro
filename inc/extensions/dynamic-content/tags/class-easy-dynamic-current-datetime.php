<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Current_DateTime extends Tag {

    public function get_name() {
        return 'easy-current-datetime-dynamic';
    }

    public function get_title() {
        return __( 'Current Date Time', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-sitetitle-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    public function is_settings_required() {
        return true;
    }

    protected function register_controls() {
         $this->add_control(
            'date_format',
            [
                'label' => __( 'Date Format', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => __( 'Default', 'easy-elements-pro' ),
                    'none' => __( 'None', 'easy-elements-pro' ),
                    'F j, Y' => date_i18n('F j, Y'),
                    'Y-m-d' => date_i18n('Y-m-d'),
                    'm/d/Y' => date_i18n('m/d/Y'),
                    'd/m/Y' => date_i18n('d/m/Y'),
                    'd.m.Y' => date_i18n('d.m.Y'),
                    'custom' => __( 'Custom', 'easy-elements-pro' ),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'custom_date_format',
            [
                'label' => __( 'Custom Date Format', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'date_format' => 'custom',
                ],
                'placeholder' => 'F j, Y g:i a',
            ]
        );

        // Time format dropdown
        $this->add_control(
            'time_format',
            [
                'label' => __( 'Time Format', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => __( 'Default', 'easy-elements-pro' ),
                    'none' => __( 'None', 'easy-elements-pro' ),
                    'g:i a' => date_i18n('g:i a'),
                    'g:i A' => date_i18n('g:i A'),
                    'H:i'   => date_i18n('H:i'),
                    
                ],
                'default' => 'default',
                'condition' => [
                    'date_format!' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'custom_time_format',
            [
                'label' => __( 'Custom Time Format', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'time_format' => 'custom',
                    'date_format!' => 'custom',
                ],
                'placeholder' => 'g:i a',
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render(): void {
        $settings = $this->get_settings_for_display();

        // Date
        if ( $settings['date_format'] === 'none' ) {
            $date = '';
        } elseif ( $settings['date_format'] === 'default' ) {
            $date = date_i18n( get_option('date_format') );
        } elseif ( $settings['date_format'] === 'custom' ) {
            $date = date_i18n( $settings['custom_date_format'] );
        } else {
            $date = date_i18n( $settings['date_format'] );
        }

        // Time
        if ( $settings['time_format'] === 'none' ) {
            $time = '';
        } elseif ( $settings['time_format'] === 'default' ) {
            $time = date_i18n( get_option('time_format') );
        } elseif ( $settings['time_format'] === 'custom' ) {
            $time = date_i18n( $settings['custom_time_format'] );
        } else {
            $time = date_i18n( $settings['time_format'] );
        }

        $output = trim( $date . ' ' . $time );

        echo wp_kses_post( $output );
    }

    public function get_value_for_editor() {
        return $this->render();
    }
}
