<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater; 
use Elementor\Group_Control_Border; 
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
     exit;
}

class Easyel_Featured_Project_Widget extends Widget_Base {

     public function get_style_depends() {
	    $handle = 'eel-project-featured-image';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/featured-project.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/featured-project.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }
	    return [ $handle ];
	}

    public function get_name() {
        return 'feature-project';
    }

    public function get_title() {
        return esc_html__( 'Easy Feature Project', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_feature_project',
            [
                'label' => esc_html__( 'Feature Project', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Feature Project Title', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title Tag', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                ],
                'default' => 'h6',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Feature Project Description', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'easy-elements' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

      

        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-star',
                    'library' => 'fa',
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Feature Title', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => esc_html__( 'Features', 'easy-elements' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'icon' => [ 'value' => 'fa fa-star', 'library' => 'fa' ],
                        'title' => esc_html__( 'Feature Title', 'easy-elements' ),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
            'section_feature_project_style',
            [
                'label' => esc_html__( 'Style', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'item_heading',
            [
                'label' => esc_html__( 'Item Style', 'easy-elements' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'background_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .eel-feature-project',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_heading',
            [
                'label' => esc_html__( 'Meta Style', 'easy-elements' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-feature-project-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]  
        );

        $this->add_responsive_control(
            'title_desc',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-feature-project-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]  
        );

        

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-feature-project .eel-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Title Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-feature-project .description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Features
        $this->add_control(
            'feature_heading',
            [
                'label' => esc_html__( 'Features Style', 'easy-elements' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'feature_border',
                'selector' => '{{WRAPPER}} .eel-feature-project .eel-project-features',
            ]
        );

        $this->add_control(
            'feature_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-project-features' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

         $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'feature_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-feature-project .eel-features .eel-title',
            ]
        );

        $this->add_responsive_control(
            'features_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-feature' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

         $this->add_control(
            'features_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-project-features i' => 'color: {{VALUE}};',
                ],
            ]
        );

       $this->add_control(
            'features_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-project-features i' => 'font-size: {{VALUE}}px;',
                    '{{WRAPPER}} .eel-feature-project .eel-features .eel-feature svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                ],
            ]
        );


        $this->add_control(
          'feature_right_border_color',
          [
            'label' => esc_html__( 'Right Border Color', 'easy-elements' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
              '{{WRAPPER}} .eel-feature-project .eel-project-features .eel-feature' => 'border-color: {{VALUE}};',
            ],
          ]  
        );

        $this->add_control(
            'features_title_color',
            [
                'label' => esc_html__( 'Title Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-project-features .eel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'features_title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-feature-project .eel-project-features .eel-feature .eel-feature-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $title_tag = ! empty( $settings['title_tag'] ) ? $settings['title_tag'] : 'h6';

        $img_url = ! empty( $settings['image']['url'] ) ? esc_url( $settings['image']['url'] ) : '';
        $img_alt = ! empty( $settings['image']['alt'] ) ? esc_attr( $settings['image']['alt'] ) : '';

        if ( empty( $img_url ) ) {
            return;
        }

        // Link fallback
        $link = ! empty( $settings['link']['url'] ) ? esc_url( $settings['link']['url'] ) : '#';
        $title = ! empty( $settings['title'] ) ? esc_html( $settings['title'] ) : '';
        $description = ! empty( $settings['description'] ) ? esc_html( $settings['description'] ) : '';

        ?>
        <div class="eel-feature-project-content">
            <div class="eel-feature-project">
                <a href="<?php echo $link; ?>">
                    <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" class="eel-feature-project-image">
        
                    <div class="eel-feature-project-meta">
                        <<?php echo $title_tag; ?> class="eel-title"><?php echo $title; ?></<?php echo $title_tag; ?>>
                        <?php if ( $description ) : ?>
                            <p class="eel-description"><?php echo $description; ?></p>
                        <?php endif; ?>
                    </div>
        
                    <?php if ( ! empty( $settings['features'] ) && is_array( $settings['features'] ) ) : ?>
                        <ul class="eel-project-features">
                            <?php foreach ( $settings['features'] as $feature ) : ?>
                                <li class="eel-feature">
                                    <?php if ( ! empty( $feature['icon']['value'] ) ) : ?>
                                        <?php 
                                            \Elementor\Icons_Manager::render_icon(
                                                $feature['icon'], 
                                                [ 'class' => 'eel-feature-icon' ], 
                                                'i' // fallback tag for font icons
                                            );
                                        ?>
                                    <?php endif; ?>
                                    <span class="eel-feature-title"><?php echo esc_html( $feature['title'] ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </a>
            </div>

        </div>
        <?php
    }

}