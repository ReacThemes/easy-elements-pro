<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Content extends Tag {

    public function get_name() {
        return 'easy-product-content-dynamic';
    }

    public function get_title() {
        return __( 'Product Content', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-wooproduct-tag';
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
        easy_get_product_title_search( $this );

       
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        $product_id = ! empty( $settings['easy_product_data'] ) ? intval( $settings['easy_product_data'] ) : 0;

        if ( ! $product_id ) {
            $product_id = get_the_ID();
        }

        if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
            return;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return;
        }

        $content = $product->get_description();

        $content = easy_apply_word_limit( $content, $settings );

        if ( empty( $content ) && ! empty( $settings['easydc_fallback'] ) ) {
            $content = $settings['easydc_fallback'];
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $content . $after );
    }

}