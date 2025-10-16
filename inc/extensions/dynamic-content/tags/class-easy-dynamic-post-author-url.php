<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_Author_Url extends Tag {

    public function get_name() {
        return 'easy-post-author-url-dynamic';
    }

    public function get_title() {
        return __( 'Post Author URL', 'easy-elements-pro' );
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

        $this->add_control(
            'easy_author_archive_url',
            [
                'label' => __( 'Author Url', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'author-url',
                'options' => [
                    'author-url'  => __( 'Author Archive Url', 'easy-elements-pro' ),
                    'author-website-url'  => __( 'Website Url', 'easy-elements-pro' ),
                ],
            ]
        );
    
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    /**
     * Main URL output
     */
    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();
        $post_id  = Easy_Dynamic_Tag_Helper::get_post_id( $settings );

        if ( ! $post_id ) {
            return ! empty( $settings['easydc_fallback'] ) ? esc_url( $settings['easydc_fallback'] ) : '';
        }

        $author_id = get_post_field( 'post_author', $post_id );

        if ( $settings['easy_author_archive_url'] === 'author-website-url' ) {
           
            $author_website = get_the_author_meta( 'user_url', $author_id );
            $url = ! empty( $author_website ) ? $author_website : home_url();
        } else {
            $author_nicename = get_the_author_meta( 'user_nicename', $author_id );
            if ( empty( $author_nicename ) ) {
                $author_nicename = 'admin';
            }
            $url = home_url( '/author/' . $author_nicename . '/' );
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        return esc_url( $before . $url . $after );
    }

    public function render() {
        echo esc_url( $this->get_value() );
    }
}