<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Archive_Meta extends Tag {

    public function get_name() {
        return 'easy-archive-meta-dynamic';
    }

    public function get_title() {
        return __( 'Archive Meta', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-archive-tag';
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
        $this->add_control(
            'easy_meta_key',
            [
                'label' => esc_html__('Meta Key', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
              
                'ai' => [
                    'active' => false,
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
    public function render(): void {
        $settings = $this->get_settings_for_display();

        // Get the meta key from the control
        $key = ! empty( $settings['easy_meta_key'] ) ? sanitize_text_field( $settings['easy_meta_key'] ) : '';

        if ( empty( $key ) ) {
            return;
        }

        $meta_value = '';
        $queried_object = get_queried_object();

        if ( is_category() || is_tag() || is_tax() ) {
            $term_id = get_queried_object_id();
            $meta_value = get_term_meta( $term_id, $key, true );
        }
       
        elseif ( is_author() && $queried_object ) {
            $author_id = $queried_object->ID ?? 0;
            $meta_value = get_user_meta( $author_id, $key, true );
        }
       
        elseif ( $queried_object && isset( $queried_object->ID ) ) {
            $meta_value = get_post_meta( $queried_object->ID, $key, true );
        }

        if ( is_array( $meta_value ) ) {
            $meta_value = implode( ', ', $meta_value );
        }

        if ( empty( $meta_value ) && ! empty( $settings['easydc_fallback'] ) ) {
            $meta_value = $settings['easydc_fallback'];
        }

        // Apply word limit

        $description = easy_apply_word_limit( $meta_value, $settings );

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $meta_value . $after );
    }


}