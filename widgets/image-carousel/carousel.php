<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Image_Carousel__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-image-carousel';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/image-carousel.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/image-carousel.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-image-carousel';
    }

    public function get_title() {
        return esc_html__( 'Easy Image Carousel', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'image', 'clients', 'brand', 'partner' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_image',
            [
                'label' => esc_html__( 'Images Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
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
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'easy_image_ca_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ image.url }}}',
                'default' => array_fill(0, 4, [
                    'image' => ['url' => Utils::get_placeholder_image_src()],
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

        $this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Image Height', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--image-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
				],
			]
		);

        $this->add_control(
            'item__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .e-e-grid-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'effect',
            [
                'label' => esc_html__('Slide Animation', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'description' => esc_html__('Choose the animation effect for slide transitions. Some effects may not support all features (e.g., fade disables slidesPerView > 1).', 'easy-elements'),
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

        $this->add_control(
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

        $this->add_control(
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

        $this->add_control(
            'mousewheel',
            [
                'label' => esc_html__('Enable Mousewheel', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'easy-elements'),
                'label_off' => esc_html__('No', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__('Allow navigation with mouse wheel.', 'easy-elements'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['easy_image_ca_list'] ) ) {
            return;
        }       
        include plugin_dir_path( __FILE__ ) . 'slider.php';
        ?>
        
        <div id="<?php echo esc_attr($unique_id); ?>" class="eel-image-carousel swiper eel-all-slider" <?php echo $slider_data_attributes; ?>>
            <div class="swiper-wrapper">
                <?php foreach ( $settings['easy_image_ca_list'] as $item ) :

                       $image_id = $item['image']['id'] ?? '';
                       $image_size = isset( $settings['image_size'] ) ? $settings['image_size'] : 'full';
                       $image_data = $image_id ? wp_get_attachment_image_src( $image_id, $image_size ) : '';
                       $alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                       $title = $image_id ? get_the_title( $image_id ) : '';

                       $link     = $item['link']['url'] ?? '';
                       $target   = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                       $nofollow = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                       $fetchpriority = $settings['fetchpriority'] ?? '';
                       ?>
                       <div class="swiper-slide">
                           <div class="eel--image-img">
                               <?php if ( $link ) : ?>
                                   <a href="<?php echo esc_url( $link ); ?>"<?php echo $target . $nofollow; ?>>
                               <?php endif; ?>

                               <?php if ( $image_data ) : ?>
                                   <img class="e-e-grid-img"
                                        src="<?php echo esc_url( $image_data[0] ); ?>"
                                        width="<?php echo esc_attr( $image_data[1] ); ?>"
                                        height="<?php echo esc_attr( $image_data[2] ); ?>"
                                        alt="<?php echo esc_attr( $alt ); ?>"
                                        title="<?php echo esc_attr( $title ); ?>"
                                        loading="lazy"
                                        decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
                               <?php endif; ?>

                               <?php if ( $link ) : ?>
                                   </a>
                               <?php endif; ?>
                           </div>
                       </div>
                   <?php endforeach; ?>
            </div>
            <?php if ( $settings['pagination'] !== 'none' ) : ?>
                <div class="swiper-pagination"></div>
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
        </div>
        <?php
    }
} ?>