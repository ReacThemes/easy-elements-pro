<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Excerpt_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-excerpt';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/excerpt.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/excerpt.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }

	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-excerpt';
	}

	public function get_title() {
		return __( 'Easy Post Excerpt', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_post_category' ];
	}

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Excerpt Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,	
	        ]
	    );

		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__('Excerpt Length', 'easy-elements'),
				'type' => Controls_Manager::NUMBER,
				'default' => 55,
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__('Read More', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Read More', 'easy-elements'),
			]
		);


		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__('Excerpt Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .eel-excerpt',
			]
		);

		// Add Padding control for the button
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__('Button Padding', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt-readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Add Margin control for the button
		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__('Button Margin', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Start Button Style Tabs
		$this->start_controls_tabs('button_style_tabs');

		// Normal State Tab
		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__('Normal', 'easy-elements'),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__('Button Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt-readmore' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_buttpm',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .eel-excerpt-readmore',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .eel-excerpt-readmore',
			]
		);
		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__('Hover', 'easy-elements'),
			]
		);
		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__('Button Hover Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt-readmore:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-excerpt-readmore:hover',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_hover_border',
				'selector' => '{{WRAPPER}} .eel-excerpt-readmore:hover',
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'display_button',
			[
				'label' => esc_html__('Read More Enable', 'easy-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'easy-elements'),
				'label_off' => esc_html__('Hide', 'easy-elements'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
	
		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$editor_mode = ( \Elementor\Plugin::instance()->editor->is_edit_mode() );
		$excerpt_length = !empty($settings['excerpt_length']) ? intval($settings['excerpt_length']) : 55;
		$read_more_text = !empty($settings['read_more_text']) ? $settings['read_more_text'] : esc_html__('Read More', 'easy-elements');
		$display_button = !isset($settings['display_button']) || $settings['display_button'] === 'yes';
		
		if ( $editor_mode ) {
			// Show a sample excerpt in editor mode
			$excerpt = esc_html__('This is a preview of the post excerpt. The actual excerpt will appear here on the frontend.', 'easy-elements');
			$post_url = '#';
		} else {
			$excerpt = get_the_excerpt();
			$post_url = get_permalink();
		}
		// Trim excerpt to desired length
		$excerpt = wp_trim_words( $excerpt, $excerpt_length, '...' );
		?>
		<div class="eel-excerpt">
			<div class="eel-excerpt-text"><?php echo esc_html($excerpt); ?></div>
			<?php if ( !empty($read_more_text) && $display_button ) : ?>
				<a href="<?php echo esc_url($post_url); ?>" class="eel-excerpt-readmore"> <?php echo esc_html($read_more_text); ?> </a>
			<?php endif; ?>
		</div>
		<?php
	}
}
