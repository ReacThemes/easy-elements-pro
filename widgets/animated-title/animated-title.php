<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Animated_Title_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-animated-title-style';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/animated-title.css';
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/animated-title.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }
	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-animated-title';
	}

	public function get_title() {
		return __( 'Animated Title', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Easy Heading Settings', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__('Title', 'easy-elements'),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Heading Here', 'easy-elements'),
				'label_block' => true,
				'description' => esc_html__('You can highlight part of the text using double curly brackets. Example: Heading {{Here}} will highlight "Here".', 'easy-elements'),
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label'   => esc_html__( 'Animation Type', 'easy-elements-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'split-text',
				'options' => [
					'characters'            => esc_html__( 'Characters (Slide In)', 'easy-elements-pro' ),
					'split-text'            => esc_html__( 'Split Text (Letters Up)', 'easy-elements-pro' ),
					'split-words'           => esc_html__( 'Split Words (Up)', 'easy-elements-pro' ),
					'split-lines'           => esc_html__( 'Split Lines (Bottom)', 'easy-elements-pro' ),
					'split-lines-bottom-v2' => esc_html__( 'Split Lines (Bottom v2)', 'easy-elements-pro' ),
					'split-lines-left'      => esc_html__( 'Split Lines (Left v1)', 'easy-elements-pro' ),
					'split-lines-leftv2'    => esc_html__( 'Split Lines (Left v2)', 'easy-elements-pro' ),
					'split-lines-up'        => esc_html__( 'Split Lines (Up)', 'easy-elements-pro' ),
					'text-reveal'           => esc_html__( 'Text Reveal (Chars Fade In)', 'easy-elements-pro' ),
					'text-word'             => esc_html__( 'Text Word (Rotate Up)', 'easy-elements-pro' ),
					'text-bounce'           => esc_html__( 'Text Bounce', 'easy-elements-pro' ),
					'typewriter'            => esc_html__( 'Typewriter Effect', 'easy-elements-pro' ),
					'words-slide-up'  => esc_html__( 'Words Slide Up', 'easy-elements-pro' ),
					'words-rotate-in'       => esc_html__( 'Words Rotate In', 'easy-elements-pro' ),
					'split-scroll-pin'      => esc_html__( 'Split Scroll Pin', 'easy-elements-pro' ),					
				],
			]
		);


		$this->add_control(
			'show_gradient_title',
			[
				'label' => esc_html__( 'Gradient', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'show_stroke_title',
			[
				'label' => esc_html__( 'Stroke', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
		    'link',
		    [
		        'label' => __( 'Link', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::URL,
		        'placeholder' => 'https://your-link.com',
		        'default' => [
		            'url' => '',
		            'is_external' => false,
		            'nofollow' => false,
		        ],
		        'show_external' => true,
		        'description' => esc_html__( 'Set a URL to make the heading clickable. Supports opening in a new tab and nofollow attribute.', 'easy-elements' ),
		    ]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__('Select Tag', 'easy-elements'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'span' => 'span',
					'p' => 'p',
					'div' => 'div',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__('Alignment', 'easy-elements'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__('Left', 'easy-elements'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'easy-elements'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'easy-elements'),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'easy-elements'),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eel-animated-title' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .image-heading' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Heading
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__('Title', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__('Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-animated-title .e-e-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_title' => '',
					'show_stroke_title'   => '',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__('Title Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-animated-title .e-e-title',
			]
		);
		$this->add_control(
			'title_opacity',
			[
				'label'     => esc_html__('Opacity', 'easy-elements'),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .eel-animated-title .e-e-title' => 'opacity: {{SIZE}};',
				],
				'default'     => [
		            'size' => 1,
		            'unit' => 'px',
		        ],
				'condition' => [
					'show_stroke_title' => 'yes',
				],
			]
		);

		$this->add_control(
		    'title_text_stroke',
		    [
		        'label'       => esc_html__('Stroke Width', 'easy-elements'),
		        'type'        => Controls_Manager::SLIDER,
		        'selectors'   => [
		            '{{WRAPPER}} .eel-animated-title .e-e-title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
		        ],
		        'default'     => [
		            'size' => 0,
		            'unit' => 'px',
		        ],
				'condition' => [
					'show_stroke_title' => 'yes',
				],
		    ]
		);

		$this->add_control(
		    'title_text_stroke_color',
		    [
		        'label'     => esc_html__('Stroke Color', 'easy-elements'),
		        'type'      => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-animated-title .e-e-title' => '-webkit-text-stroke-color: {{VALUE}};',
		        ],
		        'condition' => [
					'show_stroke_title' => 'yes',
				],
		    ]
		);

		// Text Shadow
		$this->add_group_control(
		    \Elementor\Group_Control_Text_Shadow::get_type(),
		    [
		        'name'     => 'title_text_shadow',
		        'label'    => esc_html__('Text Shadow', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-animated-title .e-e-title',
		    ]
		);
	
		$this->end_controls_section();

		// Heading
		$this->start_controls_section(
			'section_gradient_heading_style',
			[
				'label' => esc_html__('Gradient', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_gradient_title' => 'yes',
				], 
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'title_gradient_color',  
				'label'     => __( 'Title Color', 'easy-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .eel-animated-title .e-e-title.e-e-gradient-title, {{WRAPPER}} .e-e-gradient-title div',
				
			]
		);
	
		$this->end_controls_section();


		// Highlight
		$this->start_controls_section(
			'section_highlight_style',
			[
				'label' => esc_html__('Highlight Title', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'highlight_color',
			[
				'label'     => esc_html__('Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-animated-title .e-e-title span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'highlight_typography',
				'label'    => esc_html__('Highlight Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-animated-title .e-e-title span',
			]
		);	
		$this->end_controls_section();
	}


	protected function render() {
	    $settings = $this->get_settings_for_display();	
	    $tag = isset($settings['title_tag']) ? $settings['title_tag'] : 'h2';
	    $title = preg_replace_callback( '/\{\{(.*?)\}\}/', function( $matches ) {
	            return '<span>' . esc_html( trim( $matches[1] ) ) . '</span>';
	        }, $settings['title'] );

	    	if ( ! empty( $settings['link']['url'] ) ) {
	            $this->add_link_attributes( 'title_link', $settings['link'] );
	            $title = sprintf(
	                '<a %s>%s</a>',
	                $this->get_render_attribute_string( 'title_link' ),
	                $title
	            );
	        }
			$unique_id = 'eel-heading-' . $this->get_id();
			$animation = ! empty($settings['animation_type']) ? $settings['animation_type'] : '';
	    ?>
	    <div class="eel-animated-title" <?php if(!empty($animation)) : ?> data-eel-animation="<?php echo esc_attr($animation); ?>" <?php endif; ?>>
	    	<?php    
		    $allowed_tags = ['h1','h2','h3','h4','h5','h6','div','span', 'p'];
			$tag = in_array(strtolower($tag), $allowed_tags, true) ? strtolower($tag) : 'div';			

			if ( ! empty( $title ) ) {
				$gradient_class = ! empty( $settings['show_gradient_title'] ) ? ' eel-gradient-title' : '';
				$gradient_class = ! empty( $settings['show_stroke_title'] ) ? ' eel-stroke-title' : '';
				$gradient_class = '';

				if ( ! empty( $settings['show_gradient_title'] ) ) {
					$gradient_class .= ' eel-gradient-title';
				}

				if ( ! empty( $settings['show_stroke_title'] ) ) {
					$gradient_class .= ' eel-stroke-title';
				}
				$tag_class = 'e-e-title' . $gradient_class;
				printf(
					'<%1$s class="%2$s" %3$s>%4$s</%1$s>',
					esc_html( $tag ),
					esc_attr( $tag_class ),
					esc_attr( $this->get_render_attribute_string( 'title' ) ),
					wp_kses_post( $title )
				);
			}

			?>
	    </div>
	<?php }
}
?>