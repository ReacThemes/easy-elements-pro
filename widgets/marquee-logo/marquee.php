<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || die();
class Easyel_Marquee_Logo__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-marquee';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/marquee.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/marquee.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-marquee';
    }

    public function get_title() {
        return esc_html__( 'Easy Marquee Logo', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'marquee', 'logo', 'brand', 'partner', 'image' ];
    }

    protected function register_controls() {
        $logo_repeater = new \Elementor\Repeater();

        $logo_repeater->add_control(
            'content_type',
            [
                'label' => esc_html__('Content Type', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'image' => [
                        'title' => esc_html__('Logo', 'easy-elements'),
                        'icon' => 'eicon-image-bold',
                    ],
                    'title' => [
                        'title' => esc_html__('Title', 'easy-elements'),
                        'icon' => 'eicon-editor-bold',
                    ],
                ],
                'default' => 'image',
                'toggle' => true,
            ]
        );
        
        $logo_repeater->add_control(
            'title_text',
            [
                'label' => esc_html__('Title Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter title...', 'easy-elements'),
                'condition' => [
                    'content_type' => 'title',
                ],
            ]
        );

        $logo_repeater->add_control(
            'title_icon',
            [
                'label' => esc_html__('Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid',
                ],
                'condition' => [
                    'content_type' => 'title',
                ],
            ]
        );
        
        
        $logo_repeater->add_control(
            'image',
            [
                'label' => esc_html__('Logo', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'content_type' => 'image',
                ],
            ]
        );
        
        $logo_repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'description' => esc_html__('You can add a page link here, such as the logo page.', 'easy-elements'),
            ]
        );

        $row_repeater = new \Elementor\Repeater();
        $row_repeater->add_control(
            'logos',
            [
                'label' => esc_html__('Logos', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $logo_repeater->get_controls(),
                'title_field' => '{{{ image.url }}}',
                'default' => array_fill(0, 4, [
                    'image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    'link' => [],
                ]),
            ]
        );

        $this->start_controls_section(
            '_section_marquee',
            [
                'label' => esc_html__( 'Marquee Settings', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__('Rows', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $row_repeater->get_controls(),
                'title_field' => esc_html__('Row', 'easy-elements'),
                'default' => array_fill(0, 2, [
                    'logos' => array_fill(0, 2, [
                        'image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                        'link' => [],
                    ]),
                ]),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => ['custom'],
                'default' => 'full',
            ]
        );
        $this->add_control(
            'marquee_duration',
            [
                'label' => esc_html__('Animation Duration (seconds)', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 20,
                'max' => 120,
                'step' => 1,
                'description' => esc_html__('How many seconds for one full marquee loop.', 'easy-elements'),
            ]
        );
        $this->add_control(
            'marquee_direction',
            [
                'label' => esc_html__('Animation Direction', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                 'options' => [
                    'normal' => esc_html__('Normal', 'easy-elements'),
                    'reverse'   => esc_html__('Reverse', 'easy-elements'),
                ],
                'default' => 'normal',
            ]
        );
        $this->end_controls_section();

        // Logo Style Section
        $this->start_controls_section(
            'section_logo_style',
            [
                'label' => esc_html__('Logo Style', 'easy-elements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Background: classic or gradient
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'logo_bg',
                'label' => esc_html__('Logo Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eel-marquee-logo',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#f3f8f6',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_border_radius',
            [
                'label' => esc_html__('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_margin',
            [
                'label' => esc_html__('Margin', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_padding',
            [
                'label' => esc_html__('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'logo_border',
                'label' => esc_html__('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-marquee-logo',
            ]
        );

        // Box Shadow control
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'logo_box_shadow',
                'label' => esc_html__('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-marquee-logo',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'css_filters',
                'selector' => '{{WRAPPER}} .eel-marquee-logo img',
            ]
        );

        $this->add_responsive_control(
            'logo_box_width',
            [
                'label' => esc_html__( 'Min Width Box', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-logo' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_min_height',
            [
                'label' => esc_html__( 'Logo Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-logo img' => 'height: {{SIZE}}{{UNIT}}; max-height: 100%;',
                ],
            ]
        );

        

        $this->end_controls_section(); 

        $this->start_controls_section(
            'section_title_marquee',
            [
                'label' => esc_html__('Title Style', 'easy-elements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .eel-marquee-title',
			]
		);
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_icon_color',
            [
                'label' => esc_html__('Icon Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-title .eel-m-title-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-marquee-title .eel-m-title-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-marquee-title .eel-m-title-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_icon_bg_color',
            [
                'label' => esc_html__('Icon Bg Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-title .eel-m-title-icon' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_icon_size',
            [
                'label' => esc_html__('Icon Size', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-marquee-title .eel-m-title-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-marquee-title .eel-m-title-icon svg path' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icons_margin',
            [
                'label' => esc_html__('Margin', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eel-m-title-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['rows'])) {
            return;
        }
        $duration = !empty($settings['marquee_duration']) ? floatval($settings['marquee_duration']) : 40;
        $marquee_direction = ($settings['marquee_direction'] == 'reverse') ? 'reverse-item' : '';
        ?>
        <div class="eel-marquee-container <?php echo esc_attr($marquee_direction)?>">
            <?php foreach ($settings['rows'] as $i => $row) :
                $direction_class = ($i % 2 === 1) ? ' reverse' : '';
            ?>
                <div class="eel-marquee-track<?php echo esc_attr($direction_class); ?>" style="animation-duration: <?php echo esc_attr($duration); ?>s;">
                    <?php if (!empty($row['logos'])) :
                        $repeat_count = 4; // Repeat logos 4 times for seamless effect.
                        for ($r = 0; $r < $repeat_count; $r++) :
                            foreach ($row['logos'] as $item) :
                                $image_url = $item['image']['url'] ?? Elementor\Utils::get_placeholder_image_src();
                                $link = $item['link']['url'] ?? '';
                                $target = !empty($item['link']['is_external']) ? ' target="_blank"' : '';
                                $nofollow = !empty($item['link']['nofollow']) ? ' rel="nofollow"' : '';
                                $content_type = $item['content_type'] ?? 'image';
                                $title_text = $item['title_text'] ?? '';
                        ?>
                        <div class="eel-marquee-logo">
                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link); ?>"<?php echo $target . $nofollow; ?>>
                            <?php endif; ?>

                            <?php if ($content_type === 'title' && !empty($title_text)) : ?>
                                <h4 class="eel-marquee-title">
                                    <?php if (!empty($item['title_icon']['value'])) : ?>
                                        <span class="eel-m-title-icon">
                                            <?php \Elementor\Icons_Manager::render_icon($item['title_icon'], ['aria-hidden' => 'true']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="eel-title-text"><?php echo esc_html($title_text); ?></span>
                                    </h4>
                            <?php else : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="logo" loading="lazy" />
                            <?php endif; ?>

                            <?php if ($link) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; endfor; endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}