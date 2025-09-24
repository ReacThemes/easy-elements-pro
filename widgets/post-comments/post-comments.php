<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Post_Comments_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-post-comments';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/post-comments.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/post-comments.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }

	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-post-comments';
	}

	public function get_title() {
		return __( 'Easy Current Post Comments', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_post_category' ];
	}

	public function get_keywords() {
		return [ 'comments', 'posts', 'next', 'prev', 'page', 'blog' ];
	}

	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'comments_content',
			[
				'label' => __( 'Comments', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label' => __( 'Show Comments', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => '',
			]
		);

		$this->add_control(
            'button_color',
            [
                'label' => esc_html__( 'Button Text Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond .form-submit #submit' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__( 'Button Hover Text Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond .form-submit #submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__( 'Button Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond .form-submit #submit' => 'background: {{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'button_bg_hover_color',
            [
                'label' => esc_html__( 'Button Hover Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond .form-submit #submit:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .comment-respond .form-submit #submit',
			]
		);
		$this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .comment-respond .form-submit #submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();		
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Always show the comment form in Elementor editor mode
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="eel-post-comments-widget">';

			global $post;
			$has_comments_form = false;

			if ( $post && post_type_supports( $post->post_type, 'comments' ) ) {
				ob_start();
				comments_template();
				$output = ob_get_clean();
				if ( trim( strip_tags( $output ) ) ) {
					echo $output;
					$has_comments_form = true;
				}
			}

			if ( ! $has_comments_form ) {
				// Show only a message for preview				
				echo '<p>' . esc_html__( 'Comments form will appear here on the frontend.', 'easy-elements' ) . '</p>';				
			}

			echo '</div>';
			return;
		}

		if ( 'yes' === $settings['show_comments'] ) {
			if ( post_password_required() ) {
				echo '<p>' . esc_html__( 'This post is password protected. Enter the password to view comments.', 'easy-elements' ) . '</p>';
				return;
			}

			// Output the comments template
			echo '<div class="eel-post-comments-widget">';
			comments_template();
			echo '</div>';
		} else {
			
		}
	}
} 
