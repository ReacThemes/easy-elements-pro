<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Comments extends Tag {

    public function get_name() {
        return 'easy-comments-number-dynamic';
    }

    public function get_title() {
        return __( 'Comments Number ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-comments';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
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
     * Render output
     */
    public function render() {

        $settings = $this->get_settings_for_display();
        $post_id  = Easy_Dynamic_Tag_Helper::get_post_id( $settings );

        if ( ! $post_id ) {
            return;
        }

        $author_id = get_post_field( 'post_author', $post_id );

        if ( ! $author_id ) {
            return;
        }

        $field = $settings['author_info_field'];
        $custom_meta_key = $settings['author_custom_field'] ?? '';

        // Priority: predefined field → user meta
        $value = '';

        if ( ! empty( $field ) && $field !== 'display_role' ) {
            $user = get_userdata( $author_id );
            if ( $user && isset( $user->$field ) ) {
                $value = $user->$field;
            }
        } elseif ( $field === 'display_role' ) {
            $user = get_userdata( $author_id );
            $roles = $user ? implode( ', ', $user->roles ) : '';
            $value = ucfirst( $roles );
        } 

        echo wp_kses_post( $value );
    }

}