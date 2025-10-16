<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Team_Slider__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-team-slider';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/team-slider.css';

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/team-slider.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-team-slider';
    }

    public function get_title() {
        return esc_html__( 'Team Slider', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'member', 'team', 'brand', 'partner', 'image' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_team',
            [
                'label' => esc_html__( 'Team Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'team_skin',
            [
                'label'   => esc_html__('Skin Type', 'easy-elements'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'skin1',
                'options' => [
                    'skin1' => esc_html__('Skin 01', 'easy-elements'),
                    'skin2' => esc_html__('Skin 02', 'easy-elements'),
                ],
            ]
        );

        $this->add_control(
            'team_content_part_position',
            [
                'label'   => esc_html__('Content Part Position', 'easy-elements'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__('Top', 'easy-elements'),
                    'bottom' => esc_html__('Bottom', 'easy-elements'),
                ],
                'condition' => [
                    'team_skin' => 'skin1',
                ],
            ]
        );

        // Repeater
        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            '_name',
            [
                'label' => esc_html__( 'Name', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Harry Nelson', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'designation',
            [
                'label' => esc_html__('Designation', 'easy-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Head of Operations', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'socials',
            [
                'label' => esc_html__('Social Icons', 'easy-elements'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'icon',
                        'label' => esc_html__('Icon', 'easy-elements'),
                        'type' => Controls_Manager::ICONS,
                        'label_block' => true,
                        'default' => [
                            'value' => 'fab fa-facebook-f',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'name' => 'url',
                        'label' => esc_html__('URL', 'easy-elements'),
                        'type' => Controls_Manager::URL,
                        'placeholder' => 'https://facebook.com/',
                    ],
                ],
                'title_field' => '{{{ icon.value }}}',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'description' => esc_html__('You can add a page link here, such as the team member’s profile page.', 'easy-elements'),
            ]
        );

        $this->add_control(
            'easy_team_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ image.url }}}',
                'default' => array_fill(0, 4, [
                    'image' => ['url' => Utils::get_placeholder_image_src()],
                ]),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h4',
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


        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', 
                'exclude' => ['custom'],
                'default' => 'full',
            ]
        );

        $this->add_control(
            'fetchpriority',
            [
                'label' => __('Image Fetch Priority', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''      => __('Default', 'easy-elements'),
                    'high'  => __('High', 'easy-elements'),
                    'low'   => __('Low', 'easy-elements'),
                ],
                'default' => 'low',
            ]
        );

        $this->add_control(
            'image_overlay_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div class="eel-popup-note">' . esc_html__('Note: The Image Overlay supports both classic (solid color) and gradient backgrounds.', 'easy-elements') . '</div>',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_overlay',
                'label' => esc_html__('Image Overlay', 'easy-elements'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ee--team-img .eel-image-overlay',
            ]
        );

        
        $this->add_control(
            'items_margin',
            [
                'label' => esc_html__( 'Items Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-team-wraps .swiper-slide:nth-child(even)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                     '{{WRAPPER}} .swiper-prev svg, {{WRAPPER}} .swiper-prev svg path' => 'fill: {{VALUE}}',                   
                     '{{WRAPPER}} .swiper-next' => 'color: {{VALUE}}',                   
                     '{{WRAPPER}} .swiper-next svg, {{WRAPPER}} .swiper-next svg path' => 'fill: {{VALUE}}',                   
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
            'section_item_per',
            [
                'label' => esc_html__( 'Team Item', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'team_wrap_background_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Note:</strong> This background will only apply to the area containing the team member\'s name and designation.', 'easy-elements' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'item_image_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--team-img .eel-team-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'team_wrap_background',
                'label'    => __( 'Background (Name & Designation Area)', 'easy-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-name-deg-wrap',
            ]
        );
        $this->add_control(
            'item_bdr_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--team-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'social_icon_border',
                'label' => esc_html__( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--team-img',
            ]
        );

        $this->add_responsive_control(
			'content_name_padding',
			[
				'label' => esc_html__('Content Part Padding', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'content_name_margin',
			[
				'label' => esc_html__('Content Part Margin', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item',
            [
                'label' => esc_html__( 'Name & Designation Area', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            '_bdr_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_name',
            [
                'label' => esc_html__( 'Name', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-name',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_designation',
            [
                'label' => esc_html__( 'Designation', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'designation_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-designation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'designation_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-designation',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_socials',
            [
                'label' => esc_html__( 'Social Share', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_share_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-team-social-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_share_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-team-social-link' => 'background-color: {{VALUE}};',                    
                ],
            ]
        );
        $this->add_control(
            'social_share_hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-team-social-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'social_share_hover_bg',
            [
                'label' => esc_html__( 'Hover Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-team-social-link:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_share_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-team-social-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_share_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-team-social-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_share_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-team-socials' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['easy_team_list'] ) ) {
            return;
        }
        include plugin_dir_path( __FILE__ ) . 'slider.php';
        
        ?>
        
        <div id="<?php echo esc_attr($unique_id); ?>" class="eel-team-wraps swiper eel-all-slider <?php echo esc_attr( $settings['team_skin'] ); ?> <?php echo esc_attr( $settings['team_content_part_position'] ); ?>" <?php echo $slider_data_attributes; ?>>
            <div class="swiper-wrapper">
                <?php foreach ( $settings['easy_team_list'] as $item ) :
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

                    $link     = ! empty( $item['link']['url'] ) ? $item['link']['url'] : '';
                    $target   = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';

                    $fetchpriority = $settings['fetchpriority'] ?? '';
                    ?>
                    <div class="swiper-slide">
                        <div class="ee--team-img">                              
                            <?php 
                                if( $settings['team_skin'] === 'skin2' ) {                                   
                                if ( ! empty( $item['socials'] ) && is_array( $item['socials'] ) ) : ?>
                                <div class="eel-team-socials">
                                    <?php foreach ( $item['socials'] as $social ) :
                                        if ( empty( $social['icon']['value'] ) ) continue;
                                        $icon = $social['icon'];
                                        $url = $social['url']['url'] ?? '';
                                        $target = ! empty( $social['url']['is_external'] ) ? ' target="_blank"' : '';
                                        $nofollow = ! empty( $social['url']['nofollow'] ) ? ' rel="nofollow"' : '';
                                    ?>
                                        <a href="<?php echo esc_url( $url ); ?>"<?php echo $target . $nofollow; ?> class="eel-team-social-link">
                                            <?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; } ?>
                            <?php if ( $image_data ) : ?>
                                <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>"<?php echo $target . $nofollow; ?>>
                            <?php endif; ?> 
                                <img class="eel-team-img"
                                src="<?php echo esc_url( $image_data[0] ); ?>"
                                width="<?php echo esc_attr( $image_data[1] ); ?>"
                                height="<?php echo esc_attr( $image_data[2] ); ?>"
                                alt="<?php echo esc_attr( $alt ); ?>"
                                title="<?php echo esc_attr( $title ); ?>"
                                loading="lazy"
                                decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
                                <?php if ( $link ) : ?>
                                    </a>
                                <?php endif; ?>
                                <div class="eel-image-overlay"></div>
                            <?php endif; ?>   
                            <div class="eel-name-deg-wrap">
                                <?php if ( ! empty( $item['_name'] ) ) :
                                    $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h4'; ?>
                                    <<?php echo esc_attr( $title_tag ); ?> class="eel-name">
                                        <?php if ( $link ) : ?>
                                            <a href="<?php echo esc_url( $link ); ?>"<?php echo $target . $nofollow; ?>>
                                        <?php endif; ?> 
                                        <?php echo esc_html( $item['_name'] ); ?>
                                        <?php if ( $link ) : ?>
                                            </a>
                                        <?php endif; ?>
                                    </<?php echo esc_attr( $title_tag ); ?>>
                                <?php endif; ?>
                               
                                <?php if ( ! empty( $item['designation'] ) ) : ?>
                                    <div class="eel-designation"><?php echo esc_html( $item['designation'] ); ?></div>
                                <?php endif; ?>                   
                                <?php 
                                    if( $settings['team_skin'] === 'skin1' ) {                                   
                                    if ( ! empty( $item['socials'] ) && is_array( $item['socials'] ) ) : ?>
                                    <div class="eel-team-socials">
                                        <?php foreach ( $item['socials'] as $social ) :
                                            if ( empty( $social['icon']['value'] ) ) continue;
                                            $icon = $social['icon'];
                                            $url = $social['url']['url'] ?? '';
                                            $target = ! empty( $social['url']['is_external'] ) ? ' target="_blank"' : '';
                                            $nofollow = ! empty( $social['url']['nofollow'] ) ? ' rel="nofollow"' : '';
                                        ?>
                                            <a href="<?php echo esc_url( $url ); ?>"<?php echo $target . $nofollow; ?> class="eel-team-social-link">
                                                <?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; } ?>
                            </div>                           
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
                <div class="swiper-pagination eel-pagination-number-<?php echo esc_attr( $settings['number_mode_enable'] ); ?>"></div>
            <?php endif; ?>
        <?php
    }
} 