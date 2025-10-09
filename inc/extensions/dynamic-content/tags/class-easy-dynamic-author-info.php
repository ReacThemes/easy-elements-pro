<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Author_Info extends Tag {

    public function get_name() {
        return 'easy-post-authorinfo-dynamic';
    }

    public function get_title() {
        return __( 'Author Info ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-author-infotag';
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
     * Return predefined author field options
     */
    private function get_author_info_fields() {
        return [
            ''               => __( 'Select', 'easy-elements-pro' ),
            'display_name'    => __( 'Display Name', 'easy-elements-pro' ),
            'user_login'      => __( 'Username', 'easy-elements-pro' ),
            'user_email'      => __( 'Email', 'easy-elements-pro' ),
            'user_url'        => __( 'Website', 'easy-elements-pro' ),
            'description'     => __( 'Bio / Description', 'easy-elements-pro' ),
            'nickname'        => __( 'Nickname', 'easy-elements-pro' ),
            'first_name'      => __( 'First Name', 'easy-elements-pro' ),
            'last_name'       => __( 'Last Name', 'easy-elements-pro' ),
            'display_role'    => __( 'User Role', 'easy-elements-pro' ),
        ];
    }


    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        get_easy_user_info( $this );

        $this->add_control(
            'author_info_field',
            [
                'label'       => __( 'Select Author Field', 'easy-elements-pro' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_author_info_fields(),
                'default'     => 'display_name',
                'label_block' => true,
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