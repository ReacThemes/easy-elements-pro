<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Shipping extends Tag {

    public function get_name() {
        return 'easy-product-shipping-dynamic';
    }

    public function get_title() {
        return __( 'Product Shipping', 'easy-elements-pro' );
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

        $this->add_control(
            'shipping_field',
            [
                'label' => esc_html__('Shipping Field', 'easy-elements-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'weight',
                'options' => [
                    'weight' => esc_html__('Weight', 'easy-elements-pro'),
                    'dimensions' => esc_html__('Dimensions (L x W x H)', 'easy-elements-pro'),
                    'shipping_class' => esc_html__('Shipping Class', 'easy-elements-pro'),
                ],
            ]
        );
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

        $output = '';

        switch ( $settings['shipping_field'] ) {
            case 'weight':
                $weight = $product->get_weight();
                if ( $weight ) {
                    $output = wc_format_decimal( $weight ) . ' ' . get_option( 'woocommerce_weight_unit' );
                }
                break;

            case 'dimensions':
                $length = $product->get_length();
                $width  = $product->get_width();
                $height = $product->get_height();
                if ( $length || $width || $height ) {
                    $unit = get_option( 'woocommerce_dimension_unit' );
                    $output = implode( ' x ', array_filter([$length, $width, $height]) ) . ' ' . $unit;
                }
                break;

            case 'shipping_class':
                $shipping_class_id = $product->get_shipping_class_id();
                if ( $shipping_class_id ) {
                    $shipping_class_term = get_term( $shipping_class_id );
                    if ( $shipping_class_term && ! is_wp_error( $shipping_class_term ) ) {
                        $output = $shipping_class_term->name;
                    }
                }
                break;
        }

        // Fallback
        if ( empty( $output ) && ! empty( $settings['easydc_fallback'] ) ) {
            $output = $settings['easydc_fallback'];
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $output . $after );
    }

}