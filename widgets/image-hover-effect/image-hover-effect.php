<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_image_hover_effect__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-image-hover-effect-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/image-hover-effect.css';

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/image-hover-effect.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-image-hover-effect';
    }

    public function get_title() {
        return esc_html__( 'Image Hover Effect', 'easy-elements-pro' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'image', 'clients', 'brand', 'partner' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_image',
            [
                'label' => esc_html__( 'Cusror Settings', 'easy-elements-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Repeater
        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'easy-elements-pro'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'category',
            [
                'label' => esc_html__('Category', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label' => esc_html__('Item Link', 'easy-elements-pro'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'easy-elements-pro'),
                'options' => [ 'url', 'is_external', 'nofollow' ],
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'label_block' => true,
            ]
        );


        $this->add_control(
            'easy_cursor_hover_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => array_fill(0, 4, [
                    'image' => ['url' => Utils::get_placeholder_image_src()],
                    'title' => esc_html__( 'Item Title', 'easy-elements-pro' ),
                ]),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'span' => 'Span',
                    'p' => 'Paragraph',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__( 'Image Width', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 800 ],
                    '%'  => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .easyel-cursor-hover' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Image Height', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 800 ],
                    '%'  => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .easyel-cursor-hover' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'item_style_section',
            [
                'label' => esc_html__( 'Per Item', 'easy-elements-pro' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .easyel-cursor-hover-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__( 'Item Spacing', 'easy-elements-pro' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                    '%'  => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .easyel-cursor-hover-list-wrap li + li' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'easy-elements-pro' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .easyel-cursor-hover-list-wrap li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'item_border',
                'label'    => esc_html__( 'Border', 'easy-elements-pro' ),
                'selector' => '{{WRAPPER}} .easyel-cursor-hover-list-wrap li + li',
                'separator'=> 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'easy-elements-pro' ),
                'selector' => '{{WRAPPER}} .easyel-cursor-hover-list-wrap li',
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .easyel-cursor-hover-list-wrap li',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__( 'Title', 'easy-elements-pro' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .easyel-cursor-hover-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements-pro' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .easyel-cursor-hover-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .easyel-cursor-hover-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'des_style_section',
            [
                'label' => esc_html__( 'Description', 'easy-elements-pro' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'des_typography',
                'selector' => '{{WRAPPER}} .easyel-cursor-hover-list-des',
            ]
        );

        $this->add_control(
            'des_color',
            [
                'label'     => esc_html__( 'Description Color', 'easy-elements-pro' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .easyel-cursor-hover-list-des' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'des_margin',
            [
                'label'      => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .easyel-cursor-hover-list-des' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'cate_style_section',
            [
                'label' => esc_html__( 'Category', 'easy-elements-pro' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'cate_typography',
                'selector' => '{{WRAPPER}} .easyel-cursor-hover-list-categ',
            ]
        );

        $this->add_control(
            'cate_color',
            [
                'label'     => esc_html__( 'Category Color', 'easy-elements-pro' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .easyel-cursor-hover-list-categ' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    if ( empty( $settings['easy_cursor_hover_list'] ) ) {
        return;
    }       
    ?>
    <div class="easyel-cursor-hover"></div>
        <div class="easyel-wrapper">
            <ul class="easyel-cursor-hover-list-wrap">
                <?php 
                $i = 1;                
                foreach ( $settings['easy_cursor_hover_list'] as $item ) : 
                    $image_url = !empty( $item['image']['url'] ) ? $item['image']['url'] : Utils::get_placeholder_image_src();

                    // Link attributes
                    $link_open = $link_close = '';
                    if ( !empty( $item['item_link']['url'] ) ) {
                        $target   = $item['item_link']['is_external'] ? ' target="_blank"' : '';
                        $nofollow = $item['item_link']['nofollow'] ? ' rel="nofollow"' : '';
                        $link_open = '<a href="' . esc_url( $item['item_link']['url'] ) . '"' . $target . $nofollow . '>';
                        $link_close = '</a>';
                    }
                    $allowed_link = [
                        'a' => [
                            'href'   => true,
                            'target' => true,
                            'rel'    => true,
                        ],
                    ];
                    ?>
                    <li>
                    <?php echo wp_kses( $link_open, $allowed_link ); ?>
                    <div class="easyel-cursor-hover-list easyel-p-<?php echo esc_attr($i); ?>" 
                        data-image="<?php echo esc_url( $image_url ); ?>">                        
                        <div class="easyel-cursor-hover-title">
                            <?php 
                                $tag = !empty($item['title_tag']) ? $item['title_tag'] : 'h3';
                                echo '<' . esc_html($tag) . ' class="easyel-cursor-hover-title">' . esc_html($item['title']) . '</' . esc_html($tag) . '>';
                            ?>
                            <p class="easyel-cursor-hover-list-des">
                                <?php echo esc_html( $item['description'] ); ?>
                            </p>
                        </div>
                        <?php if ( !empty($item['category']) ) : ?>
                            <div class="easyel-cursor-hover-list-categ">
                                <?php echo esc_html( $item['category'] ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php echo wp_kses( $link_close, $allowed_link ); ?>
                    </li>
                <?php 
                $i++;
                endforeach;
                ?>
            </ul>
        </div>
        <?php
    }

} 