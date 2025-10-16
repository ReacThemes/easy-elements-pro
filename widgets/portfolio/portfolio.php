<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Portfolio_Pro_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-portfolio-pro';
	}

	public function get_title() {
		return __( 'Portfolio', 'easy-elements-pro' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category_pro' ];
	}

	public function get_style_depends() {
		$handle = 'eel-portfolio-pro-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/portfolio.css';

		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style(
				$handle,
				plugins_url( 'css/portfolio.css', __FILE__ ),
				[],
				defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0'
			);
		}
		return [ $handle ];
	}

	public function get_script_depends() {
		$handle  = 'eel-portfolio-script';
		$js_path = plugin_dir_path( __FILE__ ) . 'js/portfolio.js';

		if ( file_exists( $js_path ) && ! wp_script_is( $handle, 'registered' ) ) {
			wp_register_script(
				$handle,
				plugins_url( 'js/portfolio.js', __FILE__ ),
				[], // dependency empty
				defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0',
				true // load in footer
			);
		}

		return [ $handle ];
	}


	/**
	 * Get available post types for dynamic source
	 * Only shows post types that have published posts
	 */
	private function get_available_post_types() {
		$post_types = get_post_types(
			[
				'public' => true,
				'show_ui' => true,
			],
			'objects'
		);

		$options = [];

		foreach ( $post_types as $post_type ) {
			// Check if this post type has any published posts
			$posts_count = wp_count_posts( $post_type->name );
			
			// Only include post types that have published posts
			if ( $posts_count && $posts_count->publish > 0 ) {
				$options[ $post_type->name ] = $post_type->label;
			}
		}

		return $options;
	}


	/**
	 * Get available taxonomies for a post type
	 */
	private function get_post_type_taxonomies( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$options = [];

		foreach ( $taxonomies as $taxonomy ) {
			if ( $taxonomy->public && $taxonomy->show_ui ) {
				$options[ $taxonomy->name ] = $taxonomy->label;
			}
		}

		return $options;
	}

	/**
	 * Get terms from a specific taxonomy for a post type
	 */
	private function get_taxonomy_terms( $post_type, $taxonomy ) {
		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'post_type'  => $post_type,
			'hide_empty' => true,
		] );

		$options = [];
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

	/**
	 * Get dynamic posts based on settings
	 */
	private function get_dynamic_posts( $settings ) {
		$post_type = $settings['dynamic_post_type'] ?? 'post';
		$args = [
			'post_type'      => $post_type,
			'posts_per_page' => $settings['posts_per_page'] ?? 6,
			'post_status'    => 'publish',
			'orderby'        => $settings['orderby'] ?? 'date',
			'order'          => $settings['order'] ?? 'DESC',
		];

		// Add category filter if selected
		if ( ! empty( $settings['dynamic_categories'] ) ) {
			$taxonomy = $settings['dynamic_taxonomy'] ?? 'category';
			$args['tax_query'] = [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $settings['dynamic_categories'],
				],
			];
		}

		$posts = get_posts( $args );
		$portfolio_items = [];

		foreach ( $posts as $post ) {
			$featured_image_id = get_post_thumbnail_id( $post->ID );
			$image_url = '';
			$hover_image_url = '';
			
			if ( $featured_image_id ) {
				$image_url = wp_get_attachment_image_url( $featured_image_id, 'large' );
				$hover_image_url = $image_url; // Use same image for hover by default
			} else {
				$image_url = \Elementor\Utils::get_placeholder_image_src();
				$hover_image_url = $image_url;
			}

			// Get categories from post type taxonomy
			$category_text = '';
			if ( ! empty( $settings['dynamic_taxonomy'] ) ) {
				$taxonomy = $settings['dynamic_taxonomy'];
				$terms = get_the_terms( $post->ID, $taxonomy );
				if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
					$category_names = wp_list_pluck( $terms, 'name' );
					$category_text = implode( ', ', $category_names );
				}
			}

			$portfolio_items[] = [
				'title'       => $post->post_title,
				'description' => wp_trim_words( $post->post_content, 20, '...' ),
				'category'    => $category_text,
				'image'       => [
					'id'  => $featured_image_id,
					'url' => $image_url,
				],
				'hover_image' => [
					'id'  => $featured_image_id,
					'url' => $hover_image_url,
				],
				'post_id'     => $post->ID,
				'permalink'   => get_permalink( $post->ID ),
			];
		}

		return $portfolio_items;
	}

	protected function register_controls() {
		// === Portfolio Section ===
		$this->start_controls_section(
			'portfolio_section',
			[
				'label' => esc_html__('Portfolio Settings', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'portfolio_source',
			[
				'label'   => esc_html__('Select Source', 'easy-elements-pro'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom'  => esc_html__('Custom Source', 'easy-elements-pro'),
					'dynamic' => esc_html__('Dynamic Source (from CPT)', 'easy-elements-pro'),
				],
			]
		);

		// Dynamic Post Type Selection
		$this->add_control(
			'dynamic_post_type',
			[
				'label'     => esc_html__('Post Type', 'easy-elements-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'post',
				'options'   => $this->get_available_post_types(),
				'condition' => [
					'portfolio_source' => 'dynamic',
				],
			]
		);

		// Number of Posts
		$this->add_control(
			'posts_per_page',
			[
				'label'     => esc_html__('Number of Posts', 'easy-elements-pro'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 1,
				'max'       => 50,
				'condition' => [
					'portfolio_source' => 'dynamic',
				],
			]
		);

		// Order By
		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__('Order By', 'easy-elements-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => [
					'date'          => esc_html__('Date', 'easy-elements-pro'),
					'title'         => esc_html__('Title', 'easy-elements-pro'),
					'menu_order'    => esc_html__('Menu Order', 'easy-elements-pro'),
					'rand'          => esc_html__('Random', 'easy-elements-pro'),
					'comment_count' => esc_html__('Comment Count', 'easy-elements-pro'),
				],
				'condition' => [
					'portfolio_source' => 'dynamic',
				],
			]
		);

		// Order
		$this->add_control(
			'order',
			[
				'label'     => esc_html__('Order', 'easy-elements-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => [
					'ASC'  => esc_html__('Ascending', 'easy-elements-pro'),
					'DESC' => esc_html__('Descending', 'easy-elements-pro'),
				],
				'condition' => [
					'portfolio_source' => 'dynamic',
				],
			]
		);

		// Taxonomy Selection for Categories
		$this->add_control(
			'dynamic_taxonomy',
			[
				'label'     => esc_html__('Category Taxonomy', 'easy-elements-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'category',
				'options'   => $this->get_post_type_taxonomies( 'post' ), // Default to post taxonomies
				'condition' => [
					'portfolio_source' => 'dynamic',
				],
			]
		);

		// Category Selection
		$this->add_control(
			'dynamic_categories',
			[
				'label'     => esc_html__('Select Categories', 'easy-elements-pro'),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $this->get_taxonomy_terms( 'post', 'category' ), // Default to post categories
				'condition' => [
					'portfolio_source' => 'dynamic',
				],
			]
		);


		$this->add_control(
			'portfolio_skins',
			[
				'label'   => esc_html__('Select Skin', 'easy-elements-pro'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'skin1',
				'options' => [
					'skin1' => esc_html__('Skin 01', 'easy-elements-pro'),
					'skin2' => esc_html__('Skin 02', 'easy-elements-pro'),
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label'   => esc_html__('Title', 'easy-elements-pro'),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__('Any Content Title Here', 'easy-elements-pro'),
				'placeholder' => esc_html__('Enter Title Here...', 'easy-elements-pro'),
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label' => esc_html__('Icon', 'easy-elements-pro'),
				'type'  => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__('Description', 'easy-elements-pro'),
				'type'  => Controls_Manager::TEXTAREA,
				'default' => esc_html__('', 'easy-elements-pro'),
				'placeholder' => esc_html__('This is a sample project/service/portfolio or any description.', 'easy-elements-pro'),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'location',
			[
				'label' => esc_html__('Location', 'easy-elements-pro'),
				'type'  => Controls_Manager::TEXTAREA,
				'default' => esc_html__('', 'easy-elements-pro'),
				'placeholder' => esc_html__('Enter Location Here...', 'easy-elements-pro'),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__('Image', 'easy-elements-pro'),
				'type'  => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'category',
			[
				'label'   => esc_html__('Category', 'easy-elements-pro'),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__('', 'easy-elements-pro'),
				'placeholder' => esc_html__('Enter Custom Category Here...', 'easy-elements-pro'),
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'custom_link',
			[
				'label' => esc_html__('Link', 'easy-elements-pro'),
				'type'  => Controls_Manager::URL,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'label_block' => true,
				'placeholder' => esc_html__('https://your-link.com', 'easy-elements-pro'),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'portfolio_items',
			[
				'label'     => esc_html__('All Items', 'easy-elements-pro'),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => [],
				'title_field' => '{{{ title }}}',
				'condition' => [
					'portfolio_source' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'large',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__('HTML Tag', 'easy-elements-pro'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'Div',
					'span' => 'Span',
					'p' => 'P',
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'easy-elements-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1 Column', 'easy-elements-pro' ),
					'2' => esc_html__( '2 Columns', 'easy-elements-pro' ),
					'3' => esc_html__( '3 Columns', 'easy-elements-pro' ),
					'4' => esc_html__( '4 Columns', 'easy-elements-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-wrap-pro.pro-skin2' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'portfolio_skins' => 'skin2',
				],
			]
		);

		$this->add_responsive_control(
			'item_spacing',
			[
				'label'      => esc_html__('Spacing Right Item', 'easy-elements-pro'),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-portfolio-item-pro:nth-child(even)' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'columns' => ['1', '2'],
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'contetn_box_section',
			[
				'label' => esc_html__('Content Box', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'box_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-content-pro',
				'condition' => [
					'portfolio_skins' => 'skin1',
				],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-content-pro',
				'condition' => [
					'portfolio_skins' => 'skin1',
				],
			]
		);

		$this->add_responsive_control(
            'box_radius',
            [
                'label'      => esc_html__('Border Radius', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-content-pro' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'portfolio_skins' => 'skin1',
				],
            ]
        );

		$this->add_responsive_control(
            'box_spacing',
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
                    '{{WRAPPER}} .eel-portfolio-item-pro' => 'gap: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'portfolio_skins' => 'skin1',
				],
            ]
        );

		$this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-content-pro, {{WRAPPER}} .eel-portfolio-wrap-pro.pro-skin2 .eel-portfolio-item-pro .eel-portfolio-content-pro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'content_box_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 300, 'max' => 1000, 'step' => 1 ],
                    '%'  => [ 'min' => 30, 'max' => 100, 'step' => 1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-content-pro' => 'flex: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'portfolio_skins' => 'skin1',
				],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'image_box_section',
			[
				'label' => esc_html__('Image Box', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
            'image_radius',
            [
                'label'      => esc_html__('Border Radius', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-image, {{WRAPPER}} .eel-portfolio-wrap-pro.pro-skin2 .eel-portfolio-item-pro' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'img_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'portfolio_skins' => 'skin1',
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
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-image, {{WRAPPER}} .eel-portfolio-wrap-pro.pro-skin2 img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover; width: 100%;',
                ],
            ]
        );

		$this->add_responsive_control(
            'image_box_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 300, 'max' => 1000, 'step' => 1 ],
                    '%'  => [ 'min' => 30, 'max' => 100, 'step' => 1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-image' => 'flex: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'portfolio_skins' => 'skin1',
				],
            ]
        );

		$this->add_control(
			'overlay_heading',
			[
				'label' => esc_html__( 'Image Overlay', 'easy-elements-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'portfolio_skins' => 'skin2',
				],
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'overlay_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-portfolio-item-pro:before',
				'condition' => [
					'portfolio_skins' => 'skin2',
				],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_item',
			[
				'label' => esc_html__('Per Item', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_per_item_style');

        // ------------------ Normal Tab ------------------
        $this->start_controls_tab(
            'tab_per_item_normal',
            [
                'label' => esc_html__('Normal', 'easy-elements-pro'),
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'per_item_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-info-pro',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-info-pro',
			]
		);

		$this->add_responsive_control(
            'per_item_radius',
            [
                'label'      => esc_html__('Border Radius', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-info-pro' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'per_item_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-info-pro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'per_item_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-info-pro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_tab();
        $this->start_controls_tab(
            'tab_per_item_hover',
            [
                'label' => esc_html__('Hover', 'easy-elements-pro'),
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'per_item_bg_hover',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-portfolio-item-pro .eel-portfolio-info-pro:hover',
            ]
        );

		$this->end_controls_tab();
        $this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_title',
			[
				'label' => esc_html__('Title', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_title_style');

        // ------------------ Normal Tab ------------------
        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => esc_html__('Normal', 'easy-elements-pro'),
            ]
        );


		// Title Style
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Color', 'easy-elements-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-title-pro' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__('Typography', 'easy-elements-pro'),
				'selector' => '{{WRAPPER}} .eel-portfolio-title-pro',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'selector' => '{{WRAPPER}} .eel-portfolio-title-pro',
			]
		);

		$this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-title-pro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-title-pro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => esc_html__('Hover', 'easy-elements-pro'),
            ]
        );

		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__('Color', 'easy-elements-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-title-pro:hover, .eel-portfolio-item-pro.pro-skin1 .eel-portfolio-info-pro .eel-portfolio-title-pro:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-portfolio-title-pro:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-portfolio-title-pro:hover svg, {{WRAPPER}} .eel-portfolio-title-pro:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'title_border_hover',
				'selector' => '{{WRAPPER}} .eel-portfolio-title-pro:hover, {{WRAPPER}} .eel-portfolio-item-pro.pro-skin1 .eel-portfolio-info-pro:hover .eel-portfolio-title-pro, .eel-portfolio-item-pro.pro-skin2 .eel-portfolio-info-pro:hover .eel-portfolio-title-pro',
			]
		);

		$this->end_controls_tab();
        $this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_desc',
			[
				'label' => esc_html__('Description', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Description Style
		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__('Color', 'easy-elements-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'label'    => esc_html__('Typography', 'easy-elements-pro'),
				'selector' => '{{WRAPPER}} .eel-portfolio-desc',
			]
		);

		$this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->end_controls_section();


		$this->start_controls_section(
			'portfolio_category',
			[
				'label' => esc_html__('Category', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Description Style
		$this->add_control(
			'category_color',
			[
				'label' => esc_html__('Color', 'easy-elements-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-category-pro' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'category_bg',
                'types'    => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .eel-portfolio-category-pro',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'category_typography',
				'label'    => esc_html__('Typography', 'easy-elements-pro'),
				'selector' => '{{WRAPPER}} .eel-portfolio-category-pro',
			]
		);		

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'category_border',
				'selector' => '{{WRAPPER}} .eel-portfolio-category-pro',
			]
		);

		$this->add_responsive_control(
            'category_radius',
            [
                'label'      => esc_html__('Border Radius', 'easy-elements-pro'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-portfolio-category-pro' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'category_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-category-pro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );		

		$this->add_responsive_control(
            'category_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-category-pro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_location',
			[
				'label' => esc_html__('Location', 'easy-elements-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Description Style
		$this->add_control(
			'location_color',
			[
				'label' => esc_html__('Color', 'easy-elements-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-location-pro' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'location_typography',
				'label'    => esc_html__('Typography', 'easy-elements-pro'),
				'selector' => '{{WRAPPER}} .eel-portfolio-location-pro',
			]
		);	
		
		$this->add_responsive_control(
            'location_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-portfolio-location-pro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_title_style_section',
			[
				'label' => esc_html__( 'Icon', 'easy-elements-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements-pro' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-title-pro i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-portfolio-title-pro svg, {{WRAPPER}} .eel-portfolio-title-pro svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 8,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-title-pro svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-portfolio-title-pro i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'easy-elements-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-portfolio-info-pro i, {{WRAPPER}} .eel-portfolio-info-pro svg' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_show',
			[
				'label'        => esc_html__( 'Show Icon', 'easy-elements-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'easy-elements-pro' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		// Add JavaScript to handle dynamic taxonomy and category loading
		add_action( 'elementor/editor/before_enqueue_scripts', function() {
			?>
			<script>
			elementor.hooks.addAction('panel/open_editor/widget/eel-portfolio-pro', function() {
				setTimeout(function() {
					// Function to update taxonomy options
					function updateTaxonomyOptions(postType) {
						if (!postType) return;
						
						// Get taxonomies for the selected post type via AJAX
						jQuery.ajax({
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							type: 'POST',
							data: {
								action: 'eel_get_post_type_taxonomies',
								post_type: postType,
								nonce: '<?php echo wp_create_nonce('eel_taxonomy_nonce'); ?>'
							},
							success: function(response) {
								if (response.success && response.data) {
									var taxonomyControl = elementor.getControlView('dynamic_taxonomy');
									if (taxonomyControl) {
										taxonomyControl.model.set('options', response.data);
										taxonomyControl.render();
									}
									
									// Update category options for the first taxonomy
									var firstTaxonomy = Object.keys(response.data)[0];
									if (firstTaxonomy) {
										updateCategoryOptions(postType, firstTaxonomy);
									}
								}
							}
						});
					}
					
					// Function to update category options
					function updateCategoryOptions(postType, taxonomy) {
						if (!postType || !taxonomy) return;
						
						jQuery.ajax({
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							type: 'POST',
							data: {
								action: 'eel_get_taxonomy_terms',
								post_type: postType,
								taxonomy: taxonomy,
								nonce: '<?php echo wp_create_nonce('eel_taxonomy_nonce'); ?>'
							},
							success: function(response) {
								if (response.success && response.data) {
									var categoryControl = elementor.getControlView('dynamic_categories');
									if (categoryControl) {
										categoryControl.model.set('options', response.data);
										categoryControl.render();
									}
								}
							}
						});
					}
					
					// Listen for post type control changes
					var postTypeControl = elementor.getControlView('dynamic_post_type');
					if (postTypeControl) {
						postTypeControl.model.on('change:dynamic_post_type', function() {
							var postType = this.get('dynamic_post_type');
							updateTaxonomyOptions(postType);
						});
					}
					
					// Listen for taxonomy control changes
					var taxonomyControl = elementor.getControlView('dynamic_taxonomy');
					if (taxonomyControl) {
						taxonomyControl.model.on('change:dynamic_taxonomy', function() {
							var postType = elementor.getControlView('dynamic_post_type').model.get('dynamic_post_type');
							var taxonomy = this.get('dynamic_taxonomy');
							updateCategoryOptions(postType, taxonomy);
						});
					}
				}, 100);
			});
			</script>
			<?php
		});
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tag   = $settings['title_tag'];
		$portfolio_items = [];
		
		// Handle portfolio source
		if ( $settings['portfolio_source'] === 'dynamic' ) {
			// Get posts dynamically based on selected post type
			$portfolio_items = $this->get_dynamic_posts( $settings );
			
			if ( empty( $portfolio_items ) ) {
				echo '<p>' . esc_html__( 'No posts found for the selected post type.', 'easy-elements-pro' ) . '</p>';
				return;
			}
		} else {
			// Custom source
			$portfolio_items = $settings['portfolio_items'] ?? [];
			
			// Process custom items to add hover images
			foreach ( $portfolio_items as $index => $item ) {
				// If no hover image is set, use the main image
				if ( empty( $item['hover_image']['url'] ) ) {
					$portfolio_items[$index]['hover_image'] = $item['image'];
				}
			}
			
			if ( empty( $portfolio_items ) ) {
				echo '<p>' . esc_html__( 'Please add some portfolio items.', 'easy-elements-pro' ) . '</p>';
				return;
			}
		}
		
		// Prepare data for template
		$template_data = [
			'settings' => $settings,
			'portfolio_items' => $portfolio_items,
		];
		
		$skin = $settings['portfolio_skins'] ?? 'skin1';
		$template_path = plugin_dir_path(__FILE__) . 'skins/' . $skin . '.php';
		
		if ( file_exists( $template_path ) ) {
			// Extract variables for template use
			extract( $template_data );
			include $template_path;
		}
	}
}

// AJAX handlers for dynamic taxonomy and category loading
add_action( 'wp_ajax_eel_get_post_type_taxonomies', 'eel_get_post_type_taxonomies_callback' );
add_action( 'wp_ajax_eel_get_taxonomy_terms', 'eel_get_taxonomy_terms_callback' );

function eel_get_post_type_taxonomies_callback() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'eel_taxonomy_nonce' ) ) {
		wp_die( 'Security check failed' );
	}

	$post_type = sanitize_text_field( $_POST['post_type'] );
	
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	$options = [];

	foreach ( $taxonomies as $taxonomy ) {
		if ( $taxonomy->public && $taxonomy->show_ui ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}
	}

	wp_send_json_success( $options );
}

function eel_get_taxonomy_terms_callback() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'eel_taxonomy_nonce' ) ) {
		wp_die( 'Security check failed' );
	}

	$post_type = sanitize_text_field( $_POST['post_type'] );
	$taxonomy = sanitize_text_field( $_POST['taxonomy'] );

	$terms = get_terms( [
		'taxonomy'   => $taxonomy,
		'post_type'  => $post_type,
		'hide_empty' => true,
	] );

	$options = [];
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}
	}

	wp_send_json_success( $options );
}
?>