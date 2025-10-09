<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_CustomField extends Tag {

    public function get_name() {
        return 'easy-post-customfield-dynamic';
    }

    public function get_title() {
        return __( 'Post Custom Field ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-post-tags';
    }

    private function get_custom_field_keys() {
        $post_id = get_the_ID();

        if ( ! $post_id ) {
            return [ '' => __( 'No post context found', 'easy-elements-pro' ) ];
        }

        $keys = get_post_custom_keys( $post_id );
        $options = [];
        $options[''] = __( '— Select —', 'easy-elements-pro' );

        if ( ! empty( $keys ) && is_array( $keys ) ) {
            foreach ( $keys as $key ) {
                if ( strpos( $key, '_' ) === 0 ) continue;
                $options[ $key ] = $key;
            }
        }

        if ( empty( $options ) ) {
            $options[''] = __( 'No public custom fields found', 'easy-elements-pro' );
        }

        return $options;
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
     * Register controls (popup settings)
     */
    protected function register_controls() {
        easy_general_settings_post( $this );

        $this->add_control(
            'easy_custom_field_key',
            [
                'label'       => __( 'Select Meta Key', 'easy-elements-pro' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_custom_field_keys(),
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'easy_custom_field_manual',
            [
                'label'       => __( 'Or Enter Custom Key', 'easy-elements-pro' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'e.g. my_custom_meta', 'easy-elements-pro' ),
                'ai'          => false,
                'dynamic'     => [ 'active' => false ],
                'label_block' => true,
                'condition'   => [
                    'easy_custom_field_key' => '',
                ],
            ]
        );
       

    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        $post_id = Easy_Dynamic_Tag_Helper::get_post_id( $settings );
        if ( ! $post_id ) return;

        $meta_key = $settings['easy_custom_field_manual'] ?: $settings['easy_custom_field_key'];
        if ( empty( $meta_key ) ) return;

        $value = get_post_meta( $post_id, $meta_key, true );
        echo wp_kses_post( $value );
    }

}