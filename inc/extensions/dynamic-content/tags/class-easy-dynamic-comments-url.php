<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Comments_Url extends Tag {

    public function get_name() {
        return 'easy-comments-url-dynamic';
    }

    public function get_title() {
        return __( 'Comments URL ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-comments-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
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

        
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

     /**
     * Get the dynamic comments URL
     */
    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();
        $post_id  = Easy_Dynamic_Tag_Helper::get_post_id( $settings );

        if ( ! $post_id ) {
            return '';
        }

        $permalink = get_permalink( $post_id );
        if ( ! $permalink ) {
            return '';
        }

        $comments_link = $permalink . '#comments';

        return esc_url( $comments_link );
    }

    /**
     * Render output
     */
    public function render() {
        $settings = $this->get_settings_for_display();
        $output   = $this->get_value();

        if ( empty( $output ) && ! empty( $settings['easydc_fallback'] ) ) {
            $output = esc_url( $settings['easydc_fallback'] );
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $output . $after );
    }

}