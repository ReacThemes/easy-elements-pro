<?php
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Easyel_Blog_Slider__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-blog-slider-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/blog-slider.css';
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/blog-slider.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    protected function get_all_posts() {
       $posts = get_posts( [ 'numberposts' => -1 ] );
       $options = [];
       foreach ( $posts as $post ) {
           $options[ $post->ID ] = $post->post_title;
       }
       return $options;
   }

    protected function get_post_type_options() {
        $exclude_post_types = ['attachment', 'revision', 'nav_menu_item', 'custom_post_type_slug'];

        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];

        foreach ($post_types as $post_type => $post_type_obj) {
            if (!in_array($post_type, $exclude_post_types)) {
                $taxonomies = get_object_taxonomies($post_type, 'names');
                if (!empty($taxonomies)) {
                    $options[$post_type] = $post_type_obj->label;
                }
            }
        }

        return $options;
    }


    protected function get_all_categories() {
       $cats = get_categories( [ 'hide_empty' => false ] );
       $options = [];
       foreach ( $cats as $cat ) {
           $options[ $cat->term_id ] = $cat->name;
       }
       return $options;
    }


    public function get_name() {
        return 'eel-blog-slider';
    }

    public function get_title() {
        return esc_html__( 'Post Slider', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'blog', 'grid', 'post', 'news' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_grid',
            [
                'label' => esc_html__( 'Posts Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title_trim',
            [
                'label' => esc_html__( 'Title Trim Words', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 100,
                'description' => esc_html__('Limit number of words in the post title', 'easy-elements'),
            ]
        );

        $this->add_control(
            'excerpt_trim',
            [
                'label' => esc_html__( 'Excerpt Trim Words', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 5,
                'max' => 200,
                'description' => esc_html__('Limit number of words in the excerpt', 'easy-elements'),
            ]
        );

        $this->add_control(
           'title_tag',
           [
               'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
               'type' => Controls_Manager::SELECT,
               'options' => [
                   'h1' => 'H1',
                   'h2' => 'H2',
                   'h3' => 'H3',
                   'h4' => 'H4',
                   'h5' => 'H5',
                   'h6' => 'H6',
               ],
               'default' => 'h3',
           ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => esc_html__( 'Show Thumbnail', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
            ]
        );
        
        // Add Show Excerpt control
        $this->add_control(
            'show_excerpt',
            [
                'label' => esc_html__( 'Show Excerpt', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
           Group_Control_Image_Size::get_type(),
           [
               'name' => 'thumbnail',
               'exclude' => ['custom'],
               'default' => 'medium',
           ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_image_height',
            [
                'label' => esc_html__( 'Image Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_alignment',
            [
                'label' => __( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_grid_query',
            [
                'label' => esc_html__( 'Query Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label'   => esc_html__( 'Post Type', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_post_type_options(),
                'default' => 'post',
            ]
        );

        $this->add_control(
            'source_type',
            [
                'label' => esc_html__( 'Source Type', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'category' => esc_html__( 'Category', 'easy-elements' ),
                    'post'     => esc_html__( 'Specific Posts', 'easy-elements' ),
                ],
                'default' => 'category',
            ]
        );


        $this->add_control(
            'categories',
            [
                'label' => esc_html__( 'Select Categories', 'easy-elements' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_categories(),
                'label_block' => true,
                'condition' => [
                    'source_type' => 'category',
                ],
            ]
        );

        $this->add_control(
            'post__in',
            [
                'label' => esc_html__( 'Select Specific Posts', 'easy-elements' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_all_posts(),
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'source_type' => 'post',
                ],
            ]
        );


        $this->add_control(
            'exclude_posts',
            [
                'label' => esc_html__( 'Exclude Posts', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts(),
                'label_block' => true,
                'condition' => [
                    'source_type!' => 'post',
                ],
            ]
        );


        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'date' => esc_html__( 'Date', 'easy-elements' ),
                    'title' => esc_html__( 'Title', 'easy-elements' ),
                    'rand' => esc_html__( 'Random', 'easy-elements' ),
                    'menu_order' => esc_html__( 'Menu Order', 'easy-elements' ),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Order', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'DESC' => esc_html__( 'Descending', 'easy-elements' ),
                    'ASC' => esc_html__( 'Ascending', 'easy-elements' ),
                ],
                'default' => 'DESC',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            '_section_meta',
            [
                'label' => esc_html__( 'Meta Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => esc_html__('Show Meta', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'date'      => esc_html__('Date', 'easy-elements'),
                    'author'    => esc_html__('Author', 'easy-elements'),
                    'category'  => esc_html__('Category', 'easy-elements'),
                    'comments'  => esc_html__('Comments', 'easy-elements'),
                ],
                'default' => ['date', 'author'],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_date_icon',
            [
                'label' => esc_html__( 'Show Date Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'date',
                ]
            ]
        );

        $this->add_control(
            'date_icon',
            [
                'label' => esc_html__( 'Date Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_date_icon' => 'yes',
                    'show_meta' => 'date',
                ],
            ]
        );

        $this->add_control(
            'show_by_label',
            [
                'label'        => __( 'Show "By" Label', 'easy-elements' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements' ),
                'label_off'    => __( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'author_link_enable',
            [
                'label'        => __( 'Author Link', 'easy-elements' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements' ),
                'label_off'    => __( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );


        $this->add_control(
            'show_author_icon',
            [
                'label' => esc_html__( 'Show Author Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'author',
                ]
            ]
        );

        $this->add_control(
            'author_icon',
            [
                'label' => esc_html__( 'Author Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_author_icon' => 'yes',
                    'show_meta' => 'author',
                ],
            ]
        );


        $this->add_control(
            'show_category_icon',
            [
                'label' => esc_html__( 'Show Category Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'category',
                ]
            ]
        );

        $this->add_control(
            'category_icon',
            [
                'label'   => esc_html__( 'Category Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_category_icon' => 'yes',
                    'show_meta' => 'category',
                ],
            ]
        );

        $this->add_control(
            'show_comments_icon',
            [
                'label' => esc_html__( 'Show Comments Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'comments',
                ]
            ]
        );

        $this->add_control(
            'comments_icon',
            [
                'label' => esc_html__( 'Comments Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_comments_icon' => 'yes',
                    'show_meta' => 'comments',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_icon_offset',
            [
                'label' => esc_html__( 'Meta Icon Offset (px)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta svg, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta i' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_position',
            [
                'label'   => esc_html__( 'Meta Position', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'up_title',
                'options' => [
                    'up_title'   => esc_html__( 'Up Title', 'easy-elements' ),
                    'below_title'   => esc_html__( 'Below Title', 'easy-elements' ),
                    'below_content' => esc_html__( 'Below Content', 'easy-elements' ),
                ],
            ]
        );

        $this->add_control(
            'eel_separator',
            [
                'label' => esc_html__( 'Enable Separator', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => '',
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Separator Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--separator--yes li + li::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eel_separator' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_offset',
            [
                'label' => esc_html__( 'Separator Offset (px)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--separator--yes li + li::before' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eel_separator' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
            'meta_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button',
            [
                'label' => esc_html__( 'Button Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__( 'Show Read More', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__( 'Read More', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Read More', 'easy-elements' ),
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon',
            [
                'label'   => esc_html__( 'Read More Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_position',
            [
                'label'   => esc_html__( 'Icon Position', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'before' => esc_html__( 'Before Text', 'easy-elements' ),
                    'after'  => esc_html__( 'After Text', 'easy-elements' ),
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'read_more_button_style_tabs' );

        // Normal State
        $this->start_controls_tab(
            'button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .eel--read-more a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_btn_background',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--read-more a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .eel--read-more a',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border_hover',
                'selector' => '{{WRAPPER}} .eel--read-more a:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_icon_offset',
            [
                'label' => esc_html__( 'Icon Offset (Vertical)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more-icon.after' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_icon_offset_ho',
            [
                'label' => esc_html__( 'Icon Offset (Horizontal)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more-icon.after' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button_icon',
            [
                'label' => esc_html__( 'Only Icon Button Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'only_icon_show',
            [
                'label' => esc_html__( 'Show Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'only_icon',
            [
                'label'   => esc_html__( ' Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab(
            'icon_btn_style_normal',
            [
                'label' => __( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--button-icon svg, {{WRAPPER}} .eel--button-icon i' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_iocn_background',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--button-icon',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_btn_border',
                'label' => __( 'Button Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel--button-icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); 

        $this->start_controls_tab(
            'icon_btn_style_hover',
            [
                'label' => __( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => __( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--button-icon:hover svg, {{WRAPPER}} .eel--button-icon:hover i' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_iocn_background_hover',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--button-icon:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
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
            'pagination_normal_height',
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
            'section_item_style',
            [
                'label' => __( 'Items Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'item_background',
                'label'    => __( 'Item Background', 'easy-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label'      => __( 'Border Radius', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'item_border',
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner',
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_conten_style',
            [
                'label' => __( 'Content Part Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'content_background',
                'label'    => __( 'Item Background', 'easy-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ee--blog-content-wrap',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--blog-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ee--blog-title',
            ]
        );

        // Tabs for Normal and Hover state
        $this->start_controls_tabs( 'title_color_tabs' );

        // Normal
        $this->start_controls_tab(
            'title_color_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-title' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--blog-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'title_color_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-title:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_excerpt_style',
            [
                'label' => __( 'Excerpt Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .eel--blog-excerpt',
            ]
        );

        $this->add_responsive_control(
            'excerpt_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel--blog-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_meta_style',
            [
                'label' => __( 'Meta Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'     => __( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li',
            ]
        );

        $this->end_controls_section();
    }

    protected function render_blog_meta( $settings ) {
        if ( empty( $settings['show_meta'] ) || ! is_array( $settings['show_meta'] ) ) {
            return;
        }

        $show_meta = $settings['show_meta'];
        $eel_separator = (! empty($settings['eel_separator']) && $settings['eel_separator'] == 'yes') ? 'eel--separator--yes' : '';

        ?>
        <ul class="eel--blog-meta <?php echo esc_attr($eel_separator); ?>">
            <?php if ( in_array( 'date', $show_meta ) ) : ?>
                <li class="eel--blog-date">
                    <?php
                    if ( ! empty($settings['show_date_icon']) && $settings['show_date_icon'] === 'yes' ) {
                        if ( empty( $settings['date_icon']['value'] ) ) {
                            echo '<i class="unicon-calendar"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }
                    echo esc_html( get_the_date() );
                    ?> 
                </li>
            <?php endif; ?>

            <?php if ( in_array( 'author', $show_meta ) ) : ?>
                <li class="eel--blog-author">
                    <?php 
                    if ( ! empty($settings['show_author_icon']) && $settings['show_author_icon'] === 'yes' ) {
                        if ( empty( $settings['author_icon']['value'] ) ) {
                            echo '<i class="unicon-user"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }

                    if ( ! empty($settings['show_by_label']) && $settings['show_by_label'] === 'yes' ) {
                        echo '<em class="eel--meta-by">' . esc_html__( 'By', 'easy-elements' ) . '</em> ';
                    }

                    $author_id   = get_the_author_meta( 'ID' );
                    $author_name = get_the_author();
                    $author_url  = get_author_posts_url( $author_id );

                    if ( ! empty($settings['author_link_enable']) && $settings['author_link_enable'] === 'yes' ) {
                        echo '<a class="eel--meta-author" href="' . esc_url( $author_url ) . '">' . esc_html( $author_name ) . '</a>';
                    } else {
                        echo '<span class="eel--meta-author">' . esc_html( $author_name ) . '</span>';
                    }
                    ?>
                </li>
            <?php endif; ?>

            <?php if ( in_array( 'category', $show_meta ) ) : ?>
                <li class="eel--blog-cat">
                    <?php
                    if ( ! empty($settings['show_category_icon']) && $settings['show_category_icon'] === 'yes' ) {
                        if ( empty( $settings['category_icon']['value'] ) ) {
                            echo '<i class="unicon-folder"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }
                    the_category( ', ' );
                    ?>
                </li>
            <?php endif; ?>

            <?php if ( in_array( 'comments', $show_meta ) ) : ?>
                <li class="eel--blog-comments">
                    <?php
                    if ( ! empty($settings['show_comments_icon']) && $settings['show_comments_icon'] === 'yes' ) {
                        if ( empty( $settings['comments_icon']['value'] ) ) {
                            echo '<i class="unicon-forum"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['comments_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }
                    $comments_number = get_comments_number();
                    echo '<a href="' . esc_url( get_comments_link() ) . '">' . esc_html( $comments_number ) . ' ' . _n( 'Comment', 'Comments', $comments_number, 'easy-elements' ) . '</a>';
                    ?>
                </li>
            <?php endif; ?>
        </ul>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();        
        $post_type = $settings['post_type']?? '';
        $title_tag = $settings['title_tag'];
        $posts_per_page = $settings['posts_per_page'];
        $orderby = $settings['orderby'];
        $order = $settings['order'];
        $thumbnail_size = $settings['thumbnail_size'];
        $title_trim = $settings['title_trim'];
        $excerpt_trim = $settings['excerpt_trim'];
        $post_type = !empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';        
        $paged = max( 1, get_query_var('paged') ? get_query_var('paged') : ( get_query_var('page') ? get_query_var('page') : 1 ) );

        $args = [
            'post_type' => $post_type,
            'posts_per_page' => $settings['posts_per_page'],
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        // Include based on source type
        if ( $settings['source_type'] === 'category' && ! empty( $settings['categories'] ) ) {
            $args['category__in'] = $settings['categories'];
        } elseif ( $settings['source_type'] === 'post' && ! empty( $settings['post__in'] ) ) {
            $args['post__in'] = $settings['post__in'];
            $args['orderby'] = 'post__in'; // Preserve selected order
        }

        // Exclude posts
        if ( ! empty( $settings['exclude_posts'] ) ) {
            $args['post__not_in'] = $settings['exclude_posts'];
        }
        include plugin_dir_path( __FILE__ ) . 'slider.php';

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) : ?>
            <div id="<?php echo esc_attr($unique_id); ?>" class="eel-post-grid-wrap swiper eel-all-slider" <?php echo $slider_data_attributes; ?>>
            <div class="swiper-wrapper">
            <?php
            while ( $query->have_posts() ) : $query->the_post();

                $trimmed_title = wp_trim_words( get_the_title(), $title_trim, '...' );
                $trimmed_excerpt = wp_trim_words( get_the_excerpt(), $excerpt_trim, '...' );
            ?>
            <div class="swiper-slide">
                <div class="grid-item">
                    <div class="grid-item-inner">
                        <?php if ( 'yes' === $settings['show_thumbnail'] ) { ?>
                            <div class="eel--blog-img">
                                <a href="<?php the_permalink(); ?>">
                                <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( $thumbnail_size );
                                    }
                                ?>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="ee--blog-content-wrap">
                            <div class="ee--blog-content">
                                <?php if ( 'up_title' === $settings['meta_position'] ) : ?>
                                    <?php $this->render_blog_meta( $settings ); ?>
                                <?php endif; ?>

                                <<?php echo $title_tag; ?> class="ee--blog-title">
                                    <a href="<?php the_permalink(); ?>"><?php echo esc_html( $trimmed_title ); ?></a>
                                </<?php echo $title_tag; ?>>

                                <?php if ( 'below_title' === $settings['meta_position'] ) : ?>
                                    <?php $this->render_blog_meta( $settings ); ?>
                                <?php endif; ?>

                                <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                                <div class="eel--blog-excerpt">
                                    <?php echo esc_html( $trimmed_excerpt ); ?>
                                </div>
                                <?php endif; ?>

                                <?php if ( 'below_content' === $settings['meta_position'] ) : ?>
                                    <?php $this->render_blog_meta( $settings ); ?>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ( ! empty( $settings['read_more_text'] ) ) : ?>
                                <div class="eel--read-more">
                                    <a href="<?php the_permalink(); ?>" class="eel--read-more-link">
                                        <?php
                                        if ( isset( $settings['read_more_icon'] ) && ! empty( $settings['read_more_icon']['value'] ) && $settings['read_more_icon_position'] === 'before' ) {
                                            \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [
                                                'aria-hidden' => 'true',
                                                'class' => 'eel--read-more-icon before'
                                            ] );
                                        } elseif ( $settings['read_more_icon_position'] === 'before' ) {
                                            echo '<i class="unicon-chevron-right eel--read-more-icon before"></i>';
                                        }
                                        ?>

                                        <?php echo esc_html( $settings['read_more_text'] ); ?>

                                        <?php
                                        if ( isset( $settings['read_more_icon'] ) && ! empty( $settings['read_more_icon']['value'] ) && $settings['read_more_icon_position'] === 'after' ) {
                                            \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [
                                                'aria-hidden' => 'true',
                                                'class' => 'eel--read-more-icon after'
                                            ] );
                                        } elseif ( $settings['read_more_icon_position'] === 'after' ) {
                                            echo '<i class="unicon-chevron-right eel--read-more-icon after"></i>';
                                        }
                                        ?>
                                    </a>
                                </div>
                            <?php endif; 
                                if ( 'yes' === $settings['only_icon_show'] ) { ?>
                                    <div class="eel--button-icon">
                                        <?php
                                        if ( empty( $settings['only_icon']['value'] ) ) {
                                            echo '<i class="unicon-chevron-right"></i>';
                                        } else {
                                            \Elementor\Icons_Manager::render_icon( $settings['only_icon'], [ 'aria-hidden' => 'true' ] );
                                        } ?>
                                    </div>
                                <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                </div>
            <?php
            endwhile;
            ?>

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
         
            echo '</div>';
            
            echo '</div>';
            if ( $settings['pagination'] !== 'none' ) : ?>
                <div class="swiper-pagination eel-pagination-number-<?php echo $settings['number_mode_enable']; ?>"></div>
            <?php endif; 
            wp_reset_postdata();

        else :
            echo '<p>' . esc_html__( 'No posts found.', 'easy-elements' ) . '</p>';
        endif;
    }
}