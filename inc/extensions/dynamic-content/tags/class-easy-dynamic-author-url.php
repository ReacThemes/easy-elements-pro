<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Author_Url extends Tag {

    public function get_name() {
        return 'easy-post-authormeta-dynamic';
    }

    public function get_title() {
        return __( 'Author Url ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-author-infotag';
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
        get_easy_user_info( $this );

        $this->add_control(
            'author_url_type',
            [
                'label' => esc_html__('Url Select Type', 'easy-elements-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'author-archive',
                'options' => [
                    'author-archive' => esc_html__('Author Archive', 'easy-elements-pro'),
                    'website' => esc_html__('Website', 'easy-elements-pro'),
                    'email' => esc_html__('E-Mail', 'easy-elements-pro'),
                ],
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
        if ( ! $post_id ) return;

        $author_id = get_post_field( 'post_author', $post_id );
        if ( ! $author_id ) return;

        $url_type = ! empty( $settings['author_url_type'] ) ? $settings['author_url_type'] : 'author-archive';
        $value    = '';

        switch ( $url_type ) {
            case 'author-archive':
                $value = get_author_posts_url( $author_id );
                break;

            case 'website':
                $value = get_the_author_meta( 'user_url', $author_id );
              
                if ( empty( $value ) ) {
                    $value = home_url();
                }
                break;

            case 'email':
                $email = get_the_author_meta( 'user_email', $author_id );
                if ( $email ) {
                    $value = 'mailto:' . sanitize_email( $email );
                }
                break;
        }

        if ( empty( $value ) && ! empty( $settings['easydc_fallback'] ) ) {
            $value = $settings['easydc_fallback']; 
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post(  $before. $value . $after );
    }

}