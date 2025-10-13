<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Site_Tagline extends Tag {

    public function get_name() {
        return 'easy-site-tagline-dynamic';
    }

    public function get_title() {
        return __( 'Site Tagline', 'easy-elements-pro' );
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
       
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    /**
     * Frontend render
     */
    public function render(): void {
        $settings = $this->get_settings_for_display();
        $tagline = $this->get_site_tagline();

        $before = ! empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = ! empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        $tagline = easy_apply_word_limit( $tagline, $settings );

        echo wp_kses_post( $before . $tagline . $after );
    }

    /**
     * Editor live preview
     */
    public function get_value_for_editor() {
        return $this->get_site_tagline();
    }

    /**
     * Core function to get site tagline
     */
    protected function get_site_tagline(): string {
        return get_bloginfo( 'description' ) ?: '';
    }
}
