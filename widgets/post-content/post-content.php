<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Post_content_Widget extends \Elementor\Widget_Base {
	public function get_style_depends() {
	    $handle = 'eel-post-content';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/post-content.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/post-content.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }		
	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-post-content';
	}

	public function get_title() {
		return __( 'Easy Post Content', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category_pro' ];
	}

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Post Content Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );
		
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .eel-post-content',
			]
		);
		
		$this->end_controls_section();

	}

protected function render() {
    global $post;

    if ( ! $post ) {
        return;
    }

    $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( get_the_ID() );

    if ( empty( $content ) ) {
        $content = apply_filters( 'the_content', $post->post_content );
    }

    echo '<div class="eel-post-content">';
        echo $content; 
    echo '</div>';
}


}
