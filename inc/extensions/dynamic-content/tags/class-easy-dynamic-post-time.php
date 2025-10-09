<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_Time extends Tag {

    public function get_name() {
        return 'easy-post-time-dynamic';
    }

    public function get_title() {
        return __( 'Post Time ', 'easy-elements-pro' );
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
                'label'   => __( 'Time Type', 'easy-elements-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_time',
                'options' => [
                    'post_time'      => __( 'Post Time', 'easy-elements-pro' ),
                    'modified_time'  => __( 'Post Modified Time', 'easy-elements-pro' ),
                ],
            ]
        );

        $this->add_control(
            'easy_time_format',
            [
                'label'       => __( 'Time Format', 'easy-elements-pro' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => [
                    'default'      => 'Default',
                    'g:i a'    => '6:01 am',
                    'g:i A'    => '6:01 AM',
                    'H:i'    => '06:01',
                    'custom'    => 'Custom',
                ],
                'default'     => 'default',
            ]
        );

        // Optional: Custom time format
        $this->add_control(
            'easy_time_format_custom',
            [
                'label'       => __( 'Time Format', 'easy-elements-pro' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'g:i:a',
                'description' => sprintf(
                    /* translators: %s: WordPress date/time format documentation URL */
                    __( ' <a href="%s" target="_blank">WordPress Date/Time Format Documentation</a>.', 'easy-elements-pro' ),
                    'https://wordpress.org/documentation/article/customize-date-and-time-format/'
                ),
                'condition' => ['easy_time_format' => 'custom' ]
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

        // Determine time type
        $time_type = $settings['easy_time_type'] ?? 'post_time';
        $timestamp = $time_type === 'modified_time' ? get_post_modified_time('U', false, $post_id) : get_post_time('U', false, $post_id);

        // Determine format
        if ( !empty( $settings['easy_time_format_custom'] ) && $settings['easy_time_format'] === 'custom' ) {
            $format = $settings['easy_time_format_custom'];
        } elseif ( $settings['easy_time_format'] === 'default' ) {
            $format = get_option('time_format');
        } else {
            $format = $settings['easy_time_format'];
        }

        $before = !empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = !empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . date_i18n( $format, $timestamp ) . $after );
    }

    public function get_value_for_editor() {
        return date_i18n( get_option('time_format'), current_time('timestamp') );
    }

}