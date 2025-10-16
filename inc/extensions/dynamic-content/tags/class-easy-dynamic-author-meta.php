<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Author_Meta extends Tag {

    public function get_name() {
        return 'easy-post-authormeta-dynamic';
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
     * Register controls (popup settings)
     */
    protected function register_controls() {
        get_easy_user_info( $this );

       $this->add_control(
            'author_meta_manual',
            [
                'label'       => __( 'Author Meta Key', 'easy-elements-pro' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'e.g. twitter_handle', 'easy-elements-pro' ),
                'ai'          => false,
                'dynamic'     => [ 'active' => false ],
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
        if ( ! $post_id ) return;

        $author_id = get_post_field( 'post_author', $post_id );
        if ( ! $author_id ) return;

        $meta_key = ! empty( $settings['author_meta_manual'] )
            ? $settings['author_meta_manual']
            : '';

        if ( empty( $meta_key ) ) return;

        $value = get_user_meta( $author_id, $meta_key, true );

        if ( is_array( $value ) ) {
            $value = implode( ', ', $value );
        }

        if ( empty( $value ) && ! empty( $settings['easydc_fallback'] ) ) {
            $value = $settings['easydc_fallback']; 
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post(  $before. $value . $after );
    }

}