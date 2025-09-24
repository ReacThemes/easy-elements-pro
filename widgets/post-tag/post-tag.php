<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Post_Tags_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-post-tags';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/post-tag.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( 
				$handle, 
				plugins_url( 'css/post-tag.css', __FILE__ ), 
				[], 
				defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' 
			);
	    }

	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-post-tags';
	}

	public function get_title() {
		return __( 'Easy Current Post Tags', 'easy-elements-pro' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_post_category' ];
	}

	public function get_keywords() {
		return [ 'tags', 'post tags', 'meta', 'taxonomy', 'blog' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'tags_content',
			[
				'label' => __( 'Tags', 'easy-elements-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label'        => __( 'Show Label', 'easy-elements-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements-pro' ),
				'label_off'    => __( 'No', 'easy-elements-pro' ),
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'label_text',
			[
				'label'       => __( 'Label Text', 'easy-elements-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tags:', 'easy-elements-pro' ),
				'condition'   => [
					'show_label' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title Label', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget .eel-tags-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget .eel-tags-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget .eel-tags-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tags_style',
			[
				'label' => __( 'Tag', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tags_typography',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget a',
			]
		);

		$this->start_controls_tabs( 'tabs_tag_style' );

			// Normal Tab
			$this->start_controls_tab(
				'tab_tag_normal',
				[
					'label' => __( 'Normal', 'easy-elements' ),
				]
			);

			$this->add_control(
				'tags_color',
				[
					'label'     => __( 'Text Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-post-tags-widget a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'tags__bg_color',
					'label' => __('Background', 'easy-elements'),
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .eel-post-tags-widget a',
				]
			);

			$this->end_controls_tab();

			// Hover Tab
			$this->start_controls_tab(
				'tab_tag_hover',
				[
					'label' => __( 'Hover', 'easy-elements' ),
				]
			);

			$this->add_control(
				'tags_hover_color',
				[
					'label'     => __( 'Text Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-post-tags-widget a:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'tags_hover_bg_color',
					'label' => __('Background', 'easy-elements'),
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .eel-post-tags-widget a:hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tags_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tags_padding',
			[
				'label' => __( 'Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		if ( post_password_required() ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$tags = get_the_tags();

		if ( ! $tags || is_wp_error( $tags ) ) {
			return;
		}

		echo '<div class="eel-post-tags-widget">';
		
		if ( 'yes' === $settings['show_label'] && ! empty( $settings['label_text'] ) ) {
			echo '<span class="eel-tags-label">' . esc_html( $settings['label_text'] ) . '</span> ';
		}

		foreach ( $tags as $tag ) {
			$tag_link = get_tag_link( $tag->term_id );
			echo '<a href="' . esc_url( $tag_link ) . '" rel="tag">' . esc_html( $tag->name ) . '</a> ';
		}

		echo '</div>';
	}
}
?>