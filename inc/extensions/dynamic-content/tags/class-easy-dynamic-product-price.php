<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Price extends Tag {

    public function get_name() {
        return 'easy-product-price-dynamic';
    }

    public function get_title() {
        return __( 'Product Price', 'easy-elements-pro' );
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
            'easy_price_type',
            [
                'label' => esc_html__('Price Type', 'easy-elements-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'regular' => esc_html__('Regular Price', 'easy-elements-pro'),
                    'sale' => esc_html__('Sale Price', 'easy-elements-pro'),
                    'both' => esc_html__('Both Prices', 'easy-elements-pro'),
                ],
            ]
        );

        $this->add_control(
            'easyseparator',
            [
                'label' => esc_html__('Separator', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => ' - ',
                'placeholder' => esc_html__('e.g. / or | or -', 'easy-elements-pro'),
                'condition' => [
                    'easy_price_type' => 'both',
                ],
            ]
        );

        $this->add_control(
            'easy_price_underline',
            [
                'label' => esc_html__( 'Price Underline On/Off', 'easy-elements-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements-pro' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    /**
     * Render output
     */
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

        $price_type     = ! empty( $settings['easy_price_type'] ) ? $settings['easy_price_type'] : 'both';
        $regular_price  = $product->get_regular_price();
        $sale_price     = $product->get_sale_price();
        $separator      = ! empty( $settings['easyseparator'] ) ? $settings['easyseparator'] : ' | ';
        $underline      = ( isset( $settings['easy_price_underline'] ) && $settings['easy_price_underline'] === 'yes' ) ? ' underline-enabled' : '';
        $price_html     = '';

        if ( $price_type === 'regular' ) {
            $price_html = wc_price( $regular_price );
        } elseif ( $price_type === 'sale' ) {
            $price_html = $sale_price ? wc_price( $sale_price ) : wc_price( $regular_price );
        } else {
            // Both
            if ( $sale_price && $sale_price < $regular_price ) {
                $price_html  = '<span class="easy-product-price' . esc_attr( $underline ) . '">';
                $price_html .= '<del>' . wc_price( $regular_price ) . '</del>';
                $price_html .= esc_html( $separator );
                $price_html .= '<ins>' . wc_price( $sale_price ) . '</ins>';
                $price_html .= '</span>';
            } else {
                $price_html = '<span class="easy-product-price' . esc_attr( $underline ) . '">' . wc_price( $regular_price ) . '</span>';
            }
        }

        if ( empty( $price_html ) && ! empty( $settings['easydc_fallback'] ) ) {
            $price_html = $settings['easydc_fallback'];
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . $price_html . $after );
    }

}