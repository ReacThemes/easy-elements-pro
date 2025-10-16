<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Type extends Tag {

    public function get_name() {
        return 'easy-product-type-dynamic';
    }

    public function get_title() {
        return __( 'Product Type', 'easy-elements-pro' );
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
            'easy_product_type',
            [
                'label' => __( 'Type Label', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'label',
                'options' => [
                    'name'  => __( 'Name', 'easy-elements-pro' ),
                    'slug'  => __( 'Slug', 'easy-elements-pro' ),
                    'label' => __( 'Label', 'easy-elements-pro' ),
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

        $type_selector = apply_filters( 'product_type_selector', [] );
        $type = $product->get_type();

        $type_labels = [
            'simple'   => __( 'Simple product', 'easy-elements-pro' ),
            'grouped'  => __( 'Grouped product', 'easy-elements-pro' ),
            'external' => __( 'External/Affiliate product', 'easy-elements-pro' ),
            'variable' => __( 'Variable product', 'easy-elements-pro' ),
            'variation'=> __( 'Product Variation', 'easy-elements-pro' ),
            'bundle'   => __( 'Product Bundle', 'easy-elements-pro' ), 
            'composite'=> __( 'Composite Product', 'easy-elements-pro' ),
            'subscription' => __( 'Subscription Product', 'easy-elements-pro' ),
        ];

       
        $type_labels = wp_parse_args( $type_selector, $type_labels );

        switch ( $settings['easy_product_type'] ) {
            case 'name':
                $type_output = ucfirst( $type ); 
                break;

            case 'slug':
                $type_output = $type; 
                break;

            case 'label':
            default:
               
                $type_output = isset( $type_labels[ $type ] ) ? $type_labels[ $type ]  : ucfirst( $type );
                break;
        }

        if ( empty( $type_output ) && ! empty( $settings['easydc_fallback'] ) ) {
            $type_output = $settings['easydc_fallback'];
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $type_output . $after );
    }
}