<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Testimonials_Slider__Widget extends \Elementor\Widget_Base {     
    public function get_style_depends() {
        $handle = 'eel-testimonials-slider';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/testimonials-slider.css';
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/testimonials-slider.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-testimonials-slider';
    }

    public function get_title() {
        return esc_html__( 'Testimonials Slider', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'testimonials', 'clients', 'feedback', 'partner' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_layout',
            [
                'label' => esc_html__( 'Layout Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'testimonials_skin',
            [
                'label'   => esc_html__('Skin Type', 'easy-elements'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'skin1',
                'options' => [
                    'default'   => esc_html__('default', 'easy-elements'),
                    'skin1' => esc_html__('Skin 01', 'easy-elements'),
                    'skin2' => esc_html__('Skin 02', 'easy-elements'),
                    'skin3' => esc_html__('Skin 03', 'easy-elements'),
                    'skin4' => esc_html__('Skin 04', 'easy-elements'),
                    'skin5' => esc_html__('Skin 05', 'easy-elements'),
                    'skin6' => esc_html__('Skin 06', 'easy-elements'),
                    'skin7' => esc_html__('Skin 07', 'easy-elements')
                ],
            ]
        );

        $this->add_control(
            'avatar_image_top',
            [
                'label'   => esc_html__('Avatar Image Top', 'easy-elements'),
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'condition' => [
                    'testimonials_skin' => ['skin2'],
                ],
            ]
        );

        $this->add_control(
            'tstml_direction',
            [
                'label' => esc_html__( 'Direction', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'column' => esc_html__( 'Column', 'easy-elements' ),
                    'column-reverse' => esc_html__( 'Column Reverse', 'easy-elements' ),
                    'row' => esc_html__( 'Row', 'easy-elements' ),
                    'row-reverse' => esc_html__( 'Row Reverse', 'easy-elements' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap.skin3' => 'flex-direction: {{VALUE}}; display: inline-flex; gap: 16px;',
                ],
                'condition' => [
                    'testimonials_skin' => 'skin3'
                ]
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Testimonials Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Picture', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'easy-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Stefan Sears', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'designation',
            [
                'label' => esc_html__('Designation', 'easy-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Developer, Easy Elements Inc', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'easy-elements'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Enter Stefan Sears Description', 'easy-elements' ),
            ]
        );

        $repeater->add_control(
           'quote_icon',
            [
               'label'       => esc_html__( 'Icon', 'easy-elements' ),
               'type'        => \Elementor\Controls_Manager::ICONS,
               'label_block' => true,
               'default'     => [
                   'value'   => 'fas fa-quote-right',
                   'library' => 'fa-solid',
               ],
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label'   => esc_html__( 'Rating', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '★☆☆☆☆',
                    '2' => '★★☆☆☆',
                    '3' => '★★★☆☆',
                    '4' => '★★★★☆',
                    '5' => '★★★★★',
                ],
                'default' => '5',
            ]
        );

        $repeater->add_control(
            'logo_company',
            [
                'label' => esc_html__('Company Logo', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [ '' ],
            ]
        );

        $this->add_control(
            'easy_testimonials',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => array_fill( 0, 4, [
                    'image' => [ 'url' => Utils::get_placeholder_image_src() ],
                    'name' => esc_html__( 'Stefan Sears', 'easy-elements' ),
                    'description' => esc_html__( 'This service exceeded all my expectations. The team was professional, fast, and truly cared about delivering a top-notch experience from start to finish.', 'easy-elements' ),
                ]),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', 
                'exclude' => ['custom'],
                'default' => 'full',
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => esc_html__( 'Show Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
        'testimonials_alignment',
        [
            'label' => esc_html__( 'Icon Alignment', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => esc_html__( 'Left', 'easy-elements' ),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__( 'Center', 'easy-elements' ),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => esc_html__( 'Right', 'easy-elements' ),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .ee--tstml-inner-wrap, {{WRAPPER}} .ee--tstml-inner-wrap .eel-description' => 'text-align: {{VALUE}};',
            ],
        ]
    );
    $this->add_control(
            'show_rating',
            [
                'label' => esc_html__( 'Show Rating', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => '',
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


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bg',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap',
            ]
        );


        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap',
            ]
        );


        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_responsive_control(
            'pagination_normal_width',
            [
                'label' => esc_html__( 'Dot Width', 'easy-elements' ),
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
            'pagination_normal_height',
            [
                'label' => esc_html__( 'Dot Height', 'easy-elements' ),
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

        $this->add_control(
            'pagination_dot_border_radius',
            [
                'label' => esc_html__( 'Dot Border Radius', 'easy-elements' ),
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
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-prev svg, {{WRAPPER}} .swiper-prev svg path' => 'fill: {{VALUE}}',                   
                    '{{WRAPPER}} .swiper-next' => 'color: {{VALUE}}',                   
                    '{{WRAPPER}} .swiper-next svg, {{WRAPPER}} .swiper-next svg path' => 'fill: {{VALUE}}',  
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'color: {{VALUE}}',  
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'fill: {{VALUE}}',  
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'color: {{VALUE}}',  
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'fill: {{VALUE}}',  
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'color: {{VALUE}}',  
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
                    '{{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',            
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
                    '{{WRAPPER}} .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; transform: translateY({{SIZE}}{{UNIT}});',
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
                'selector' => '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next',
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
                    '{{WRAPPER}} .swiper-prev:hover svg, {{WRAPPER}} .swiper-prev:hover svg path' => 'fill: {{VALUE}}',                 
                    '{{WRAPPER}} .swiper-next:hover svg, {{WRAPPER}} .swiper-next:hover svg path' => 'fill: {{VALUE}}',                     
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
                    '{{WRAPPER}} .swiper-button-prev:hover' => 'background: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-next:hover' => 'background: {{VALUE}}; border-color: {{VALUE}};',        
                ],
                'condition' => [ 'navigation' => 'yes' ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover',
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

        $this->add_control(
            'arrow_position',
            [
                'label' => esc_html__('Arrow Vertical Position', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'top' => esc_html__('Top', 'easy-elements'),
                    'center' => esc_html__('Center', 'easy-elements'),
                    'bottom' => esc_html__('Bottom', 'easy-elements'),
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_name',
            [
                'label' => esc_html__( 'Name', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-name, {{WRAPPER}} .ee--tstml-wrap-skin-style-two .eel-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap .eel-name, {{WRAPPER}} .ee--tstml-wrap-skin-style-two .eel-name',
            ]
        );
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __('Margin', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-author-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_designation',
            [
                'label' => esc_html__( 'Designation', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'designation_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-designation, {{WRAPPER}} .ee--tstml-wrap-skin-style-two .eel-designation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'designation_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap .eel-designation, {{WRAPPER}} .ee--tstml-wrap-skin-style-two .eel-designation',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description, {{WRAPPER}} .ee--tstml-wrap-skin-style-two .eel-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description, {{WRAPPER}} .ee--tstml-wrap-skin-style-two .eel-description',
            ]
        );

        $this->add_responsive_control(
            'des_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-description' => 
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bg_description',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eel-description',
            ]
        );

        $this->add_responsive_control(
            'des_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-description' => 
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius_description',
            [
                'label' => __('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eel-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'author_image',
            [
                'label' => esc_html__( 'Author Image Settings', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'author_image_size',
            [
                'label'      => esc_html__( 'Image Size', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-author-wrap .eel-picture img, {{WRAPPER}} .ee--tstml-inner-wrap .eel-picture' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; min-width:{{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

         $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-picture' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['easy_testimonials'] ) ) {
            return;
        }
        include plugin_dir_path( __FILE__ ) . 'slider.php';        
    ?>
        <?php
            $settings = $this->get_settings_for_display();
            $arrow_custom_icon = (!empty($settings['arrow_prev_icon']['value'])) ? 'easy--custom-navigation-icon' : '' ;
                if ( $settings['arrow_position'] == 'top' ) { 
                echo '<div class="swiper-navigation-top ' . esc_attr( $arrow_custom_icon ) . '">';
                if ( 'yes' === $settings['ee_customicon_'] ) {
                    echo '<div class="swiper-navigation">';
                    if ( ! empty( $settings['arrow_prev_icon']['value'] ) ) {
                        echo '<div class="swiper-button-prev swiper-prev">';
                        \Elementor\Icons_Manager::render_icon( $settings['arrow_prev_icon'], [ 'aria-hidden' => 'true' ] );
                        echo '</div>';
                    }

                    if ( ! empty( $settings['arrow_next_icon']['value'] ) ) {
                        echo '<div class="swiper-button-next swiper-next">';
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
                echo '</div>';
            }
        ?>
        <div id="<?php echo esc_attr($unique_id); ?>" class="e-e-testimonial swiper eel-all-slider" <?php echo $slider_data_attributes; ?>>
            
            <div class="swiper-wrapper">
                <?php foreach ( $settings['easy_testimonials'] as $item ) :
                    $image_id = $item['image']['id'] ?? '';
                    $image_size = $settings['image_size'] ?? 'full';
                    if ( $image_id ) {
                        $image_data = wp_get_attachment_image_src( $image_id, $image_size );
                        $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                        $title = get_the_title( $image_id );
                    } else {
                        $fallback_url = Utils::get_placeholder_image_src();
                        $image_data = [ $fallback_url, 600, 400 ];
                        $alt = esc_attr__( 'Sample Image', 'easy-elements' );
                        $title = esc_attr__( 'Sample Image', 'easy-elements' );
                    }

                    $skin = $settings['testimonials_skin'] ?? 'default';
                    $template_path = plugin_dir_path(__FILE__) . 'skins/' . $skin . '.php';
                    ?>
                    <div class="swiper-slide">
                        <div class="ee--testimonial">                            
                            <?php 
                                if ( file_exists( $template_path ) ) {
                                    include $template_path;
                                }
                            ?>                            
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>         
        </div>
        <?php if ( $settings['pagination'] !== 'none' ) : ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
        <?php
            $settings = $this->get_settings_for_display();
            if ( $settings['arrow_position'] != 'top' ) {                        
                if ( 'yes' === $settings['ee_customicon_'] ) {
                    echo '<div class="swiper-navigation ' . esc_attr( $arrow_custom_icon ) . '">';
                    if ( ! empty( $settings['arrow_prev_icon']['value'] ) ) {
                        echo '<div class="swiper-button-prev swiper-prev">';
                        \Elementor\Icons_Manager::render_icon( $settings['arrow_prev_icon'], [ 'aria-hidden' => 'true' ] );
                        echo '</div>';
                    }

                    if ( ! empty( $settings['arrow_next_icon']['value'] ) ) {
                        echo '<div class="swiper-button-next swiper-next">';
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
            }
    }
} 