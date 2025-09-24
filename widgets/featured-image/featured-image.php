<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Featured_Image_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-featured-image';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/featured-image.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/featured-image.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }
	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-featured-image';
	}

	public function get_title() {
		return __( 'Easy Featured Image', 'easy-elements' );
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
	            'label' => esc_html__('Featured Image Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

		$this->add_control(
			'featured_image_width',
			[
				'label' => esc_html__('Width', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-featured-image-inner img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'featured_image_height',
			[
				'label' => esc_html__('Height', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'%' => [
						'min' => 100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-featured-image-inner img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'.eel-featured-image-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		// Image Size Control
		$this->add_control(
			'image_size',
			[
				'label' => esc_html__('Image Size', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'thumbnail' => esc_html__('Thumbnail', 'easy-elements'),
					'medium' => esc_html__('Medium', 'easy-elements'),
					'large' => esc_html__('Large', 'easy-elements'),
					'full' => esc_html__('Full', 'easy-elements'),
				],
			]
		);

		// Add show/hide image link control
		$this->add_control(
			'show_image_link',
			[
				'label' => esc_html__('Show Image Link', 'easy-elements'),
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
		$image_size = isset($settings['image_size']) ? $settings['image_size'] : 'full';
		// Show preview in Elementor editor
		$editor_mode = ( \Elementor\Plugin::instance()->editor->is_edit_mode() );
		$wrapper_classes = 'eel-featured-image-wrapper';
		if ( $editor_mode ) {
			$wrapper_classes .= ' eel-featured-image-editor-preview';
		}
		global $post;
		$post_id = 0;
		if ( isset( $post ) && isset( $post->ID ) ) {
			$post_id = $post->ID;
		} elseif ( isset( $_GET['preview_id'] ) ) {
			$post_id = intval( $_GET['preview_id'] );
		}
		if ( $post_id && has_post_thumbnail( $post_id ) ) {
			$image_url = get_the_post_thumbnail_url( $post_id, $image_size );
			$image_alt = get_the_title( $post_id );
			$post_link = get_permalink( $post_id );
		} else {
			
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="eel-featured-image-inner">
			<?php if ( !empty($image_url) ) : ?>
				<?php if ( !empty($settings['show_image_link']) && $settings['show_image_link'] === 'yes' && !empty($post_link) ) : ?>
					<a href="<?php echo esc_url( $post_link ); ?>" class="eel-featured-image-link">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ?: __('Featured Image', 'easy-elements') ); ?>" class="eel-featured-image-img" loading="lazy" />
					</a>
				<?php else : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ?: __('Featured Image', 'easy-elements') ); ?>" class="eel-featured-image-img" loading="lazy" />
				<?php endif; ?>
			<?php else : ?>
				<?php if ( $editor_mode ) { ?>
				<div class="eel-featured-image-placeholder">
					<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" class="eel-featured-image-placeholder-icon">
						<defs>
							<linearGradient id="imgGradient" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
								<stop stop-color="#7fd7ff"/>
								<stop offset="1" stop-color="#0073e6"/>
							</linearGradient>
						</defs>
						<rect x="8" y="14" width="48" height="36" rx="5" fill="#f0f6ff" stroke="url(#imgGradient)" stroke-width="2.5"/>
						<circle cx="22" cy="28" r="5" fill="#b3d4fc" />
						<path d="M14 44L26 32L36 42L44 34L52 42" stroke="#b3d4fc" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<div class="eel-featured-image-placeholder-title">No Featured Image</div>
					<div class="eel-featured-image-placeholder-desc">Please add a featured image to display here.</div>
					<?php if ( $editor_mode ) : ?>
						<a href="#" class="eel-featured-image-placeholder-btn" tabindex="-1">Set Featured Image</a>
					<?php endif; ?>
				</div>
			<?php } endif; ?>
			</div>
		</div>
		<?php
	}
}
