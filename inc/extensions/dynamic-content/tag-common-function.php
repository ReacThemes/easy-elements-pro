<?php 
use Elementor\Controls_Manager;

function easy_advanced_control( $element ) {
   
    $element->start_controls_section(
            'advanced_section',
            [
                'label' => esc_html__('Advanced', 'easy-elements-pro'),
            ]
    );
    $element->add_control(
        'easydc_before',
        [
            'label' => esc_html__('Before', 'easy-elements-pro'),
            'type' => Controls_Manager::TEXT,
        ]
    );

    $element->add_control(
        'easydc_after',
        [
            'label' => esc_html__('After', 'easy-elements-pro'),
            'type' => Controls_Manager::TEXT,
        ]
    );

    $element->add_control(
        'easydc_fallback',
        [
            'label' => esc_html__('Fallback', 'easy-elements-pro'),
            'type' => Controls_Manager::TEXT,
        ]
    );

    $element->add_control(
        'easy_word_limit',
        [
            'label' => esc_html__('Word Limit', 'easy-elements-pro'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0,
        ]
    );

    $element->end_controls_section();
}

function easy_general_settings_post( $element ) {
     $element->add_control(
        'easy_selected_post_type',
        [
            'label' => esc_html__('Select Post Option', 'easy-elements-pro'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'current',
            'options' => [
                'current' => esc_html__('Current Post', 'easy-elements-pro'),
                'selected' => esc_html__('Selected Post', 'easy-elements-pro'),
            ],
        ]
    );

    $element->add_control(
        'dynamic_post',
        [
            'label' => esc_html__('Search & Select a Post', 'easy-elements-pro'),
            'type' => 'easy-dynamic-select',
            'multiple' => false,
            'label_block' => true,
            'query_args' => [
                'query' => 'posts',
                'post_type' => 'any',
            ],
            'condition' => ['easy_selected_post_type' => 'selected']
        ]
    );
}

function easy_apply_word_limit( $text, $settings, $key = 'easy_word_limit' ) {
    if ( ! empty( $settings[ $key ] ) && $settings[ $key ] > 0 ) {
        $words = explode( ' ', $text );
        $text = implode( ' ', array_slice( $words, 0, $settings[ $key ] ) );
    }
    return $text;
}


function get_easy_user_info( $element ) {
    $element->add_control(
        'easy_user_info',
        [
            'label' => esc_html__('Search & Select User', 'easy-elements-pro'),
            'type' => 'easy-dynamic-select',
            'multiple' => false,
            'label_block' => true,
            'query_args' => [
                'query' => 'authors',
            ],
        ]
    );
}

function easy_get_terms_data_info( $element ) {
    $element->add_control(
        'easy_terms_data',
        [
            'label' => esc_html__('Search & Select Terms', 'easy-elements-pro'),
            'type' => 'easy-dynamic-select',
            'multiple' => false,
            'label_block' => true,
            'query_args' => [
                'query' => 'terms',
            ],
        ]
    );
}


function easy_get_product_title_search( $element ) {
    $element->add_control(
        'easy_product_data',
        [
            'label' => esc_html__('Search  Product', 'easy-elements-pro'),
            'type' => 'easy-dynamic-select',
            'multiple' => false,
            'label_block' => true,
            'query_args' => [
                'query' => 'posts',
                'post_type' => 'product',
            ],
        ]
    );
}