<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Post_Pagination_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-post-pagination';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/post-pagination.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/post-pagination.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }

	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-post-pagination';
	}

	public function get_title() {
		return __( 'Easy Post Pagination', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_post_category' ];
	}

	public function get_keywords() {
		return [ 'pagination', 'posts', 'next', 'prev', 'page', 'blog' ];
	}

	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'pagination_content',
			[
				'label' => __( 'Pagination', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_prev_next',
			[
				'label' => __( 'Show Prev/Next', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => __( 'Previous Label', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'easy-elements' ),
				'condition' => [ 'show_prev_next' => 'yes' ],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => __( 'Next Label', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Next', 'easy-elements' ),
				'condition' => [ 'show_prev_next' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// Style Tab
		$this->start_controls_section(
			'pagination_style',
			[
				'label' => __( 'Pagination', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Label Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Label Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-nav-label, {{WRAPPER}} .eel-nav-label i',
			]
		);


		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-nav-title',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$is_editor = ( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() );

		// Only render on single post pages or in Elementor editor
		if ( is_singular( 'post' ) || $is_editor ) {
			echo '<nav class="eel-pagination" aria-label="Post Navigation"><ul>';
			if ( $settings['show_prev_next'] === 'yes' ) {
				// In editor, show dummy links for preview
				if ( $is_editor ) {
					echo '<li><a href="#"><span class="eel-nav-label"><i class="unicon-arrow-left"></i> ' . esc_html( $settings['prev_label'] ) . '</span><span class="eel-nav-title">Sample Previous Post</span></a></li>';
					echo '<li><a href="#"><span class="eel-nav-label">' . esc_html( $settings['next_label'] ) . ' <i class="unicon-arrow-right"></i></span><span class="eel-nav-title">Sample Next Post</span></a></li>';
				} else {
					$prev = get_previous_post_link(
						'<li>%link</li>',
						'<span class="eel-nav-label"><i class="unicon-arrow-left"></i> ' . esc_html( $settings['prev_label'] ) . '</span><span class="eel-nav-title">%title</span>'
					);
					$next = get_next_post_link(
						'<li>%link</li>',
						'<span class="eel-nav-label">' . esc_html( $settings['next_label'] ) . ' <i class="unicon-arrow-right"></i></span><span class="eel-nav-title">%title</span>'
					);
					if ( $prev ) echo $prev;
					if ( $next ) echo $next;
				}
			}
			echo '</ul></nav>';
		}
		// Do nothing on other page types
	}
} 