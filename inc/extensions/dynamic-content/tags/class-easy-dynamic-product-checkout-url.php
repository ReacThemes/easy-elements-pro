<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Checkout_Url extends Tag {

    public function get_name() {
        return 'easy-product-checkout-url-dynamic';
    }

    public function get_title() {
        return __( 'Checkout Page Url', 'easy-elements-pro' );
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
        $this->add_control(
            'easy_checkout_type',
            [
                'label'   => __( 'Checkout Type', 'easy-elements-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'add-to-cart-checkout',
                'options' => [
                    'add-to-cart-checkout'  => __( 'Add to cart & Checkout', 'easy-elements-pro' ),
                    'direct-checkout'   => __( 'Direct Checkout', 'easy-elements-pro' ),
                ],
                'label_block' => true,
            ]
        );


    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();
        $product_id = ! empty( $settings['easy_product_data'] ) ? intval( $settings['easy_product_data'] ) : 0;

        if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
            return ! empty( $settings['easydc_fallback'] ) ? esc_url( $settings['easydc_fallback'] ) : '';
        }

        $checkout_url = wc_get_checkout_url();
        $quantity = ! empty( $settings['easy_quantity'] ) ? absint( $settings['easy_quantity'] ) : 1;

        if ( ! empty( $settings['easy_checkout_type'] ) && $settings['easy_checkout_type'] === 'add-to-cart-checkout' ) {
           
            $checkout_url = add_query_arg([
                'add-to-cart' => $product_id,
                'quantity'    => $quantity,
            ], $checkout_url);
        } elseif ( ! empty( $settings['easy_checkout_type'] ) && $settings['easy_checkout_type'] === 'direct-checkout' ) {
           
            if ( WC()->cart && ! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product_id ) ) ) {
                WC()->cart->add_to_cart( $product_id, $quantity );
            }
            $checkout_url = wc_get_checkout_url();
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        return esc_url( $before . $checkout_url . $after );
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        $output = $this->get_value();

        if ( empty( $output ) && ! empty( $settings['easydc_fallback'] ) ) {
            $output = esc_url( $settings['easydc_fallback'] );
        }

        echo wp_kses_post( $output );
    }

}