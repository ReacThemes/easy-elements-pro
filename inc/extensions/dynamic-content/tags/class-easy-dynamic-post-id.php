<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_ID extends Tag {

    public function get_name() {
        return 'easy-post-id-dynamic';
    }

    public function get_title() {
        return __( 'Post ID ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-post-tags';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
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
     * Render output
     */
    public function render() {
        $settings = $this->get_settings_for_display();

        $post_id = Easy_Dynamic_Tag_Helper::get_post_id( $settings );

        if ( ! $post_id ) return;

        $before = !empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = !empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $post_id . $after );
    }

    public function get_value_for_editor() {
        return get_the_ID() ?: $this->get_settings('easydc_fallback');
    }

}
