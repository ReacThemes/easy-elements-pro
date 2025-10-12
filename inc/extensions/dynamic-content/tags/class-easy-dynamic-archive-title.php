<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Archive_Title extends Tag {

    public function get_name() {
        return 'easy-archive-title-dynamic';
    }

    public function get_title() {
        return __( 'Archive Title ', 'easy-elements-pro' );
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
       
       $this->add_control(
            'easy_include_context',
            [
                'label'        => __( 'Include Context (e.g. "Category:", "Author:")', 'easy-elements-pro' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements-pro' ),
                'label_off'    => __( 'No', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    /**
     * Render output
     */
    public function render() {
        $settings = $this->get_settings_for_display();
        $include_context = !empty( $settings['easy_include_context'] ) && $settings['easy_include_context'] === 'yes';
        $title = '';

        $queried_object = get_queried_object();

        // Helper function to handle include_context formatting
        $format_title = function( $prefix, $value ) use ( $include_context ) {
            return $include_context ? sprintf( '%s: %s', $prefix, $value ) : $value;
        };

        if ( isset( $GLOBALS['wp_query']->loop_term ) && is_object( $GLOBALS['wp_query']->loop_term ) ) {
            echo wp_kses_post( $GLOBALS['wp_query']->loop_term->name );
            return;
        }

        if ( is_category() || is_tag() || is_tax() ) {
            $title = $include_context ? get_the_archive_title() : single_term_title( '', false );

        } elseif ( is_author() ) {
            $title = $format_title( 'Author', get_the_author() );

        } elseif ( is_post_type_archive() ) {
            $title = $format_title( 'Archives', post_type_archive_title( '', false ) );

        } elseif ( is_date() ) {
            if ( is_year() ) {
                $title = $format_title( 'Year', get_the_date( 'Y' ) );
            } elseif ( is_month() ) {
                $title = $format_title( 'Month', get_the_date( 'F Y' ) );
            } elseif ( is_day() ) {
                $title = $format_title( 'Day', get_the_date() );
            }

        } elseif ( is_search() ) {
            $title = $format_title( 'Search Results for', get_search_query() );

        } elseif ( $queried_object && isset( $queried_object->post_type ) ) {
            $post_title = get_the_title( $queried_object->ID );
            $prefix_map = [
                'post' => 'Post',
                'page' => 'Page',
            ];
            $prefix = $prefix_map[$queried_object->post_type] ?? 'Item';
            $title = $format_title( $prefix, $post_title );

        } elseif ( is_home() || is_front_page() ) {
            $title = $format_title( 'Home', get_bloginfo( 'name' ) );

        } else {
            $title = get_the_archive_title();
            if ( ! $include_context ) {
                $title = preg_replace( '/^[^:]+:\s*/', '', $title );
            }
        }

        echo wp_kses_post( $title );
    }

}