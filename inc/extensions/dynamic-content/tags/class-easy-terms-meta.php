<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Terms_Meta extends Tag {

    public function get_name() {
        return 'easy-meta-terms-dynamic';
    }

    public function get_title() {
        return __( 'Terms Meta', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-terms-tag';
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
        easy_get_terms_data_info( $this );
        $this->add_control(
            'easy_terms_meta_key',
            [
                'label'       => __( 'Meta Key', 'easy-elements-pro' ),
                'type'        => Controls_Manager::TEXT,
                'description' => __( 'Enter term meta key', 'easy-elements-pro' ),
                'condition' => ['easy_terms_data!' => '']
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

   public function render() {
        $settings = $this->get_settings_for_display();

        $post_id = Easy_Dynamic_Tag_Helper::get_post_id( $settings );
        if ( ! $post_id ) {
            return;
        }

        $term_id = ! empty( $settings['easy_terms_data'] ) ? intval( $settings['easy_terms_data'] ) : 0;
        if ( ! $term_id ) {
            return;
        }

        $term = get_term( $term_id );
        if ( ! $term || is_wp_error( $term ) ) {
            if ( ! empty( $settings['easydc_fallback'] ) ) {
                echo wp_kses_post( $settings['easydc_fallback'] );
            }
            return;
        }

        // Get the meta key from settings
        $meta_key = ! empty( $settings['easy_terms_meta_key'] ) ? sanitize_text_field( $settings['easy_terms_meta_key'] ) : '';
        $meta_value = $meta_key ? get_term_meta( $term_id, $meta_key, true ) : '';

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $meta_value . $after );
    }

    /**
     * Editor preview
     */
    public function get_value_for_editor() {
        $settings = $this->get_settings_for_display();

        $term_id = ! empty( $settings['easy_terms_data'] ) ? intval( $settings['easy_terms_data'] ) : 0;
        if ( ! $term_id ) {
            return '';
        }

        $term = get_term( $term_id );
        if ( ! $term || is_wp_error( $term ) ) {
            return ! empty( $settings['easydc_fallback'] ) ? $settings['easydc_fallback'] : '';
        }

        $meta_key = ! empty( $settings['easy_terms_meta_key'] ) ? sanitize_text_field( $settings['easy_terms_meta_key'] ) : '';
        $meta_value = $meta_key ? get_term_meta( $term_id, $meta_key, true ) : '';

        return $meta_value;
    }
}