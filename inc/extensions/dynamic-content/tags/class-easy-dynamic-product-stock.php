<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Stock extends Tag {

    public function get_name() {
        return 'easy-product-stock-dynamic';
    }

    public function get_title() {
        return __( 'Product Stock', 'easy-elements-pro' );
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
            'stock_type',
            [
                'label' => esc_html__('Stock Type', 'easy-elements-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'status',
                'options' => [
                    'quantity' => esc_html__('Stock Quantity', 'easy-elements-pro'),
                    'status'   => esc_html__('Stock Status', 'easy-elements-pro'),
                ],
            ]
        );

        $this->add_control(
            'low_stock_threshold',
            [
                'label' => esc_html__('Low Stock Threshold', 'easy-elements-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'description' => esc_html__('Show a warning if stock quantity is below this number.', 'easy-elements-pro'),
                'condition' => [
                    'stock_type' => 'quantity',
                ],
            ]
        );

         $this->add_control(
            'in_stock_label',
            [
                'label' => esc_html__('In Stock Label', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'In Stock', 'easy-elements-pro' ),
                'condition' => [
                    'stock_type' => 'status',
                ],
                'ai'    => false,
            ]
        );

        $this->add_control(
            'out_of_stock_label',
            [
                'label' => esc_html__('Out of Stock Label', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Out of Stock', 'easy-elements-pro' ),
                'condition' => [
                    'stock_type' => 'status',
                ],
                'ai'    => false,
            ]
        );

        $this->add_control(
            'backorder_label',
            [
                'label' => esc_html__('Backorder Label', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'On Backorder', 'easy-elements-pro' ),
                'condition' => [
                    'stock_type' => 'status',
                ],
                'ai'    => false,
            ]
        );

       
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        $product_id = ! empty( $settings['easy_product_data'] ) ? intval( $settings['easy_product_data'] ) : get_the_ID();
        if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
            return;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) return;

        $output = '';

        switch ( $settings['stock_type'] ) {
            case 'quantity':
                $stock_qty = $product->get_stock_quantity();
                if ( $stock_qty === null ) {
                    $output = __( 'Unlimited', 'easy-elements-pro' );
                } else {
                    $output = $stock_qty;
                    if ( ! empty( $settings['low_stock_threshold'] ) && $stock_qty < intval($settings['low_stock_threshold']) ) {
                        $output .= ' (' . esc_html__('Low Stock', 'easy-elements-pro') . ')';
                    }
                }
                break;

            case 'status':
                if ( $product->is_in_stock() ) {
                    $output = ! empty( $settings['in_stock_label'] ) ? $settings['in_stock_label'] : __( 'In Stock', 'easy-elements-pro' );
                } else {
                    $output = ! empty( $settings['out_of_stock_label'] ) ? $settings['out_of_stock_label'] : __( 'Out of Stock', 'easy-elements-pro' );
                }

                if ( $product->is_on_backorder( 1 ) ) {
                    $output .= ' (' . ( ! empty( $settings['backorder_label'] ) ? $settings['backorder_label'] : __( 'On Backorder', 'easy-elements-pro' ) ) . ')';
                }
                break;
        }

        if ( empty( $output ) && ! empty( $settings['easydc_fallback'] ) ) {
            $output = $settings['easydc_fallback'];
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $output . $after );
    }

}