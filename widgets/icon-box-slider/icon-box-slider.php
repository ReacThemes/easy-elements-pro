<?php
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Icon_Box_Slider__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-icon-box-slider';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/icon-box-slider.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/icon-box-slider.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-icon-box-slider';
    }

    public function get_title() {
        return esc_html__( 'Easy Icon Box Slider', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'box', 'sevice', 'icon', 'icon-box', 'text' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Icon Box Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $repeater->add_control(
            'top_icon',
            [
                'label' => esc_html__( 'Icon Top', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            '_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Optimizing production and supply chain operations and generational transitions', 'easy-elements' ),
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );
        // Current Item CSS 
        $repeater->start_controls_tabs('current_item_style_tabs');
            // Normal Tab
            $repeater->start_controls_tab(
                'current_item_normal',
                [
                    'label' => __('Normal', 'easy-elements'),
                ]
             );
                $repeater->add_control(
                    'current_item_bg',
                    [
                        'label' => __('Background', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}' => 'background: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_icon_color',
                    [
                        'label' => __('Icon Color', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-icon' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-icon svg' => 'fill: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-icon svg path' => 'fill: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_icon_bg',
                    [
                        'label' => __('Icon Background', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-icon' => 'background: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_title_color',
                    [
                        'label' => __('Title Color', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .icon-box-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_desc_color',
                    [
                        'label' => __('Description Color', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .icon-box-description' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_btn_color',
                    [
                        'label' => __('Button Color & Border Color', 'easy-elements'),
                        'type'  => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-read-more .eel-read-more-text' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-read-more .eel-read-more-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                        ],
                    ]
                );
            $repeater->end_controls_tab();
            // Hover Tab
            $repeater->start_controls_tab(
                'current_item_hover',
                [
                    'label' => __('Hover', 'easy-elements'),
                ]
             );
                $repeater->add_control(
                    'current_item__hoverbg',
                    [
                        'label' => __('Background', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover' => 'background: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_icon_hover_color',
                    [
                        'label' => __('Icon Color', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .eel-icon' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .eel-icon svg' => 'fill: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .eel-icon svg path' => 'fill: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_icon_hover_bg',
                    [
                        'label' => __('Icon Background', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .eel-icon' => 'background: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_title_hover_color',
                    [
                        'label' => __('Title Color', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .icon-box-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_desc_hover_color',
                    [
                        'label' => __('Description Color', 'easy-elements'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .icon-box-description' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_btn_color',
                    [
                        'label' => __('Item Hover to Button Color & Border Color', 'easy-elements'),
                        'type'  => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .eel-read-more .eel-read-more-text' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}}:hover .eel-read-more .eel-read-more-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_btn_hover_color',
                    [
                        'label' => __('Button Color & Border Color', 'easy-elements'),
                        'type'  => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-read-more .eel-read-more-text:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                            '{{WRAPPER}} .ee--icon-box-slider{{CURRENT_ITEM}} .eel-read-more .eel-read-more-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                        ],
                    ]
                );
            $repeater->end_controls_tab();
        $repeater->end_controls_tabs();  
        
        $this->add_control(
            'icon_boxes',
            [
                'label' => esc_html__( 'Icon Boxes', 'easy-elements' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        '_title' => esc_html__( 'Manufacturing Industrial 01', 'easy-elements' ),
                    ],
                    [
                        '_title' => esc_html__( 'Manufacturing Industrial 02', 'easy-elements' ),
                    ],
                    [
                        '_title' => esc_html__( 'Manufacturing Industrial 03', 'easy-elements' ),
                    ],
                ],
                'title_field' => '{{{ _title }}}',
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__('Show Read More', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        
        $this->add_control(
            'read_more_type',
            [
                'label' => esc_html__('Read More Type', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'read_text' => esc_html__('Text', 'easy-elements'),
                    'read_icon' => esc_html__('Icon', 'easy-elements'),
                    'read_icon_to_text' => esc_html__('Icon Hover to Text Show', 'easy-elements'),
                ],
                'default' => 'read_text',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Read More', 'easy-elements'),
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-read-more-text',
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        
        $this->add_control(
            'read_more_icon',
            [
                'label' => esc_html__('Read More Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_color',
            [
                'label' => esc_html__('Icon Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-icon i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_color_hovers',
            [
                'label' => esc_html__('Icon Hover Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-icon:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-icon:hover svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-icon:hover i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_color_hover',
            [
                'label' => esc_html__('Icon Hover Color (When use with Wrap BG Hover Color)', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__('Note: This hover color will only apply if a background hover color is set for the item (Wrap BG Hover Color).'),
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-icon i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'read_more_icon_size',
            [
                'label' => esc_html__('Icon Size', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-read-more-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_text_color',
            [
                'label' => esc_html__('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-text svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-text svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_control(
            'read_more_text_color_hover',
            [
                'label' => esc_html__('Text Hover Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_color_hovers',
            [
                'label' => esc_html__('Text Hover Color (When use with Wrap BG Hover Color)', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__('Note: This hover color will only apply if a background hover color is set for the item (Wrap BG Hover Color).'),
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_bg_color',
            [
                'label' => esc_html__('Background Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_bg_color_hover',
            [
                'label' => esc_html__('Text Hover Background Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_bg_color_hovers',
            [
                'label' => esc_html__('Text Hover Background Color (When use with Wrap BG Hover Color)', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__('Note: This hover color will only apply if a background hover color is set for the item (Wrap BG Hover Color).'),   
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],    
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_responsive_control(
            'read_more_text_padding',
            [
                'label' => esc_html__('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'read_more_text_border',
                'label' => esc_html__('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-read-more-text, {{WRAPPER}} .eel-read-more-icon',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'read_more_text_icon_show',
            [
                'label' => esc_html__('Show Icon Next to Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_control(
            'read_more_text_icon',
            [
                'label' => esc_html__('Text Button Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_text_icon_size',
            [
                'label' => esc_html__('Text Button Icon Size', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-read-more-text-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_text_icon_position',
            [
                'label' => esc_html__('Position Top/Bottom', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text-icon' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'read_more_text_icon_color_hover',
            [
                'label' => esc_html__('Text Button Icon Hover Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover .eel-read-more-text-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover .eel-read-more-text-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider .eel-read-more-text:hover .eel-read-more-text-icon svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'read_more_text_icon_color_hovers',
            [
                'label' => esc_html__('Text Button Icon Hover Color (When use with Wrap BG Hover Color)', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__('Note: This hover color will only apply if a background hover color is set for the item (Wrap BG Hover Color).'),
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text .eel-read-more-text-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text .eel-read-more-text-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-read-more-text .eel-read-more-text-icon svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],    
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_direction',
            [
                'label' => esc_html__( 'Direction', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [                    
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
            ]
        );


        $this->add_responsive_control(
            '_text_align',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p'   => 'p',
                ],
            ]
        );
        // Top Icon Show/Hide Switcher
        $this->add_control(
            'show_top_icon',
            [
                'label' => esc_html__('Show Top Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'slider__part_section',
             [
                'label' => esc_html__( 'Slider Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
             ]
        );

        $this->add_control(
             'loop',
             [
                 'label' => esc_html__( 'Loop', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::SWITCHER,
                 'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                 'label_off' => esc_html__( 'No', 'easy-elements' ),
                 'return_value' => 'yes',
                 'default' => 'no',
             ]
         );

        $this->add_control(
        'autoplay',
        [
            'label' => esc_html__('Autoplay', 'easy-elements'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('On', 'easy-elements'),
            'label_off' => esc_html__('Off', 'easy-elements'),
            'return_value' => 'yes',
            'default' => '',
        ]
         );

         $this->add_control(
           'autoplay_delay',
           [
               'label' => esc_html__('Autoplay Delay (ms)', 'easy-elements'),
               'type' => \Elementor\Controls_Manager::NUMBER,
               'default' => 3000,
           ]
         );

         // Speed
         $this->add_control(
             'speed',
             [
                 'label' => esc_html__('Speed (ms)', 'easy-elements'),
                 'type' => Controls_Manager::NUMBER,
                 'default' => 900,
             ]
         );

         $this->add_control(
             'centered_slides',
             [
                 'label' => esc_html__('Centered Slides', 'easy-elements'),
                 'type' => Controls_Manager::SWITCHER,
                 'label_on' => esc_html__('Yes', 'easy-elements'),
                 'label_off' => esc_html__('No', 'easy-elements'),
                 'return_value' => 'yes',
                 'default' => '',
             ]
         );
         $this->add_control(
             'free_mode',
             [
                 'label' => esc_html__('Free Mode', 'easy-elements'),
                 'type' => \Elementor\Controls_Manager::SWITCHER,
                 'label_on' => esc_html__('Yes', 'easy-elements'),
                 'label_off' => esc_html__('No', 'easy-elements'),
                 'return_value' => 'yes',
                 'default' => '',
             ]
         );

        $this->add_control(
           'slides_per_view',
           [
               'label' => esc_html__('Slides Per View', 'easy-elements'),
               'type' => \Elementor\Controls_Manager::NUMBER,
               'default' => 3,
           ]
        );

        $this->add_control(
          'slides_per_view_tablet',
          [
              'label' => esc_html__('Slides Per View (Tablet)', 'easy-elements'),
              'type' => \Elementor\Controls_Manager::NUMBER,
              'default' => 2,
          ]
        );

        $this->add_control(
          'slides_per_view_mobile',
          [
              'label' => esc_html__('Slides Per View (Mobile)', 'easy-elements'),
              'type' => \Elementor\Controls_Manager::NUMBER,
              'default' => 1,
          ]
        );

        $this->add_control(
           'space_between',
           [
               'label' => esc_html__('Space Between (px)', 'easy-elements'),
               'type' => \Elementor\Controls_Manager::NUMBER,
               'default' => 20,
           ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Pagination', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'easy-elements'),
                    'bullets' => esc_html__('Bullets', 'easy-elements'),
                    'fraction' => esc_html__('Fraction', 'easy-elements'),
                    'progressbar' => esc_html__('Progressbar', 'easy-elements'),
                ],
            ]
        );

        $this->add_control(
            'number_mode_enable',
            [
                'label' => esc_html__('Number Mode', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'easy-elements'),
                'label_off' => esc_html__('No', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'pagination' => ['bullets']
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_alignment',
            [
                'label' => esc_html__( 'Pagination Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eel-pagination-number-yes.swiper-pagination, {{WRAPPER}} .swiper-pagination' => 'justify-content: {{VALUE}};',
                ],
            ]
        );     
          
        $this->add_responsive_control(
             'pagination_top_spacing',
             [
                 'label' => esc_html__( 'Top Spacing', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::SLIDER,
                 'size_units' => [ '%', 'px', 'em', 'rem', 'custom' ],
                 'range' => [
                     'px' => [
                         'min' => 0,
                         'max' => 1000,
                         'step' => 1,
                     ],
                     '%' => [
                         'min' => 0,
                         'max' => 100,
                     ],
                 ],
                 'condition' => [
                     'pagination' => ['bullets', 'fraction']
                 ],
                 'selectors' => [
                     '{{WRAPPER}} .swiper-pagination' => 'top: {{SIZE}}{{UNIT}};',
                 ],
             ]
        );
        
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'color: {{VALUE}}; opacity: 1;',
                    '{{WRAPPER}} .eel-pagination-number-yes.swiper-pagination span.swiper-pagination-bullet-active::after' => 'background: {{VALUE}};',                    
                ],
                'condition' => [
                   'number_mode_enable' => 'yes'
                ],
            ]
        ); 

        $this->add_control(
            'number_color_active',
            [
                'label' => esc_html__( 'Number Active Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'color: {{VALUE}}; opacity: 1;',
                ],
                'condition' => [
                   'number_mode_enable' => 'yes'
                ],
            ]
        ); 

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .eel-pagination-number-yes.swiper-pagination span',
                'condition' => [
                    'number_mode_enable' => 'yes'
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_left_spacing',
            [
                'label' => esc_html__( 'Left/Right Spacing', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'pagination' => ['bullets', 'fraction']
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
       ); 
    

    $this->add_responsive_control(
        'pagination_noraml_height',
        [
            'label' => esc_html__( 'Normal Dot Height', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 8,
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination' => ['bullets']
            ],
        ]
    );

    $this->add_responsive_control(
        'pagination_normal_width',
        [
            'label' => esc_html__( 'Normal Dot Width', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 8,
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination' => ['bullets']
            ],
        ]
    );

    $this->add_responsive_control(
        'pagination_active_height',
        [
            'label' => esc_html__( 'Active Dot Height', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 8,
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination' => ['bullets']
            ],
        ]
    );

    $this->add_responsive_control(
        'pagination_active_width',
        [
            'label' => esc_html__( 'Active Dot Width', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 8,
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination' => ['bullets']
            ],
        ]
    );

    $this->add_control(
        'pagination_active_border_radius',
        [
            'label' => esc_html__( 'Active Dot Border Radius', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 4,
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination' => ['bullets']
            ],
        ]
    );

         $this->add_control(
             'pagination_color',
             [
                 'label' => esc_html__( 'Pagination Color', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}}',
                     '{{WRAPPER}} .swiper-pagination-progressbar' => 'background: {{VALUE}}',
                     '{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
                     
                 ],
                 'condition' => [
                     'pagination' => ['bullets', 'fraction', 'progressbar']
                 ],
             ]
         ); 

         $this->add_control(
             'pagination_active_color',
             [
                 'label' => esc_html__( 'Pagination Active Color', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'background: {{VALUE}}',
                     '{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background: {{VALUE}}',
                     '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}}',
                 ],
                 'condition' => [
                     'pagination' => ['bullets', 'fraction', 'progressbar']
                 ],
             ]
         ); 

         $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation Arrows', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
         );

         $this->start_controls_tabs( 'tabs_navigation_style' );

         // Normal tab
         $this->start_controls_tab(
             'tab_navigation_normal',
             [
                 'label' => esc_html__( 'Normal', 'easy-elements' ),
                 'condition' => [ 'navigation' => 'yes' ],
             ]
         );

         $this->add_control(
             'navigation_color',
             [
                 'label' => esc_html__( 'Color', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .swiper-prev' => 'color: {{VALUE}}',                   
                     '{{WRAPPER}} .swiper-prev svg' => 'fill: {{VALUE}}',                   
                     '{{WRAPPER}} .swiper-next' => 'color: {{VALUE}}',                   
                     '{{WRAPPER}} .swiper-next svg' => 'fill: {{VALUE}}',                   
                 ],
                 'condition' => [ 'navigation' => 'yes' ],
             ]
         );

         $this->add_control(
             'navigation_bgcolor',
             [
                 'label' => esc_html__( 'Background Color', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .swiper-prev' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                     '{{WRAPPER}} .swiper-next' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',            
                 ],
                 'condition' => [ 'navigation' => 'yes' ],
             ]
         );

         $this->end_controls_tab();

         // Hover tab
         $this->start_controls_tab(
             'tab_navigation_hover',
             [
                 'label' => esc_html__( 'Hover', 'easy-elements' ),
                 'condition' => [ 'navigation' => 'yes' ],
             ]
         );

         $this->add_control(
             'navigation_hover_color',
             [
                 'label' => esc_html__( 'Color', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .swiper-prev:hover' => 'color: {{VALUE}}',                 
                     '{{WRAPPER}} .swiper-prev:hover svg' => 'fill: {{VALUE}}',                 
                     '{{WRAPPER}} .swiper-next:hover svg' => 'fill: {{VALUE}}',                     
                     '{{WRAPPER}} .swiper-next:hover' => 'color: {{VALUE}}',                     
                 ],
                 'condition' => [ 'navigation' => 'yes' ],
             ]
         );

         $this->add_control(
             'navigation_hover_bgcolor',
             [
                 'label' => esc_html__( 'Background Color', 'easy-elements' ),
                 'type' => \Elementor\Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .swiper-prev:hover' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                     '{{WRAPPER}} .swiper-next:hover' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',        
                 ],
                 'condition' => [ 'navigation' => 'yes' ],
             ]
         );

         $this->end_controls_tab();
         $this->end_controls_tabs();


         $this->add_control(
            'ee_customicon_',
            [
                'label' => esc_html__('Need Custom Icon?', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
         );

         $this->add_control(
             'arrow_prev_icon',
             [
                 'label' => esc_html__( 'Prev Arrow', 'easy-elements' ),
                 'type'  => \Elementor\Controls_Manager::ICONS,
                 'default' => [
                     'value' => 'fas fa-chevron-left',
                     'library' => 'fa-solid',
                 ],
                 'condition' => [
                     'ee_customicon_' => 'yes',
                 ],
             ]
         );

         $this->add_control(
             'arrow_next_icon',
             [
                 'label' => esc_html__( 'Next Arrow', 'easy-elements' ),
                 'type'  => \Elementor\Controls_Manager::ICONS,
                 'default' => [
                     'value' => 'fas fa-chevron-right',
                     'library' => 'fa-solid',
                 ],
                 'condition' => [
                     'ee_customicon_' => 'yes',
                 ],
             ]
         );

        $this->end_controls_section();   

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__( 'Item', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('background_tabs');

        $this->start_controls_tab('background_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bg_normal',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ee--icon-box-slider',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box-slider',
            ]
        );


        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box-slider',
            ]
        );


        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => __('Margin', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('background_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bg_hover',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ee--icon-box-slider:hover',
            ]
        );

        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border_hover',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box-slider:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hover',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box-slider:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();   

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('icon_style_tabs');
        $this->start_controls_tab('icon_style_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-icon, 
                    {{WRAPPER}} .ee--icon-box-slider .eel-icon svg,
                    {{WRAPPER}} .ee--icon-box-slider .eel-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ee--icon-box-slider .eel-icon',
            ]
        );

        $this->add_responsive_control(
            'icon__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-icon i, {{WRAPPER}} .ee--icon-box-slider .eel-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Box Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-icon' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .eel-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_icon',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box-slider .eel-icon',
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab('icon_style_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-icon, 
                    {{WRAPPER}} .ee--icon-box-slider:hover .eel-icon svg, 
                    {{WRAPPER}} .ee--icon-box-slider:hover .eel-icon svg path,
                    {{WRAPPER}} .ee--icon-box-slider:hover .eel-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border_hover',
                'selector' => '{{WRAPPER}} .ee--icon-box-slider:hover .eel-icon',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

         // Top Icon Style Controls
         $this->start_controls_section(
            'section_style_top_icon',
            [
                'label' => esc_html__( 'Top Icon', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_top_icon' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'top_icon_top',
            [
                'label' => esc_html__( 'Top', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => [ 'size' => -5, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--top-icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'top_icon_right',
            [
                'label' => esc_html__( 'Right', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => [ 'size' => -5, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--top-icon' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
 
        $this->add_responsive_control(
            'top_icon_height',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 500 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 60, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--top-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel--top-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'top_icon_opacity',
            [
                'label' => esc_html__( 'Opacity', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 0.05 ],
                'selectors' => [
                    '{{WRAPPER}} .eel--top-icon' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        // Top Icon Color Tabs
        $this->start_controls_tabs('top_icon_color_tabs');
        // Normal Tab
        $this->start_controls_tab('top_icon_color_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);
        $this->add_control(
            'top_icon_color',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--top-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel--top-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel--top-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Hover Tab
        $this->start_controls_tab('top_icon_color_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);
        $this->add_control(
            'top_icons_color_hover',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel--top-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel--top-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box-slider:hover .eel--top-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('title_color_tabs');
        $this->start_controls_tab('title_color_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-title',
            ]
        );  

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->end_controls_tab();
        $this->start_controls_tab('title_color_hovers', [
            'label' => __('Hover', 'easy-elements'),
        ]);
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs(); 

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('desc_color_tabs');
        $this->start_controls_tab('desc_color_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'box_desc_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-description',
            ]
        );

        $this->add_responsive_control(
            'des_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider .icon-box-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab('desc_color_hovers', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'desc_color_hover',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box-slider:hover .icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['icon_boxes'] ) ) {
            return;
        }

        $slider_path = plugin_dir_path( __FILE__ ) . 'slider.php';
        if ( file_exists( $slider_path ) ) {
            include $slider_path;
        }
        ?>
        <div id="<?php echo esc_attr($unique_id); ?>" class="eel-icon-box-wrap swiper eel-all-slider" <?php echo $slider_data_attributes; ?>>
            <div class="swiper-wrapper">
                <?php 
                foreach ( $settings['icon_boxes'] as $item ) :
                    $icon_direction     = $settings['icon_direction'] ?? '';
                    $link     = $item['link']['url'] ?? '';
                    $target   = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    $unique_class = 'elementor-repeater-item-' . $item['_id']; 
                ?>
                <div class="swiper-slide">
                    <div class="ee--icon-box-slider <?php echo esc_attr($icon_direction.' '.$unique_class); ?>">
                        <?php if ( $link ) : ?>
                            <a href="<?php echo esc_url( $link ); ?>"<?php echo $target . $nofollow; ?>>
                        <?php endif; ?>

                        <?php if ( isset( $item['icon']['value'] ) && $item['icon']['value'] ) : ?>
                            <span class="eel-icon">
                                <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </span>
                        <?php endif; ?>

                       
                        <?php if ( (!empty($settings['show_top_icon']) && $settings['show_top_icon'] === 'yes') && ( (!empty($item['top_icon']) && !empty($item['top_icon']['value'])) || (!empty($item['icon']) && !empty($item['icon']['value'])) ) ) : ?>
                        <span class="eel--top-icon">
                            <?php
                            if ( ! empty( $item['top_icon'] ) && ! empty( $item['top_icon']['value'] ) ) :
                                \Elementor\Icons_Manager::render_icon( $item['top_icon'], [ 'aria-hidden' => 'true' ] ); 
                            else:
                                \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); 
                            endif;
                            ?>
                        </span>
                        <?php endif; ?>
                      

                        <?php if ( $icon_direction === 'left' || $icon_direction === 'right' ) : ?>
                            <div class="eel-title-content-wrap">
                        <?php endif; ?>

                        <?php if ( ! empty( $item['_title'] ) ) :
                            $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3'; ?>
                            <<?php echo esc_attr( $title_tag ); ?> class="icon-box-title">
                                <?php echo esc_html( $item['_title'] ); ?>
                            </<?php echo esc_attr( $title_tag ); ?>>
                        <?php endif; ?>


                        <?php if ( ! empty( $item['_description'] ) ) : ?>
                            <div class="icon-box-description"><?php echo esc_html( $item['_description'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( !empty($settings['show_read_more']) && $settings['show_read_more'] === 'yes' ) : ?>
                            <div class="eel-read-more">
                                <?php if ( $settings['read_more_type'] === 'read_icon' ) : ?>
                                    <span class="eel-read-more-icon">
                                        <?php
                                        if ( !empty($settings['read_more_icon']['value']) ) {
                                            \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [ 'aria-hidden' => 'true' ] );
                                        } else {
                                            echo '<i class="unicon-arrow-up-right"></i>';
                                        }
                                        ?>
                                    </span>
                                <?php elseif ( $settings['read_more_type'] === 'read_text' && !empty($settings['read_more_text']) ) : ?>
                                    <span class="eel-read-more-text">
                                        <?php echo esc_html( $settings['read_more_text'] ); ?>
                                        <?php
                                        if (
                                            !empty($settings['read_more_text_icon_show']) &&
                                            $settings['read_more_text_icon_show'] === 'yes'
                                        ) {
                                            echo '<span class="eel-read-more-text-icon">';
                                            if (!empty($settings['read_more_text_icon']['value'])) {
                                                \Elementor\Icons_Manager::render_icon( $settings['read_more_text_icon'], [ 'aria-hidden' => 'true' ] );
                                            } else {
                                                echo '<i class="unicon-arrow-up-right"></i>';
                                            }
                                            echo '</span>';
                                        }
                                        ?>
                                    </span>
                                <?php elseif ( $settings['read_more_type'] === 'read_icon_to_text' && !empty($settings['read_more_text']) ) : ?>
                                    <span class="eel-read-more-text eel-icon-to-text">
                                        <span class="eel-text"><?php echo esc_html( $settings['read_more_text'] ); ?></span>
                                        <?php
                                        if (
                                            !empty($settings['read_more_text_icon_show']) &&
                                            $settings['read_more_text_icon_show'] === 'yes'
                                        ) {
                                            echo '<span class="eel-read-more-text-icon">';
                                            if (!empty($settings['read_more_text_icon']['value'])) {
                                                \Elementor\Icons_Manager::render_icon( $settings['read_more_text_icon'], [ 'aria-hidden' => 'true' ] );
                                            } else {
                                                echo '<i class="unicon-add"></i>';
                                            }
                                            echo '</span>';
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $icon_direction === 'left' || $icon_direction === 'right' ) : ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $link ) : ?>
                            </a>
                        <?php endif; ?>
                    </div>       
                </div>       
                <?php endforeach; ?>                  
            </div>
            

            <?php
                $settings = $this->get_settings_for_display();
                if ( 'yes' === $settings['ee_customicon_'] ) {
                    echo '<div class="swiper-navigation">';
                    if ( ! empty( $settings['arrow_prev_icon']['value'] ) ) {
                        echo '<div class="swiper-prev">';
                        \Elementor\Icons_Manager::render_icon( $settings['arrow_prev_icon'], [ 'aria-hidden' => 'true' ] );
                        echo '</div>';
                    }

                    if ( ! empty( $settings['arrow_next_icon']['value'] ) ) {
                        echo '<div class="swiper-next">';
                        \Elementor\Icons_Manager::render_icon( $settings['arrow_next_icon'], [ 'aria-hidden' => 'true' ] );
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    if ( $data_navigation === 'true' ) :
                        echo '<div class="swiper-button-prev swiper-prev"></div>';
                        echo '<div class="swiper-button-next swiper-next"></div>';
                    endif;
                }
            ?>
        </div>
        <?php if ( $settings['pagination'] !== 'none' ) : ?>
            <div class="swiper-pagination eel-pagination-number-<?php echo $settings['number_mode_enable']; ?>"></div>
        <?php endif; ?>
    <?php
    }
} ?>