<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Shop_Url extends Tag {

    public function get_name() {
        return 'easy-product-shop-url-dynamic';
    }

    public function get_title() {
        return __( 'Back to Shop', 'easy-elements-pro' );
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
            'easy_back_to_shop_type',
            [
                'label'   => __( 'Back to Shop URL Type', 'easy-elements-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default-shop',
                'options' => [
                    'default-shop'  => __( 'Default Shop', 'easy-elements-pro' ),
                    'custom-page'   => __( 'Custom Page', 'easy-elements-pro' ),
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'easy_back_to_shop_custom_page',
            [
                'label'       => __( 'Select Page', 'easy-elements-pro' ),
                'type'        => 'easy-dynamic-select',
                'multiple'    => false,
                'label_block' => true,
                'query_args'  => [
                    'query'      => 'posts',
                    'post_type'  => 'page',
                ],
                'condition'   => [
                    'easy_back_to_shop_type' => 'custom-page',
                ],
            ]
        );

    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();
        $url = '';

        if ( ! empty( $settings['easy_back_to_shop_type'] ) && $settings['easy_back_to_shop_type'] === 'custom-page' ) {
           
            $page_id = ! empty( $settings['easy_back_to_shop_custom_page'] ) ? intval( $settings['easy_back_to_shop_custom_page'] ) : 0;
            if ( $page_id ) {
                $url = get_permalink( $page_id );
            }
        } else {
            $url = wc_get_page_permalink( 'shop' );
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        return esc_url( $before . $url . $after );
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