<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Url extends Tag {

    public function get_name() {
        return 'easy-product-url-dynamic';
    }

    public function get_title() {
        return __( 'Product Url', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-wooproduct-tag';
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
       
        $this->add_control(
            'easy_product_data',
            [
                'label' => esc_html__('Select Product', 'easy-elements-pro'),
                'type' => 'easy-dynamic-select',
                'multiple' => false,
                'label_block' => true,
                'query_args' => [
                    'query'     => 'posts',
                    'post_type' => 'product',
                    'tax_query' => [
                        [
                            'taxonomy' => 'product_type',
                            'field'    => 'slug',
                            'terms'    => 'simple',
                        ],
                    ],
                ],
            ]
        );

    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    /**
     * Return the product URL
     */
    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();

        $product_id = ! empty( $settings['easy_product_data'] ) ? intval( $settings['easy_product_data'] ) : get_the_ID();

        if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
           
            return ! empty( $settings['easydc_fallback'] ) ? esc_url( $settings['easydc_fallback'] ) : '';
        }

        $url = get_permalink( $product_id );

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        return esc_url( $before . $url . $after );
    }

    /**
     * Render method for preview or frontend
     */
    public function render() {
        $settings = $this->get_settings_for_display();
        $output = $this->get_value();

        if ( empty( $output ) && ! empty( $settings['easydc_fallback'] ) ) {
            $output = esc_url( $settings['easydc_fallback'] );
        }

        echo wp_kses_post( $output );
    }

}