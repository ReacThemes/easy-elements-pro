<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_Date extends Tag {

    public function get_name() {
        return 'easy-post-date-dynamic';
    }

    public function get_title() {
        return __( 'Post Date ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-post-tags';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    public function is_settings_required() {
		return true;
	}

    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        easy_general_settings_post( $this );
        $this->add_control(
            'easy_time_type',
            [
                'label'   => __( 'Date Type', 'easy-elements-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_date',
                'options' => [
                    'post_date'      => __( 'Post Published', 'easy-elements-pro' ),
                    'modified_date'  => __( 'Post Modified ', 'easy-elements-pro' ),
                ],
            ]
        );


        $this->add_control(
            'easy_date_format',
            [
                'label' => esc_html__('Format', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'easy-elements-pro'),
                    'F j, Y' => date_i18n('F j, Y'),   
                    'Y-m-d' => date_i18n('Y-m-d'),    
                    'm/d/Y' => date_i18n('m/d/Y'),    
                    'd/m/Y' => date_i18n('d/m/Y'),  
                    'human' => esc_html__('Human Readable', 'easy-elements-pro'),
                    'custom' => esc_html__('Custom', 'easy-elements-pro'),
                ],
                'default' => 'default',
            ]
        );

        // Optional: Custom time format
        $this->add_control(
            'easy_time_format_custom',
            [
                'label'       => __( 'Date Format', 'easy-elements-pro' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'F j, Y',
                'description' => sprintf(
                    /* translators: %s: WordPress date/time format documentation URL */
                    __( ' <a href="%s" target="_blank">WordPress Date/Time Format Documentation</a>.', 'easy-elements-pro' ),
                    'https://wordpress.org/documentation/article/customize-date-and-time-format/'
                ),
                'condition' => ['easy_date_format' => 'custom' ]
            ]
        );

    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        // Get post ID using common helper
        $post_id = Easy_Dynamic_Tag_Helper::get_post_id( $settings );
        if ( ! $post_id ) return;

        // Determine date type
        $date_type = $settings['easy_time_type'] ?? 'post_date';
        $timestamp = $date_type === 'modified_date'
            ? get_post_modified_time( 'U', false, $post_id )
            : get_post_time( 'U', false, $post_id );

        // Determine format
        if ( ! empty( $settings['easy_time_format_custom'] ) && $settings['easy_date_format'] === 'custom' ) {
            $format = $settings['easy_time_format_custom'];
        } elseif ( $settings['easy_date_format'] === 'default' ) {
            $format = get_option( 'date_format' );
        } elseif ( $settings['easy_date_format'] === 'human' ) {
            
            $format = human_time_diff( $timestamp, current_time('timestamp') ) . ' ' . __( 'ago', 'easy-elements-pro' );
            echo wp_kses_post( $format );
            return;
        } else {
            $format = $settings['easy_date_format'];
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . date_i18n( $format, $timestamp ) . $after );
    }

}