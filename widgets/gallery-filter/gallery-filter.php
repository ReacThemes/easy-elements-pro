<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Easyel__Gallery_Pro_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-filterable-gallery';
    }

    public function get_title() {
        return esc_html__( 'filterable Gallery', 'easy-elements-pro' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_form' ];
    }

    public function get_keywords() {
        return [ 'filterable', 'image', 'photo', 'portfolio' ];
    }

    public function get_style_depends() {
        $handle = 'eel-gallery-filter-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/gallery-filter.css';	    
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style(
                $handle,
                plugins_url( 'css/gallery-filter.css', __FILE__ ),
                [],
                defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0'
            );
        }
        return [ $handle ];
    }

    public function get_script_depends() {
        $deps = [];

        // ✅ Register Isotope if not already registered
        if ( ! wp_script_is( 'isotope-pkgd', 'registered' ) ) {
            wp_register_script(
                'isotope-pkgd',
                plugins_url( 'js/isotope.pkgd.js', __FILE__ ),
                [ 'jquery' ],
                '3.0.6',
                true // footer
            );
        }
        $deps[] = 'isotope-pkgd';

        // ✅ Register imagesLoaded if not already
        if ( ! wp_script_is( 'imagesloaded', 'registered' ) ) {
            wp_register_script(
                'imagesloaded',
                plugins_url( 'js/imagesloaded.pkgd.min.js', __FILE__ ),
                [ 'jquery' ],
                '4.1.4',
                true // footer
            );
        }
        $deps[] = 'imagesloaded';

        // ✅ Register your gallery script
        $handle  = 'eel-simple-gallery-filter-script';
        $js_path = plugin_dir_path( __FILE__ ) . 'js/simple-gallery-filter.js';
        $js_url  = plugins_url( 'js/simple-gallery-filter.js', __FILE__ );

        if ( file_exists( $js_path ) && ! wp_script_is( $handle, 'registered' ) ) {
            wp_register_script(
                $handle,
                $js_url,
                [ 'jquery', 'isotope-pkgd', 'imagesloaded' ],
                ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? filemtime( $js_path ) : '1.0.0',
                true // ✅ footer load
            );
        }

        $deps[] = $handle;

        return array_unique( $deps );
    }


    protected function register_controls() {

        // Gallery Images
        $this->start_controls_section(
            'section_gallery',
            [
                'label' => esc_html__( 'Gallery Images', 'easy-elements-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'gallery_images',
            [
                'label' => esc_html__( 'Add Images', 'easy-elements-pro' ),
                'type' => Controls_Manager::GALLERY,
                'default' => [],
            ]
        );

        $repeater->add_control(
            'categories',
            [
                'label' => esc_html__('Categories', 'easy-elements-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => esc_html__('Separate categories with commas', 'easy-elements-pro'),
            ]
        );

        $this->add_control(
            'gallery_items',
            [
                'label' => esc_html__('Gallery Items', 'easy-elements-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                // Use categories text as the item title in the editor
                'title_field' => '{{{ categories }}}',
            ]
        );


        $this->add_control(
            'category_filter_type',
            [
                'label' => esc_html__( 'Category Style', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Buttons', 'easy-elements-pro' ),
                    'with_search'         => esc_html__( 'Dropdown with Search', 'easy-elements-pro' ),
                ],
            ]
        );


        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => esc_html__( '1 Column', 'easy-elements-pro' ),
                    '2' => esc_html__( '2 Columns', 'easy-elements-pro' ),
                    '3' => esc_html__( '3 Columns', 'easy-elements-pro' ),
                    '4' => esc_html__( '4 Columns', 'easy-elements-pro' ),
                    '5' => esc_html__( '5 Columns', 'easy-elements-pro' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filter' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'show_caption',
            [
                'label' => esc_html__( 'Show Caption', 'easy-elements-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements-pro' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements-pro' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_media_description',
            [
                'label' => esc_html__( 'Show Media Description', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements-pro' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements-pro' ),
                'default' => 'no',
            ]
        );


        $this->add_control(
            'caption_source',
            [
                'label' => esc_html__( 'Caption Source', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'media',
                'options' => [
                    'media' => esc_html__( 'Media Library Caption', 'easy-elements-pro' ),
                    'title' => esc_html__( 'Image Title', 'easy-elements-pro' ),
                    'none' => esc_html__( 'None', 'easy-elements-pro' ),
                ],
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_popup',
            [
                'label' => esc_html__( 'Enable Lightbox', 'easy-elements-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements-pro' ),
                'label_off' => esc_html__( 'No', 'easy-elements-pro' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__( 'Order By', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'menu_order',
                'options' => [
                    'menu_order' => esc_html__( 'Default', 'easy-elements-pro' ),
                    'title'      => esc_html__( 'Title', 'easy-elements-pro' ),
                    'id'         => esc_html__( 'ID', 'easy-elements-pro' ),
                    'date'       => esc_html__( 'Date', 'easy-elements-pro' ),
                    'rand'       => esc_html__( 'Random', 'easy-elements-pro' ),
                ],
            ]
        );

        $this->add_control(
            'hover_style',
            [
                'label' => esc_html__( 'On Hover', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Default', 'easy-elements-pro' ),
                    'icon'      => esc_html__( 'Icon', 'easy-elements-pro' ),
                    'text'         => esc_html__( 'Text', 'easy-elements-pro' ),
                ],
            ]
        );

        $this->add_control(
            'hover_text',
            [
                'label' => esc_html__( 'Hover Text', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'View', 'easy-elements-pro' ),
                'placeholder' => esc_html__( 'Enter hover text', 'easy-elements-pro' ),
                'condition' => [
                    'hover_style' => 'text',
                ],
            ]
        );

        $this->add_control(
            'hover_icon',
            [
                'label' => esc_html__( 'Hover Icon', 'easy-elements-pro' ),
                'type'  => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'hover_style' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'filter_all_text',
            [
                'label' => esc_html__( 'All Filter Text', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'All', 'easy-elements-pro' ),
                'placeholder' => esc_html__( 'Enter text for All filter button', 'easy-elements-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'no_images_text',
            [
                'label' => esc_html__( 'No Images Message', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Please select images to display the gallery.', 'easy-elements-pro' ),
                'placeholder' => esc_html__( 'Enter message when no images are selected', 'easy-elements-pro' ),
            ]
        );

        $this->add_control(
            'lightbox_prev_text',
            [
                'label' => esc_html__( 'Previous Button Text', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '&#10094;', 'easy-elements-pro' ),
                'placeholder' => esc_html__( 'Enter previous button text', 'easy-elements-pro' ),
                'condition' => [
                    'enable_popup' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lightbox_next_text',
            [
                'label' => esc_html__( 'Next Button Text', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '&#10095;', 'easy-elements-pro' ),
                'placeholder' => esc_html__( 'Enter next button text', 'easy-elements-pro' ),
                'condition' => [
                    'enable_popup' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lightbox_close_text',
            [
                'label' => esc_html__( 'Close Button Text', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '&times;', 'easy-elements-pro' ),
                'placeholder' => esc_html__( 'Enter close button text', 'easy-elements-pro' ),
                'condition' => [
                    'enable_popup' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_caption_position',
			[
				'label' => esc_html__('Content Position', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'content_position',
            [
                'label' => esc_html__( 'Content Position', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__( 'Top', 'easy-elements-pro' ),
                    'bottom'      => esc_html__( 'Bottom', 'easy-elements-pro' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__('Images', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
            'image_gap',
            [
                'label' => esc_html__( 'Spacing Between Images', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 10 ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filter.eel-uses-isotope .eel-gallery-filter-item a' => 'margin: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1000, 'step' => 1 ],
                    '%'  => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filter-item img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover; width: 100%;',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'easy-elements-pro' ),
                'selector' => '{{WRAPPER}} .eel-gallery-filter-item img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filter-item img, {{WRAPPER}} .eel-gallery-filter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filter-item img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_caption_style',
            [
                'label' => esc_html__('Content Part', 'easy-elements-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,                
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__('Padding', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-gallery-caption-filter.bottom, {{WRAPPER}} .eel-gallery-caption-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'caption_bg_color',
            [
                'label' => esc_html__('Background Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption-filter' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'caption_heading',
			[
				'label' => esc_html__( 'Caption Part', 'easy-elements-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        // Caption Color
        $this->add_control(
            'caption_color',
            [
                'label' => esc_html__('Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption-filter' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );

        // Caption Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'label' => esc_html__('Typography', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-caption-filter',
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );


        $this->add_control(
			'description_heading',
			[
				'label' => esc_html__( 'Description Part', 'easy-elements-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        // Caption Color
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption-filter.bottom .eel-gallery-description' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_media_description' => 'yes',
                ],
            ]
        );

        // Caption Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Typography', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-caption-filter.bottom .eel-gallery-description',
                'condition' => [
                    'show_media_description' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption-filter.bottom .eel-gallery-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_media_description' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_hover_overlay_style',
            [
                'label' => esc_html__('Hover Overlay', 'easy-elements-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hover_style!' => 'default',
                ],
            ]
        );
        $this->add_control(
            'hover_overlay_color',
            [
                'label' => esc_html__( 'Hover Overlay Color', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.6)',
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-content' => 'background-color: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_hover_icon_style',
            [
                'label' => esc_html__('Hover Icon', 'easy-elements-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hover_style' => 'icon',
                ],
            ]
        );
        $this->add_responsive_control(
            'hover_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default' => [ 'size' => 16 ],
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-hover-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-icon i, {{WRAPPER}} .eel-hover-icon svg, {{WRAPPER}} .eel-hover-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_hover_text_style',
            [
                'label' => esc_html__('Hover Text', 'easy-elements-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hover_style' => 'text',
                ],
            ]
        );

        // Color
        $this->add_control(
            'hover_text_color',
            [
                'label' => esc_html__('Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-text span' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hover_text_color_typography',
                'label' => esc_html__('Typography', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-hover-text span',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category_style',
            [
                'label' => esc_html__('Category', 'easy-elements-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Start tabs for Normal / Active
        $this->start_controls_tabs('tabs_category_style');

        // ------------------ Normal Tab ------------------
        $this->start_controls_tab(
            'tab_category_normal',
            [
                'label' => esc_html__('Normal', 'easy-elements-pro'),
            ]
        );

        $this->add_control(
            'cate_text_color',
            [
                'label'     => esc_html__('Text Color', 'easy-elements-pro'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filters .eel-filter' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'cate_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-gallery-filters .eel-filter',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'cate_typography',
                'label'    => esc_html__('Typography', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-filters .eel-filter',
            ]
        );

        $this->add_responsive_control(
            'cate_button',
            [
                'label'      => esc_html__('Padding', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-gallery-filters .eel-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cate_border_radius',
            [
                'label'      => esc_html__('Boper Radius', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-gallery-filters .eel-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'cate_border_normal',
                'label' => esc_html__('Border', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-filters .eel-filter',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'cate_box_shadow',
                'label'    => esc_html__('Box Shadow', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-filters .eel-filter',
            ]
        );

        $this->add_responsive_control(
            'cate_item_spacing',
            [
                'label'      => esc_html__('Spacing Between', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-gallery-filters' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cate_bottom_spacing',
            [
                'label'      => esc_html__('Spacing Bottom', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-gallery-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); 

        $this->add_responsive_control(
            'cate_alignment',
            [
                'label' => esc_html__('Alignment', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'easy-elements-pro'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'easy-elements-pro'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'easy-elements-pro'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'easy-elements-pro'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'easy-elements-pro'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filters' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Normal Tab

        // ------------------ Active Tab ------------------
        $this->start_controls_tab(
            'tab_category_active',
            [
                'label' => esc_html__('Active', 'easy-elements-pro'),
            ]
        );

        $this->add_control(
            'cate_text_color_active',
            [
                'label'     => esc_html__('Text Color', 'easy-elements-pro'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-filters .eel-filter.active, {{WRAPPER}} .eel-gallery-filters .eel-filter:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'cate_bg_active',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-gallery-filters .eel-filter.active, {{WRAPPER}} .eel-gallery-filters .eel-filter:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'cate_border_active',
                'label' => esc_html__('Border', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-filters .eel-filter.active, {{WRAPPER}} .eel-gallery-filters .eel-filter:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs(); 
        $this->end_controls_section();


        $this->start_controls_section(
            'section_search_bar_style',
            [
                'label' => esc_html__('Dropdown with Search', 'easy-elements-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'category_filter_type' => 'with_search',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'search_bar_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-gallery-search-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'search_bar_border',
                'label' => esc_html__( 'Border', 'easy-elements-pro' ),
                'selector' => '{{WRAPPER}} .eel-gallery-search-wrapper',
            ]
        );

        $this->add_responsive_control(
            'search_bar_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_bar_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_bar_spacing',
            [
                'label' => esc_html__( 'Spacing Bottom', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_bar_max_width',
            [
                'label' => esc_html__( 'Max Width', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'input_style',
			[
				'label' => esc_html__( 'Input Style', 'easy-elements-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__('Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => esc_html__('Placeholder Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search::placeholder, {{WRAPPER}} .eel-gallery-search-wrapper .unicon-search' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'input_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'label' => esc_html__( 'Border', 'easy-elements-pro' ),
                'selector' => '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_box_shadow',
                'label' => esc_html__('Box Shadow', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'select_style',
			[
				'label' => esc_html__( 'Select Style', 'easy-elements-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'select_color',
            [
                'label' => esc_html__('Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-filters-dropdown' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'select_bg',
            [
                'label' => esc_html__('Background Color', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-filters-dropdown' => 'background-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'select_box_shadow',
                'label' => esc_html__('Box Shadow', 'easy-elements-pro'),
                'selector' => '{{WRAPPER}} .eel-gallery-search-wrapper .eel-gallery-filters-dropdown',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    $gallery_items = $settings['gallery_items'];
    
    if ( empty( $gallery_items ) ) {
        echo '<p>' . esc_html( $settings['no_images_text'] ) . '</p>';
        return;
    }

    // Flatten all images from gallery items
    $images = [];
    foreach ( $gallery_items as $item ) {
        if ( ! empty( $item['gallery_images'] ) ) {
            foreach ( $item['gallery_images'] as $image ) {
                $image['categories'] = $item['categories'] ?? '';
                $images[] = $image;
            }
        }
    }
    
    if ( empty( $images ) ) {
        echo '<p>' . esc_html( $settings['no_images_text'] ) . '</p>';
        return;
    }

    // Order images
    $order_by = $settings['order_by'];
    if ( $order_by === 'rand' ) {
        shuffle( $images );
    } elseif ( $order_by !== 'menu_order' ) {
        usort( $images, function( $a, $b ) use ( $order_by ) {
            $a_post = get_post( $a['id'] );
            $b_post = get_post( $b['id'] );
            if ( ! $a_post || ! $b_post ) return 0;
            return strcmp( strtolower( $a_post->$order_by ), strtolower( $b_post->$order_by ) );
        });
    }

    $popup_enabled = isset( $settings['enable_popup'] ) && $settings['enable_popup'] === 'yes';
    $popup_class   = $popup_enabled ? 'eel-popup-enabled' : '';

    // Collect all categories
    $all_categories = [];
    foreach ( $images as $image ) {
        if ( ! empty($image['categories']) ) {
            $cats = array_map('trim', explode(',', $image['categories']));
            $all_categories = array_merge($all_categories, $cats);
        }
    }
    $all_categories = array_unique($all_categories);

    $filter_type = $settings['category_filter_type'];
    if ( $filter_type === 'with_search' ) {
        // === Search Input ===
        echo '<div class="eel-gallery-search-wrapper">';
        echo '<select class="eel-gallery-filters-dropdown">';
        echo '<option value="*">' . esc_html( $settings['filter_all_text'] ) . '</option>';
        foreach ($all_categories as $cat) {
            $sanitized_cat = sanitize_title( $cat );
            echo '<option value="' . esc_attr($sanitized_cat) . '">' . esc_html($cat) . '</option>';
        }
        echo '</select>';
        echo '<div class="eel-gallery-search-input-wrapper"><input type="text" class="eel-gallery-search" placeholder="' . esc_attr__('Search Here...', 'easy-elements-pro') . '"> <i class="unicon-search"></i> </div>';
        echo '</div>';
        
    }

    // === Category Filters ===
    if ( ! empty($all_categories) ) {
        if ( $filter_type === 'default' ) {
            echo '<div class="eel-gallery-filters">';
            echo '<button class="eel-filter active" data-filter="*">' . esc_html( $settings['filter_all_text'] ) . '</button>';
            foreach ($all_categories as $cat) {
                $sanitized_cat = sanitize_title( $cat );
                echo '<button class="eel-filter" data-filter="' . esc_attr($sanitized_cat) . '">' . esc_html($cat) . '</button>';
            }
            echo '</div>';
        }
    }

    // === Gallery Items ===
    echo '<div class="eel-gallery-filter eel-uses-isotope ' . esc_attr( $popup_class ) . '">';
    foreach ( $images as $index => $image ) {
        $image_url  = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'thumbnail', $settings ) ?: $image['url'];
        $full_image = wp_get_attachment_image_url( $image['id'], 'full' );
        $caption    = '';

        if ( isset($settings['show_caption']) && $settings['show_caption'] === 'yes' ) {
            if ( $settings['caption_source'] === 'media' ) {
                $caption = wp_get_attachment_caption( $image['id'] );
            } elseif ( $settings['caption_source'] === 'title' ) {
                $caption = get_the_title( $image['id'] );
            }
        }       


        $img_categories = !empty($image['categories']) ? implode(' ', array_map('sanitize_title', array_map('trim', explode(',', $image['categories'])))) : '';
        echo '<div class="eel-gallery-filter-item" data-category="' . esc_attr($img_categories) . '" data-title="' . esc_attr($caption) . '">';

        if ( $popup_enabled ) {
            echo '<a href="' . esc_url( $full_image ) . '" class="eel-popup-link" data-index="' . esc_attr( $index ) . '" data-elementor-open-lightbox="no">';
        } else {
            echo '<a href="' . esc_url( $image['url'] ) . '" target="_blank" rel="noopener" data-elementor-open-lightbox="no">';
        }

        // === Image ===
        echo '<div class="eel-gallery-image-wrap">';
        echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_post_meta( $image['id'], '_wp_attachment_image_alt', true ) ) . '" data-elementor-open-lightbox="no">';

        // === Hover Content ===
        if ( $settings['hover_style'] === 'text' && ! empty( $settings['hover_text'] ) ) {
            echo '<div class="eel-hover-content eel-hover-text">';
            echo '<span>' . esc_html( $settings['hover_text'] ) . '</span>';
            echo '</div>';
        } elseif ( $settings['hover_style'] === 'icon' && ! empty( $settings['hover_icon']['value'] ) ) {
            echo '<div class="eel-hover-content eel-hover-icon">';
            \Elementor\Icons_Manager::render_icon( $settings['hover_icon'], [ 'aria-hidden' => 'true' ] );
            echo '</div>';
        }

        echo '</div>'; // .eel-gallery-image-wrap

        // === Caption + Description ===
        if ( ! empty( $caption ) || ( 'yes' === $settings['show_media_description'] && ! empty( get_post_field( 'post_content', $image['id'] ) ) ) ) {
            $content_position = $settings['content_position'] ?? '';
            echo '<div class="eel-gallery-caption-filter ' . esc_attr( $content_position ) . '">';

            // Caption
            if ( ! empty( $caption ) ) {
                echo '<div class="eel-gallery-caption-filter-text">' . esc_html( $caption ) . '</div>';
            }

            // Media Description
            if ( 'yes' === $settings['show_media_description'] ) {
                $image_description = get_post_field( 'post_content', $image['id'] );
                if ( ! empty( $image_description ) ) {
                    echo '<div class="eel-gallery-description">' . esc_html( $image_description ) . '</div>';
                }
            }

            echo '</div>'; // .eel-gallery-caption-filter
        }


        echo '</a>';

        

        echo '</div>'; // .eel-gallery-filter-item
    }
    echo '</div>'; // .eel-gallery-filter

    // === Lightbox ===
    if ( $popup_enabled ) :
        ?>
        <div class="eel-lightbox">
            <span class="eel-close"><?php echo $settings['lightbox_close_text']; ?></span>
            <img class="eel-lightbox-image" src="" alt="">
            <button class="eel-prev"><?php echo $settings['lightbox_prev_text']; ?></button>
            <button class="eel-next"><?php echo $settings['lightbox_next_text']; ?></button>
        </div>
        <?php
    endif;
}

} ?>