<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Search_Results extends Tag {

    public function get_name() {
        return 'easy-search-result-count-dynamic';
    }

    public function get_title() {
        return __( 'Search Results', 'easy-elements-pro' );
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
        global $wp_query;

        if ( ! is_search() ) {
            return;
        }

        $search_query = get_search_query();
        $found_posts  = intval( $wp_query->found_posts );

        if ( $found_posts > 0 ) {
            $text = sprintf(
                /* translators: %1$s: search term, %2$d: number of results */
                __( 'Search results for "%1$s" — %2$d results found', 'easy-elements-pro' ),
                $search_query,
                $found_posts
            );
        } else {
            $text = sprintf(
                __( 'No results found for "%s"', 'easy-elements-pro' ),
                $search_query
            );
        }

        echo esc_html( $text );
    }

    /**
     * Preview text in Elementor editor
     */
    public function get_value_for_editor() {
        return __( 'Search results for "sample" — 8 results found', 'easy-elements-pro' );
    }

}