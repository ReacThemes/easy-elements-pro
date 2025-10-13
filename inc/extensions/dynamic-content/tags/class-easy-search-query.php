<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Search_Query extends Tag {

    public function get_name() {
        return 'easy-search-query-dynamic';
    }

    public function get_title() {
        return __( 'Search Query', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-search-tag';
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
     * Render frontend output
     */
    public function render(): void {
        // WordPress- current search query
        $search_query = get_search_query();
        $settings = $this->get_settings_for_display();

        $search_title = easy_apply_word_limit(  $search_query, $settings );

        if ( ! empty( $search_title ) ) {
            echo esc_html( $search_title );
        }
    }

}