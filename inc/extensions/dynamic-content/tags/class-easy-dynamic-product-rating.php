<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Rating extends Tag {

    public function get_name() {
        return 'easy-product-rating-dynamic';
    }

    public function get_title() {
        return __( 'Product Rating', 'easy-elements-pro' );
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
            'review_type',
            [
                'label' => esc_html__('Review Type', 'easy-elements-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'average_rating',
                'options' => [
                    'rating_count'   => esc_html__('Rating Count', 'easy-elements-pro'),
                    'review_count'   => esc_html__('Review Count', 'easy-elements-pro'),
                    'average_rating' => esc_html__('Average Rating', 'easy-elements-pro'),
                    'review_text'    => esc_html__('Review Text', 'easy-elements-pro'),
                ],
            ]
        );

        // Customizable review text for different ratings
        $this->add_control(
            'review_text_excellent',
            [
                'label' => esc_html__('Text for Excellent (5 Stars)', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Excellent',
                'condition' => [
                    'review_type' => 'review_text',
                ],
                'ai' => false,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'review_text_good',
            [
                'label' => esc_html__('Text for Good (4 Stars)', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Good',
                'condition' => [
                    'review_type' => 'review_text',
                ],
                'ai' => false,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'review_text_average',
            [
                'label' => esc_html__('Text for Average (3 Stars)', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Average',
                'condition' => [
                    'review_type' => 'review_text',
                ],
                'ai' => false,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'review_text_poor',
            [
                'label' => esc_html__('Text for Poor (1-2 Stars)', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Poor',
                'condition' => [
                    'review_type' => 'review_text',
                ],
                'ai' => false,
                'label_block' => true,
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        $product_id = ! empty( $settings['easy_product_data'] ) ? intval( $settings['easy_product_data']) : get_the_ID();
        if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
            return;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) return;

        $output = '';

        switch ( $settings['review_type'] ) {
            case 'rating_count':
                $output = $product->get_rating_count();
                break;

            case 'review_count':
                $output = $product->get_review_count();
                break;

            case 'average_rating':
                $output = wc_format_decimal( $product->get_average_rating(), 2 );
                break;

            case 'review_text':
                $average = round( $product->get_average_rating() );
                if ( $average >= 5 ) {
                    $output = $settings['review_text_excellent'];
                } elseif ( $average == 4 ) {
                    $output = $settings['review_text_good'];
                } elseif ( $average == 3 ) {
                    $output = $settings['review_text_average'];
                } else {
                    $output = $settings['review_text_poor'];
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