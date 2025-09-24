<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Post_Title_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-post-title';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/post-title.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/post-title.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }
	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-post-title';
	}

	public function get_title() {
		return __( 'Easy Post Title', 'easy-elements' );
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
	            'label' => esc_html__('Post Title Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('HTML Tag', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
			]
		);
		
		$this->add_control(
			'link_to_post',
			[
				'label' => esc_html__('Link to Post', 'easy-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'easy-elements'),
				'label_off' => esc_html__('No', 'easy-elements'),
				'default' => '',
			]
		);

		$this->add_control(
			'align',
			[
				'label' => esc_html__('Alignment', 'easy-elements'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'easy-elements'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'easy-elements'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'easy-elements'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Title Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .eel-post-title',
			]
		);
		$this->add_responsive_control(
			'margin',
			[
				'label' => esc_html__('Margin', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tag = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h1';
		$align = !empty($settings['align']) ? $settings['align'] : 'left';
		$link = !empty($settings['link_to_post']);
		$title = '';
		if ( isset($easy_elements_original_post) && $easy_elements_original_post instanceof WP_Post ) {
			$title = get_the_title( $easy_elements_original_post );
			$post_url = get_permalink( $easy_elements_original_post );
		} else {
			$title = get_the_title();
			$post_url = get_permalink();
		}
		?>
		<<?php echo esc_attr($tag); ?> class="eel-post-title" style="text-align:<?php echo esc_attr($align); ?>;">
		<?php if ( $link ) : ?>
			<a href="<?php echo esc_url($post_url); ?>"><?php echo esc_html($title); ?></a>
		<?php else : ?>
			<?php echo esc_html($title); ?>
		<?php endif; ?>
		</<?php echo esc_attr($tag); ?>>
		<?php
	}
}
