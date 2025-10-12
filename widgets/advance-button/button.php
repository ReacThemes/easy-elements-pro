<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Advance_Button_Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-advance-button-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/advance-button.css';

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/advance-button.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : time() );
        }

        return [ $handle ];
    }

    public function get_script_depends() {
        $handle = 'eel-advance-button-script';
        $js_path = plugin_dir_path( __FILE__ ) . 'js/advance-button.js';
        
        // Check if minification is enabled and helper class exists
        if ( get_option( 'easyel_elements_minify_js', '0' ) === '1' && class_exists( 'Easyel_Elements_JS_Loader_Helper' ) ) {
            try {
                Easyel_Elements_JS_Loader_Helper::easyel_elements_load_minified_inline_js( $handle, $js_path );
                return [ $handle ];
            } catch ( Exception $e ) {
                // Fallback to normal loading if minification fails
                error_log( 'Easy Elements: JS minification failed for ' . $handle . ': ' . $e->getMessage() );
            }
        }

        // Normal script loading
        if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
            wp_register_script( $handle, plugins_url( 'js/advance-button.js', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-advace-button';
    }

    public function get_title() {
        return esc_html__( 'Advance Button', 'easy-elements-pro' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'button', 'link' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
          'butten_settings',
          [
               'label' => esc_html__( 'Button Settings', 'easy-elements-pro' ),
               'tab' => Controls_Manager::TAB_CONTENT
          ]
        );

        $this->add_control(
            'button_layout',
            [
                'label' => esc_html__('Button Type', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'easy-elements-pro'),
                    'sticky' => esc_html__('Sticky', 'easy-elements-pro'),
                    'badge' => esc_html__('Button with Badge', 'easy-elements-pro'),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'button_style_default',
            [
                'label' => esc_html__('Style', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'skin1' => esc_html__('Left Slide', 'easy-elements-pro'),
                    'skin3' => esc_html__('Bottom Slide', 'easy-elements-pro'),
                    'skin2' => esc_html__('Letter Effect', 'easy-elements-pro'),
                    'skin4' => esc_html__('Letter Effect 2', 'easy-elements-pro'),
                ],
                'default' => 'skin1',
                'condition' => [
                    'button_layout' => 'default',
                ],
            ]
        );

        $this->add_control(
            'button_style',
            [
                'label' => esc_html__('Style', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'skin1' => esc_html__('Style 01', 'easy-elements-pro'),
                ],
                'default' => 'skin1',
                'condition' => [
                    'button_layout' => 'sticky',
                ],
            ]
        );

        $this->add_control(
            'sticky_value',
            [
                'label' => esc_html__('Sticky Strength', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0.1,
                'max' => 50,
                'step' => 0.1,
                'condition' => [
                    'button_layout' => 'sticky',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Text', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "Sticky Button",
                'placeholder' => esc_html__('Enter Text', 'easy-elements-pro'),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );
        $this->add_control(
            'button_badge_text',
            [
                'label' => esc_html__('Badge Text', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "Badge",
                'dynamic'     => [
                    'active' => true,
                ],
                'condition' => [
					'button_layout' => 'badge',
				],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Link', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'easy-elements-pro'),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
			'button_icon',
			[
				'label' => esc_html__('Icon', 'easy-elements-pro'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => '',
				],
                'condition' => [
					'button_style_default!' => ['skin2','skin4'],
                    'button_layout!' => 'badge',
				],
			]
		);
        $this->add_control(
			'icon_position',
			[
				'label' => esc_html__('Icon Position', 'easy-elements-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__('Before Text', 'easy-elements-pro'),
					'after' => esc_html__('After Text', 'easy-elements-pro'),
				],
                'condition' => [
					'button_style_default!' => ['skin2', 'skin4'],
                    'button_icon[value]!' => '',
                    'button_layout!' => 'badge',
				],
			]
		);

        $this->add_responsive_control(
            'text_gap',
            [
                'label' => esc_html__('Icon Spacing', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-advance-button-text, {{WRAPPER}} .eel-advance-btn-wrap, {{WRAPPER}} .eel-btn-main div' => 'gap: {{SIZE}}{{UNIT}}; display: inline-flex;',
                ],
               'condition' => [
					'button_style_default!' => ['skin2', 'skin4'],
                    'button_icon[value]!' => '',
                    'button_layout!' => 'badge',
				],
            ]
        );

        $this->add_responsive_control(
            'icon_position_top_btm',
            [
                'label' => esc_html__('Spacing Vertical', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => -10,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => -10,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-advance-button i, {{WRAPPER}} .eel-advance-button svg' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
                ],
                'condition' => [
					'button_style_default!' => ['skin2', 'skin4'],
                    'button_icon[value]!' => '',
                    'button_layout!' => 'badge',
				],
            ]
        );

        $this->add_responsive_control(
            'icon_position_left_right',
            [
                'label' => esc_html__('Spacing Horizontal', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => -10,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => -10,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-advance-button-defualt3 .eel-btn-main div' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_style_default' => ['skin3',],
                    'button_icon[value]!' => '',
                    'button_layout!' => 'badge',
                ],
            ]
        );
       
        $this->end_controls_section();

        // Style Tab - Button
		$this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__('Button Style', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		 );
            $this->start_controls_tabs('button_styles');

                $this->start_controls_tab(
                    'button_normal',
                    [
                        'label' => esc_html__('Normal', 'easy-elements'),
                    ]
                 );

                    $this->add_control(
                        'btn_color',
                        [
                            'label' => esc_html__( 'Color', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'background_btn',
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .eel-advance-button, {{WRAPPER}} .eel-advance-button-defualt3 .eel-btn-one',
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'btn_typography',
                            'selector' => '{{WRAPPER}} .eel-advance-button',
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Border::get_type(),
                        [
                            'name' => 'btn_border',
                            'selector' => '{{WRAPPER}} .eel-advance-button',
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'button_box_shadow',
                            'selector' => '{{WRAPPER}} .eel-advance-button',
                        ]
                    );

                    $this->add_responsive_control(
                        'button__width',
                        [
                            'label' => esc_html__('Width', 'easy-elements'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px','%'],
                            'range' => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'button_style_default' => ['skin1'],
                            ],
                            'condition' => [
                                'button_style' => ['skin1'],
                            ],                
                        ]
                    );

                    $this->add_responsive_control(
                        'button__height',
                        [
                            'label' => esc_html__('Height', 'easy-elements'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px','%'],
                            'range' => [
                                'px' => [
                                    'min' => 50,
                                    'max' => 500,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button' => 'height: {{SIZE}}{{UNIT}}; display:inline-grid; place-content: center;',
                                '{{WRAPPER}} .eel-advance-button-defualt3 .eel-btn-main div' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'button_style_default' => ['skin1'],
                            ],
                            'condition' => [
                                'button_style' => ['skin1'],
                            ],
                        ]
                    );


                    $this->add_responsive_control(
                        'button__width_two',
                        [
                            'label' => esc_html__('Hover Width', 'easy-elements'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px','%'],
                            'range' => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button-defualt3 .eel-btn-main div' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'button_style_default' => ['skin3'],
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button__height_two',
                        [
                            'label' => esc_html__('Hover Height', 'easy-elements'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px','%'],
                            'range' => [
                                'px' => [
                                    'min' => 50,
                                    'max' => 500,
                                ],
                            ],
                            'selectors' => [                    
                                '{{WRAPPER}} .eel-advance-button-defualt3:hover .eel-btn-main' => 'top: -{{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'button_style_default' => ['skin3'],
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => esc_html__('Padding', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                'button_style_default' => ['skin1', 'skin2', 'skin4'],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__('Border Radius', 'easy-elements'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px'],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'button_hover',
                    [
                        'label' => esc_html__('Hover', 'easy-elements'),
                    ]
                 );

                    $this->add_control(
                        'btn_color_hover',
                        [
                            'label' => esc_html__( 'Color', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'background_btn_hover',
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .eel-advance-button:hover, {{WRAPPER}} .eel-advance-button.eel-default::after, {{WRAPPER}} .eel-advance-button-defualt3 .eel-btn-two',
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Border::get_type(),
                        [
                            'name' => 'btn_border_hover',
                            'selector' => '{{WRAPPER}} .eel-advance-button:hover',
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'button_box_shadow_hover',
                            'selector' => '{{WRAPPER}} .eel-advance-button:hover',
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();

        // Icon Style Section
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__('Icon Style', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'button_layout!' => 'badge',
                    'button_style_default!' => ['skin2', 'skin4'],
					'button_icon[value]!' => '',
				],
			]
		 );
            $this->start_controls_tabs('button_icon_styles');
				$this->start_controls_tab(
					'button_icon_normal',
					[
						'label' => esc_html__('Normal', 'easy-elements'),
					]
				 );
                    $this->add_control(
                        'button_icon_color',
                        [
                            'label' => esc_html__('Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .eel-advance-button svg' => 'fill: {{VALUE}};',
                                '{{WRAPPER}} .eel-advance-button svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => esc_html__('Background Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button .eel-button-icon-before-advance,
                                {{WRAPPER}} .eel-advance-button .eel-button-icon-after-advance ' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'button_icon_size',
                        [
                            'label' => esc_html__('Icon Size', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', 'em', 'rem' ],
                            'range' => [
                                'px' => [
                                    'min' => 8,
                                    'max' => 200,
                                ],
                                'em' => [
                                    'min' => 0.5,
                                    'max' => 10,
                                ],
                                'rem' => [
                                    'min' => 0.5,
                                    'max' => 10,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .eel-advance-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
						'icon_width',
						[
							'label' => esc_html__('Width', 'easy-elements'),
							'type' => Controls_Manager::SLIDER,
							'selectors' => [
								'{{WRAPPER}} .eel-advance-button .eel-button-icon-before-advance, {{WRAPPER}} .eel-advance-button .eel-button-icon-after-advance' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'icon_height',
						[
							'label' => esc_html__('Height', 'easy-elements'),
							'type' => Controls_Manager::SLIDER,
							'selectors' => [
								'{{WRAPPER}} .eel-advance-button .eel-button-icon-before-advance, {{WRAPPER}} .eel-advance-button .eel-button-icon-after-advance' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
                    $this->add_responsive_control(
						'icon_border_radius',
						[
							'label' => esc_html__('Border Radius', 'easy-elements'),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => ['px','%'],
							'selectors' => [
								'{{WRAPPER}} .eel-advance-button .eel-button-icon-before-advance, 
								{{WRAPPER}} .eel-advance-button .eel-button-icon-after-advance' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
                    $this->add_responsive_control(
                        'icon_rotate',
                        [
                            'label' => esc_html__('Icon Rotate', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => -360,
                                    'max' => 360,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button i, {{WRAPPER}} .eel-advance-button svg' => 'transform: rotate({{SIZE}}deg); display: inline-block;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

				// Button Icon Hover Style
				$this->start_controls_tab(
					'button_icon_hover',
					[
						'label' => esc_html__('Hover', 'easy-elements'),
					]
				 );
                    $this->add_control(
                        'button_icon_color_hover',
                        [
                            'label' => esc_html__('Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button:hover i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .eel-advance-button:hover svg' => 'fill: {{VALUE}};',
                                '{{WRAPPER}} .eel-advance-button:hover svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'button_bg_color_hover',
                        [
                            'label' => esc_html__('Background Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button:hover .eel-button-icon-before-advance,
                                {{WRAPPER}} .eel-advance-button:hover .eel-button-icon-after-advance' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'icon_hover_rotate',
                        [
                            'label' => esc_html__('Icon Rotate', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => -360,
                                    'max' => 360,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button:hover i, {{WRAPPER}} .eel-advance-button:hover svg' => 'transform: rotate({{SIZE}}deg); display: inline-block;',
                            ],
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();

        $this->end_controls_section();

        // Badge Style Section
		$this->start_controls_section(
			'badge_style_section',
			[
				'label' => esc_html__('Badge Style', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'button_layout' => 'badge',
					'button_badge_text[value]!' => '',
				],
			]
		 );
            $this->start_controls_tabs('button_badge_styles');
				$this->start_controls_tab(
					'button_badge_normal',
					[
						'label' => esc_html__('Normal', 'easy-elements'),
					]
				 );
                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'button_badge_typography',
                            'selector' => '{{WRAPPER}} .eel-advance-button-badge .eel-badge',
                        ]
                    );
                    $this->add_control(
                        'button_badge_color',
                        [
                            'label' => esc_html__('Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button-badge .eel-badge' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'button_badge_bg',
                        [
                            'label' => esc_html__('Background Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button-badge .eel-badge ' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
						'badge_padding',
						[
							'label' => esc_html__('Padding', 'easy-elements'),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => ['px','%'],
							'selectors' => [
								'{{WRAPPER}} .eel-advance-button-badge .eel-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
                    $this->add_responsive_control(
						'badge_margin',
						[
							'label' => esc_html__('Margin', 'easy-elements'),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => ['px','%'],
							'selectors' => [
								'{{WRAPPER}} .eel-advance-button-badge .eel-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
                    $this->add_responsive_control(
						'badge_border_radius',
						[
							'label' => esc_html__('Border Radius', 'easy-elements'),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => ['px','%'],
							'selectors' => [
								'{{WRAPPER}} .eel-advance-button-badge .eel-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
                $this->end_controls_tab();

				// Button Icon Hover Style
				$this->start_controls_tab(
					'button_badge_hover',
					[
						'label' => esc_html__('Hover', 'easy-elements'),
					]
				 );
                    $this->add_control(
                        'button_badge_hover_color',
                        [
                            'label' => esc_html__('Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button-badge:hover .eel-badge' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'button_badge_hover_bg',
                        [
                            'label' => esc_html__('Background Color', 'easy-elements-pro'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-advance-button-badge:hover .eel-badge' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $layout = $settings['button_layout'] ?? 'default';        
        $sticky = !empty($settings['sticky_value']) ? floatval($settings['sticky_value']) : 5;

        // Get link
        $link = !empty($settings['button_link']['url']) ? esc_url($settings['button_link']['url']) : '#';
        $is_external = !empty($settings['button_link']['is_external']) ? ' target="_blank"' : '';
        $nofollow = !empty($settings['button_link']['nofollow']) ? ' rel="nofollow"' : '';

        if ($layout === 'default') {
            $skin = $settings['button_style_default'] ?? 'skin1';
            $template_path = plugin_dir_path(__FILE__) . 'skins/' . $skin . '.php';
        } elseif ($layout === 'badge') {
            $template_path = plugin_dir_path(__FILE__) . 'badge/skin1.php';
        } else {
            $skin = $settings['button_style'] ?? 'skin1';
            $template_path = plugin_dir_path(__FILE__) . 'sticky/' . $skin . '.php';
        }

        if (file_exists($template_path)) {
            include $template_path;
        }
        ?>
        <?php
    }

} ?>