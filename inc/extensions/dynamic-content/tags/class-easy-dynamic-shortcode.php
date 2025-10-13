<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Shortcode extends Tag {

    public function get_name() {
        return 'easy-site-shortcode-dynamic';
    }

    public function get_title() {
        return __( 'Shortcode', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-sitetitle-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
        ];
    }

    public function is_settings_required() {
        return true;
    }

    protected function register_controls() {
        $this->add_control(
            'easy_dynamic_shortcode',
            [
                'label' => esc_html__('Shortcode', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
                'ai' => [
                    'active' => false,
                ]
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

     /**
     * Frontend render
     */
    public function render(): void {
        $settings = $this->get_settings_for_display();
        $shortcode = trim( $settings['easy_dynamic_shortcode'] ?? '' );

        if ( empty( $shortcode ) ) {
            return;
        }

        $output = do_shortcode( $shortcode );

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $output . $after );
    }

    /**
     * Editor live preview
     */
    public function get_value_for_editor() {
        $settings = $this->get_settings_for_display();
        $shortcode = trim( $settings['easy_dynamic_shortcode'] ?? '' );

        if ( empty( $shortcode ) ) {
            return '';
        }

        return do_shortcode( $shortcode );
    }
}
