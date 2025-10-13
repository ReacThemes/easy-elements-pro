<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Site_Title extends Tag {

    public function get_name() {
        return 'easy-site-title-dynamic';
    }

    public function get_title() {
        return __( 'Site Title', 'easy-elements-pro' );
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
        $title = $this->get_site_title();

        $before = ! empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = ! empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        $title = easy_apply_word_limit( $title, $settings );

        echo wp_kses_post( $before . $title . $after );
    }

    /**
     * Editor live preview
     */
    public function get_value_for_editor() {
        return $this->get_site_title();
    }

    /**
     * Core function to get site title
     */
    protected function get_site_title(): string {
        return get_bloginfo( 'name' ) ?: '';
    } 
}
