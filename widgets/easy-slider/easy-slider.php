<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Easy_Slider_Widget extends \Elementor\Widget_Base {   

    public function get_style_depends() {
        $handle = 'eel-easy-slider';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/easy-slider.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/easy-slider.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-easy-slider';
    }

    public function get_title() {
        return esc_html__( 'Easy Hero Slider', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'easy', 'slider', 'text' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Slider Content Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_image',
            [
                'label' => esc_html__( 'Slide Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'slide_image',
                'default' => 'large',
            ]
        );
         $repeater->add_control(
            'sub__title',
            [
                'label' => esc_html__( 'Sub Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( '', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            '_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Welcome to Our Hero Section', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Create stunning hero sections with beautiful images, compelling titles, and engaging descriptions. Add call-to-action buttons to drive conversions.', 'easy-elements' ),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => esc_html__( 'Button Text', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Learn More', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'slide_link',
            [
                'label' => esc_html__('Slide Link (Optional)', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'description' => esc_html__('Make entire slide clickable', 'easy-elements'),
            ]
        );


        $this->add_control(
            'easy_icon_box',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ _title }}}',
                'default' => [
                    [
                        'slide_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        '_title' => esc_html__( 'Welcome to Our Hero Section', 'easy-elements' ),
                        '_description' => esc_html__( 'Create stunning hero sections with beautiful images, compelling titles, and engaging descriptions. Add call-to-action buttons to drive conversions.', 'easy-elements' ),
                        'button_text' => esc_html__( 'Learn More', 'easy-elements' ),
                        'button_link' => ['url' => '#'],
                        'slide_link' => ['url' => ''],
                    ],

                    [
                        'slide_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        '_title' => esc_html__( 'Amazing Features & Benefits', 'easy-elements' ),
                        '_description' => esc_html__( 'Discover the amazing features and benefits that make our product stand out from the competition. Experience excellence in every detail.', 'easy-elements' ),
                        'button_text' => esc_html__( 'Get Started', 'easy-elements' ),
                        'button_link' => ['url' => '#'],
                        'slide_link' => ['url' => ''],
                    ],

                    [
                        'slide_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        '_title' => esc_html__( 'Premium Quality Service', 'easy-elements' ),
                        '_description' => esc_html__( 'We provide premium quality services that exceed expectations. Our team is dedicated to delivering excellence in everything we do.', 'easy-elements' ),
                        'button_text' => esc_html__( 'Contact Us', 'easy-elements' ),
                        'button_link' => ['url' => '#'],
                        'slide_link' => ['url' => ''],
                    ],
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

        $this->add_responsive_control(
            'text_alignment',
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .hero-content-wrapper' => 'text-align: {{VALUE}};',
                ],
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
                 'default' => 'yes',
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
            'default' => 'yes',
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
           'space_between',
           [
               'label' => esc_html__('Space Between (px)', 'easy-elements'),
               'type' => \Elementor\Controls_Manager::NUMBER,
               'default' => 0,
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
            'arrow_alignment',
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

        $this->add_responsive_control(
            'paginationleft_right_spacing',
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
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'pagination' => ['bullets', 'fraction']
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; left: auto; transform: translateX({{SIZE}}{{UNIT}});',                 
                ],
            ]
        );
         
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'color: {{VALUE}}; opacity: .5;',
                    '{{WRAPPER}} .eel-pagination-number-yes.swiper-pagination span.swiper-pagination-bullet-active::after' => 'background: {{VALUE}};',                    
                ],
                'condition' => [
                   'number_mode_enable' => 'yes'
                ],
            ]
        ); 

        $this->add_control(
            'number_color_color',
            [
                'label' => esc_html__( 'Number Color (Active)', 'easy-elements' ),
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
                     '{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
                     
                 ],
                 'condition' => [
                    'pagination' => ['bullets', 'fraction']
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
                     '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}}',
                 ],
                 'condition' => [
                     'pagination' => ['bullets', 'fraction']
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

        $this->add_control(
            'effect',
            [
                'label' => esc_html__('Transition Effect', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__('Slide', 'easy-elements'),
                    'fade' => esc_html__('Fade', 'easy-elements'),
                    'cube' => esc_html__('Cube', 'easy-elements'),
                    'coverflow' => esc_html__('Coverflow', 'easy-elements'),
                    'flip' => esc_html__('Flip', 'easy-elements'),
                    'cards' => esc_html__('Cards', 'easy-elements'),
                    'creative' => esc_html__('Creative', 'easy-elements'),
                ],
            ]
        );

        $this->add_control(
            'cube_effect_shadow',
            [
                'label' => esc_html__('Cube Shadow', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'easy-elements'),
                'label_off' => esc_html__('No', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'effect' => 'cube',
                ],
            ]
         );

         $this->add_control(
            'coverflow_rotate',
            [
                'label' => esc_html__('Coverflow Rotate', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 50,
                ],
                'condition' => [
                    'effect' => 'coverflow',
                ],
            ]
        );

        $this->add_control(
            'coverflow_stretch',
            [
                'label' => esc_html__('Coverflow Stretch', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => [
                    'effect' => 'coverflow',
                ],
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
                     '{{WRAPPER}} .swiper-navigation .swiper-next svg' => 'fill: {{VALUE}}',
                     '{{WRAPPER}} .swiper-navigation .swiper-next svg path' => 'fill: {{VALUE}}',
                     '{{WRAPPER}} .swiper-navigation .swiper-prev svg' => 'fill: {{VALUE}}',     
                     '{{WRAPPER}} .swiper-navigation .swiper-prev svg path' => 'fill: {{VALUE}}',                
                     '{{WRAPPER}} .swiper-navigation .swiper-prev' => 'color: {{VALUE}}',
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
                     '{{WRAPPER}} .swiper-prev' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                     '{{WRAPPER}} .swiper-next' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',            
                     '{{WRAPPER}} .swiper-navigation .swiper-next' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                     '{{WRAPPER}} .swiper-navigation .swiper-prev' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                 ],
                 'condition' => [ 'navigation' => 'yes' ],
             ]
        );
        $this->add_control(
            'arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-navigation .swiper-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-navigation .swiper-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-navigation-top .swiper-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-navigation-top .swiper-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrow_width',
            [
                'label' => esc_html__('Width & Height', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    '%' => [ 'min' => 5, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-navigation .swiper-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-navigation .swiper-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrow_left_right_position',
            [
                'label' => esc_html__('Arrow Horizontal Offset Left', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-navigation .swiper-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_left_right_position_right',
            [
                'label' => esc_html__('Arrow Horizontal Offset Right', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-navigation .swiper-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_top_bottom_position',
            [
                'label' => esc_html__('Arrow Vertical Offset', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-navigation .swiper-next, {{WRAPPER}} .swiper-navigation .swiper-prev' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-next' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrow_box_shadow ',
                'selector' => '{{WRAPPER}} .swiper-navigation .swiper-next, {{WRAPPER}} .swiper-navigation .swiper-prev',
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
                     '{{WRAPPER}} .swiper-navigation .swiper-next:hover' => 'color: {{VALUE}}',
                     '{{WRAPPER}} .swiper-navigation .swiper-prev:hover' => 'color: {{VALUE}}',
                     '{{WRAPPER}} .swiper-navigation .swiper-next:hover svg path' => 'fill: {{VALUE}}',
                     '{{WRAPPER}} .swiper-navigation .swiper-prev:hover svg path' => 'fill: {{VALUE}}',
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
                     '{{WRAPPER}} .swiper-navigation .swiper-next:hover' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                     '{{WRAPPER}} .swiper-navigation .swiper-prev:hover' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                     '{{WRAPPER}} .swiper-next:hover' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                     '{{WRAPPER}} .swiper-prev:hover' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                 ],
                 'condition' => [ 'navigation' => 'yes' ],
             ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .swiper-navigation .swiper-button-prev:hover, {{WRAPPER}} .swiper-navigation .swiper-button-next:hover',
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

        // Hero Slider Styling Section
        $this->start_controls_section(
            'hero_slider_styling',
            [
                'label' => esc_html__( 'Hero Slider Style', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__( 'Container Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1920,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-content-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin: 0 auto;',
                ],
            ]
        );
        

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .eel-hero-slide .hero-slide-content',
			]
		);

        $this->add_responsive_control(
            'slide_height',
            [
                'label' => esc_html__( 'Slide Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', '%' ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-hero-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Content Area Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-hero-slide .hero-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Sub Title Styling
        $this->start_controls_section(
            'sub_title_styling',
            [
                'label' => esc_html__( 'Sub Title Styling', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .hero-slide-sub-title',
            ]
        );

        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__( 'Sub Title Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__( 'Sub Title Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'sub_title_padding',
            [
                'label' => esc_html__( 'Sub Title Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'sub_title_border',
                'label' => esc_html__( 'Sub Title Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .hero-slide-sub-title',
            ]
        );

        // Border Radius (optional)
        $this->add_control(
            'sub_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-sub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
		    'show_gradient_border',
		    [
		        'label' => esc_html__( 'Show Gradient Border', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => esc_html__( 'Show', 'easy-elements' ),
		        'label_off' => esc_html__( 'Hide', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		    ]
		);

		$this->add_control(
			'gradient_color_1',
			[
				'label' => esc_html__( 'Gradient Color 1', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.15)',
				'selectors' => [
					'{{WRAPPER}} .hero-slide-sub-title.easy_gradiant_border::before' => '--grad-color-1: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'gradient_color_2',
			[
				'label' => esc_html__( 'Gradient Color 2', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.30)',
				'selectors' => [
					'{{WRAPPER}} .hero-slide-sub-title.easy_gradiant_border::before' => '--grad-color-2: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'gradient_color_3',
			[
				'label' => esc_html__( 'Gradient Color 3', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.50)',
				'selectors' => [
					'{{WRAPPER}} .hero-slide-sub-title.easy_gradiant_border::before' => '--grad-color-3: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		

		$this->add_responsive_control(
		    'sub_gradient_border_padding',
		    [
		        'label' => __('Gradient Border Padding', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors' => [
		            '{{WRAPPER}} .hero-slide-sub-title.easy_gradiant_border' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
		    ]
		);

		$this->add_responsive_control(
			'gradient_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .hero-slide-sub-title.easy_gradiant_border::before' => '--grad-border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		

        $this->end_controls_section();

        // Title Styling
        $this->start_controls_section(
            'title_styling',
            [
                'label' => esc_html__( 'Title Styling', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .hero-slide-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Title Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Description Styling
        $this->start_controls_section(
            'description_styling',
            [
                'label' => esc_html__( 'Description Styling', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .hero-slide-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-description' => 'color: {{VALUE}};',
                ],
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Description Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-slide-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Styling
        $this->start_controls_section(
            'button_styling',
            [
                'label' => esc_html__( 'Button Styling', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .hero-btn',
            ]
        );

        $this->start_controls_tabs( 'button_styles' );

        $this->start_controls_tab(
            'button_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Button Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hero-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['easy_icon_box'] ) ) {
            return;
        }
        include plugin_dir_path( __FILE__ ) . 'slider.php';
        ?>
        <div id="<?php echo esc_attr($unique_id); ?>" class="eel-icon-box-wrap swiper eel-all-slider" <?php echo $slider_data_attributes; ?>>
        <div class="swiper-wrapper">
                <?php foreach ( $settings['easy_icon_box'] as $item ) :
                    $slide_link = $item['slide_link']['url'] ?? '';
                    $slide_target = ! empty( $item['slide_link']['is_external'] ) ? ' target="_blank"' : '';
                    $slide_nofollow = ! empty( $item['slide_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    
                    $button_link = $item['button_link']['url'] ?? '';
                    $button_target = ! empty( $item['button_link']['is_external'] ) ? ' target="_blank"' : '';
                    $button_nofollow = ! empty( $item['button_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    ?>
                    <div class="swiper-slide">
                        <div class="eel-hero-slide">
                            <?php if ( $slide_link ) : ?>
                                <a href="<?php echo esc_url( $slide_link ); ?>"<?php echo $slide_target . $slide_nofollow; ?> class="slide-link">
                            <?php endif; ?>
                            
                            <div class="hero-slide-background">
                                <?php if ( ! empty( $item['slide_image']['url'] ) ) : ?>
                                    <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'slide_image' ); ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="hero-slide-content">
                                <div class="hero-content-wrapper">
                                    <?php if ( ! empty( $item['sub__title'] ) ) : ?>
                                            <div class="hero-slide-sub-title <?php echo esc_attr($settings['show_gradient_border'] == 'yes' ? 'easy_gradiant_border' : ''); ?>"><?php echo wp_kses_post( $item['sub__title'] ); ?></div>
                                        <?php endif; ?>
                                    <?php if ( ! empty( $item['_title'] ) ) :
                                        $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h2'; ?>
                                        <<?php echo esc_attr( $title_tag ); ?> class="hero-slide-title">
                                            <?php echo wp_kses_post( $item['_title'] ); ?>
                                        </<?php echo esc_attr( $title_tag ); ?>>
                                    <?php endif; ?>
                                    <div class="des-btn-wrap">      
                                        <?php if ( ! empty( $item['_description'] ) ) : ?>
                                            <div class="hero-slide-description"><?php echo wp_kses_post( $item['_description'] ); ?></div>
                                        <?php endif; ?>

                                        <?php if ( ! empty( $item['button_text'] ) && ! empty( $button_link ) ) : ?>
                                            <div class="hero-slide-button">
                                                <a href="<?php echo esc_url( $button_link ); ?>"<?php echo $button_target . $button_nofollow; ?> class="hero-btn">
                                                    <?php echo esc_html( $item['button_text'] ); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ( $slide_link ) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>           
        </div>
        <?php if ( $settings['pagination'] !== 'none' ) : ?>
                <div class="swiper-pagination eel-pagination-number-<?php echo $settings['number_mode_enable']; ?>"></div>
            <?php endif; ?>

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
        <?php
    }
} ?>