<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Post_Title_Tag extends Tag {

    public function get_name() {
        return 'easy-post-title-dynamic';
    }

    public function get_title() {
        return __( 'Post Title', 'easy-dynamic-tags' );
    }

    public function get_group() {
        return 'easy-post-tags';
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

        if (isset($settings['easy_selected_post_type']) && $settings['easy_selected_post_type'] === 'selected' && !empty($settings['dynamic_post'])) {
            $post_id = is_array($settings['dynamic_post']) ? $settings['dynamic_post'][0] : $settings['dynamic_post'];
        } else {
            $post_id = get_the_ID();
        }

        $title = get_the_title( $post_id );

        $title = easy_apply_word_limit( $title, $settings );

        if ( empty($title) && !empty($settings['easydc_fallback']) ) {
            $title = $settings['easydc_fallback'];
        }

        $before = !empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = !empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $title . $after );
    }

    public function get_value_for_editor() {
        return get_the_title() ?: $this->get_settings('easydc_fallback');
    }

}
