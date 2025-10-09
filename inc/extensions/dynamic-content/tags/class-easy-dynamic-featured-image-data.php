<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Featured_Image_Data extends Tag {

    public function get_name() {
        return 'easy-media-featureimg-dynamic';
    }

    public function get_title() {
        return __( 'Post Feature Image', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-media-tag';
    }

    public function get_categories() {
        return [
           \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
		    \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
        ];
    }

    public function is_settings_required() {
		return true;
	}

     /**
     * Available image fields
     */
    protected function get_image_fields() {
        return [
            'url'             => __( 'Image URL', 'easy-elements-pro' ),
            'attachment_url'  => __( 'Attachment URL', 'easy-elements-pro' ),
            'alt'             => __( 'Alt Text', 'easy-elements-pro' ),
            'title'           => __( 'Title', 'easy-elements-pro' ),
            'caption'         => __( 'Caption', 'easy-elements-pro' ),
            'description'     => __( 'Description', 'easy-elements-pro' ),
        ];
    }

    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        easy_general_settings_post( $this );

        $this->add_control(
            'image_field',
            [
                'label'   => __( 'Select Field', 'easy-elements-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'url',
                'options' => $this->get_image_fields(),
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

        $field = $settings['image_field'] ?? 'url';
        $thumb_id = get_post_thumbnail_id( $post_id );

        if ( ! $thumb_id ) return;

        $value = '';

        switch ( $field ) {
            case 'url':
                $value = wp_get_attachment_url( $thumb_id );
                break;

            case 'attachment_url':
                $value = get_attachment_link( $thumb_id );
                break;

            case 'alt':
                $value = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
                break;

            case 'title':
                $value = get_the_title( $thumb_id );
                break;

            case 'caption':
                $value = wp_get_attachment_caption( $thumb_id );
                break;

            case 'description':
                $desc_post = get_post( $thumb_id );
                if ( $desc_post ) $value = $desc_post->post_content;
                break;
        }

        if ( $value ) {
            echo wp_kses_post( $value );
        }
    }

}
