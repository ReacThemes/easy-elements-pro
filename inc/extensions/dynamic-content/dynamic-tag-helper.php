<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Dynamic_Tag_Helper {

    /**
     * Get Post ID based on settings
     *
     * @param array $settings Elementor control settings
     * @return int|null
     */
    public static function get_post_id( $settings = [] ) {
        // Check if user selected a specific post
        if ( isset($settings['easy_selected_post_type']) && $settings['easy_selected_post_type'] === 'selected' && !empty($settings['dynamic_post']) ) {
            $post_id = is_array($settings['dynamic_post']) ? $settings['dynamic_post'][0] : $settings['dynamic_post'];
        } else {
            $post_id = get_the_ID();
        }

        // Use fallback if post ID is empty
        if ( empty($post_id) && !empty($settings['easydc_fallback']) ) {
            $post_id = $settings['easydc_fallback'];
        }

        return $post_id ?: null;
    }

}
