<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Timeline_Slider_Widget extends \Elementor\Widget_Base {   

    public function get_style_depends() {
        $handle = 'eel-timeline-slider';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/timeline.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/timeline.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-timeline-slider';
    }

    public function get_title() {
        return esc_html__( 'Easy Timeline Slider', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'timeline', 'sevice', 'icon', 'timeline-box', 'text' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
        '_section_logo',
        [
            'label' => esc_html__( 'Timeline Settings', 'easy-elements' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            '_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '2013', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Company Founded', 'easy-elements' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'easy_titmeline_repeater',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ _title }}}',
                'default' => [
                    [
                        '_title' => esc_html__( '2013', 'easy-elements' ),
                        '_description' => esc_html__( 'Company Founded', 'easy-elements' ),
                        'link' => ['url' => ''],
                    ],
                    [
                        '_title' => esc_html__( '2015', 'easy-elements' ),
                        '_description' => esc_html__( 'First Product Launch', 'easy-elements' ),
                        'link' => ['url' => ''],
                    ],
                    [
                        '_title' => esc_html__( '2018', 'easy-elements' ),
                        '_description' => esc_html__( 'Expanded to International Market', 'easy-elements' ),
                        'link' => ['url' => ''],
                    ],
                    [
                        '_title' => esc_html__( '2021', 'easy-elements' ),
                        '_description' => esc_html__( 'Reached 1M Customers', 'easy-elements' ),
                        'link' => ['url' => ''],
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

        // Image Alignment
        $this->add_responsive_control(
            'all_alignment',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ee--time-line-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_image',
            [
                'label' => esc_html__( 'Separator Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => plugin_dir_url( __FILE__ ) . 'img/divider.png',
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
            'section_style_image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 20, 'max' => 800 ],
                    '%'  => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--time-line-box img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--time-line-box img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%'  => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--time-line-box img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .time-line-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .time-line-title',
            ]
        );        

        $this->add_responsive_control(
            'item_padding_title_part',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .time-line-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .time-line-description' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_desc_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .time-line-description',
            ]
        );

        $this->add_responsive_control(
            'desc_padding_inner',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .time-line-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $separator_image = $settings['separator_image']['url'];

        if ( empty( $settings['easy_titmeline_repeater'] ) ) {
            return;
        }
        include plugin_dir_path( __FILE__ ) . 'slider.php';
        ?>
        <?php
            $settings = $this->get_settings_for_display();
                if ( $settings['arrow_position'] == 'top' ) { 
                echo '<div class="swiper-navigation-top">';
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
                echo '</div>';
            }
        ?>
        <div id="<?php echo esc_attr($unique_id); ?>" class="eel-time-line-wrap swiper eel-all-slider" <?php echo $slider_data_attributes; ?>>
        <div class="swiper-wrapper">
                <?php foreach ( $settings['easy_titmeline_repeater'] as $index => $item ) :
                    $link     = $item['link']['url'] ?? '';
                    $target   = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';

                    
                    ?>
                    <div class="swiper-slide">
                        <div class="eel-time-line-item">
                            <div class="ee--time-line-box">
                                <?php 
                                // Link data safely
                                $link     = $item['link']['url'] ?? '';
                                $target   = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                $nofollow = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';

                                if ( $link ) : ?>
                                    <a href="<?php echo esc_url( $link ); ?>"<?php echo $target . $nofollow; ?>>
                                <?php endif; ?>

                                <?php 
                                    $image = $item['image'] ?? [];
                                    $image_id = $image['id'] ?? '';

                                    if ( $image_id ) {
                                        echo wp_get_attachment_image(
                                            intval( $image_id ),
                                            'full', // full size
                                            false,
                                            ['class' => 'timeline-image']
                                        );
                                    }
                                    // URL fallback
                                    elseif ( ! empty( $image['url'] ) ) {
                                        ?>
                                        <img src="<?php echo esc_url( $image['url'] ); ?>" 
                                            alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>" 
                                            class="timeline-image" />
                                        <?php
                                    }
                                ?>

                                <?php 
                                // Title
                                $title_tag = $settings['title_tag'] ?? 'h3';
                                if ( ! empty( $item['_title'] ) ) : ?>
                                    <<?php echo esc_attr( $title_tag ); ?> class="time-line-title">
                                        <?php echo esc_html( $item['_title'] ); ?>
                                    </<?php echo esc_attr( $title_tag ); ?>>
                                <?php endif; ?>

                                <?php 
                                // Description
                                if ( ! empty( $item['_description'] ) ) : ?>
                                    <div class="time-line-description"><?php echo esc_html( $item['_description'] ); ?></div>
                                <?php endif; ?>

                                <?php if ( $link ) : ?></a><?php endif; ?>
                            </div>
                            <?php if ( ! empty( $separator_image ) && $index !== array_key_last( $settings['easy_titmeline_repeater'] ) ) : ?>
                                <div class="eel-timeline-separator">
                                    <img src="<?php echo esc_url( $separator_image ); ?>" alt="">
                                </div>
                            <?php endif; ?>

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
                }
            ?>
        <?php
    }
} ?>