<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Add_ToCart extends Tag {

    public function get_name() {
        return 'easy-product-addtocart-dynamic';
    }

    public function get_title() {
        return __( 'Add To Cart', 'easy-elements-pro' );
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
			'easy_quantity',
			[
				'label' => esc_html__( 'Quantity', 'easy-elements-pro' ),
				'type' => Controls_Manager::TEXT,
				'ai' => [
					'active' => false,
				],
				'default' => 1,
			]
		);
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

     /**
     * Return Add To Cart URL (for Dynamic Tag output)
     */
    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();

        $product_id = ! empty( $settings['easy_product_data'] ) ? intval( $settings['easy_product_data'] ) : 0;
        if ( ! $product_id ) {
            $product_id = get_the_ID();
        }

        if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
            return '';
        }

        $quantity = ! empty( $settings['easy_quantity'] ) ? absint( $settings['easy_quantity'] ) : 1;

        $redirect_url = 'yes' === get_option( 'woocommerce_cart_redirect_after_add' )
            ? wc_get_cart_url()
            : get_permalink( $product_id );

        $add_to_cart_url = home_url() . '?add-to-cart=' . $product_id . '&quantity=' . $quantity . '&e-redirect=' . $redirect_url;

        return esc_url( $add_to_cart_url );
    }

    /**
     * Optional: render() only needed for debug or preview
     */
    public function render() {
        echo esc_url( $this->get_value() );
    }

}