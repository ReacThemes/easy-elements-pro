<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Product_Terms extends Tag {

    public function get_name() {
        return 'easy-product-terms-dynamic';
    }

    public function get_title() {
        return __( 'Product Terms', 'easy-elements-pro' );
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

    private function get_taxonomies_product_dropdown() {
        $taxonomies = get_taxonomies( ['public' => true,  'show_ui'  => true ], 'objects' );
        $options = [];

        foreach ( $taxonomies as $taxonomy ) {
            if ( in_array( 'product', $taxonomy->object_type ) ) {
                $options[ $taxonomy->name ] = $taxonomy->label;
            }
        }

        return $options;
    }

    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        easy_get_product_title_search( $this );

         $this->add_control(
            'easy_taxonomy',
            [
                'label' => __( 'Taxonomy', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'product_cat',
                'options' => $this->get_taxonomies_product_dropdown(),
            ]
        );

        // --- Show as link ---
        $this->add_control(
            'easy_show_link',
            [
                'label' => __( 'Show as Link', 'easy-elements-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'easy-elements-pro' ),
                'label_off' => __( 'No', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'easy_separator',
            [
                'label' => __( 'Separator', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => ', ',
                'description' => __( 'Separator for multiple terms', 'easy-elements-pro' ),
                'label_block' => true,
                'ai'    => false,
            ]
        );

        $this->add_control(
            'easy_terms_limit',
            [
                'label' => __( 'Limit', 'easy-elements-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Maximum number of terms to display. Leave empty for all.', 'easy-elements-pro' ),
                'min' => 1,
                'step' => 1,
                'default' => '',
            ]
        );

        // Offset terms
        $this->add_control(
            'easy_terms_offset',
            [
                'label' => __( 'Offset', 'easy-elements-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Skip first N terms.', 'easy-elements-pro' ),
                'min' => 0,
                'step' => 1,
                'default' => 0,
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

        $taxonomy = ! empty( $settings['easy_taxonomy'] ) ? $settings['easy_taxonomy'] : 'product_cat';
        $terms = wp_get_post_terms( $product_id, $taxonomy );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            if ( ! empty( $settings['easydc_fallback'] ) ) {
                echo esc_html( $settings['easydc_fallback'] );
            }
            return;
        }

        // Apply offset and limit
        $offset = ! empty( $settings['easy_terms_offset'] ) ? intval( $settings['easy_terms_offset'] ) : 0;
        $limit  = ! empty( $settings['easy_terms_limit'] ) ? intval( $settings['easy_terms_limit'] ) : count( $terms );
        $terms  = array_slice( $terms, $offset, $limit );

        $separator = isset( $settings['easy_separator'] ) ? $settings['easy_separator'] : ', ';
        $show_link = ! empty( $settings['easy_show_link'] ) && $settings['easy_show_link'] === 'yes';

        $term_list = [];
        foreach ( $terms as $term ) {
            if ( $show_link ) {
                $term_list[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
            } else {
                $term_list[] = esc_html( $term->name );
            }
        }

        $before = ! empty( $settings['easydc_before'] ) ? $settings['easydc_before'] : '';
        $after  = ! empty( $settings['easydc_after'] ) ? $settings['easydc_after'] : '';

        echo wp_kses_post( $before . implode( $separator, $term_list ) . $after );
    }

}