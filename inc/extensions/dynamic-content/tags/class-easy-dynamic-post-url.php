<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_Url extends Tag {

    public function get_name() {
        return 'easy-post-url-dynamic';
    }

    public function get_title() {
        return __( 'Post URL', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-post-tags';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }

    public function is_settings_required() {
		return false;
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
     * Main output (URL value)
     */
    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();

        $post_id  = Easy_Dynamic_Tag_Helper::get_post_id( $settings );

        if ( ! $post_id ) {
            return;
        }

        if ( ! $post_id || get_post_status( $post_id ) !== 'publish' ) {
            return ! empty( $settings['easydc_fallback'] ) ? esc_url( $settings['easydc_fallback'] ) : '';
        }

        $url = get_permalink( $post_id );

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        return esc_url( $before . $url . $after );
    }

    /**
     * Render method for preview or frontend
     */
    public function render() {
        echo esc_url( $this->get_value() );
    }


}