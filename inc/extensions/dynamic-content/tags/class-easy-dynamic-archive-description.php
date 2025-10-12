<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Archive_Description extends Tag {

    public function get_name() {
        return 'easy-archive-description-dynamic';
    }

    public function get_title() {
        return __( 'Archive Description ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-archive-tag';
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
        
    }

    protected function register_advanced_section() {

       
        easy_advanced_control( $this );
    }

    /**
     * Render output
     */
    public function render() {
        $settings   = $this->get_settings_for_display();
       
        $description = '';

        $queried_object = get_queried_object();

        if ( isset( $GLOBALS['wp_query']->loop_term ) && is_object( $GLOBALS['wp_query']->loop_term ) && ! empty( $GLOBALS['wp_query']->loop_term->description ) ) {
            $description = $GLOBALS['wp_query']->loop_term->description;

        } elseif ( is_category() || is_tag() || is_tax() ) {
            $description = get_the_archive_description();

        } elseif ( is_author() ) {
            $author_id = get_queried_object_id();
            $description = get_the_author_meta( 'description', $author_id );

        } elseif ( is_post_type_archive() ) {
            $description = get_the_archive_description();

        } else {
            $description = get_the_archive_description();
        }

        if ( empty( $description ) && ! empty( $settings['easydc_fallback'] ) ) {
            $description = $settings['easydc_fallback']; 
        }

        $description = easy_apply_word_limit( $description, $settings );

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $description . $after );
    }

}