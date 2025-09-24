<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Image_Accordion_Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-image-accordion';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/image-accordion.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/image-accordion.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_script_depends() {
        $handle = 'eel-image-accordion-script';
        $js_path = plugin_dir_path( __FILE__ ) . 'js/image-accordion.js';
        
        // Check if minification is enabled and helper class exists
        if ( get_option( 'easyel_elements_minify_js', '0' ) === '1' && class_exists( 'Easyel_Elements_JS_Loader_Helper' ) ) {
            try {
                Easyel_Elements_JS_Loader_Helper::easyel_elements_load_minified_inline_js( $handle, $js_path );
                return [ $handle ];
            } catch ( Exception $e ) {
                // Fallback to normal loading if minification fails
                error_log( 'Easy Elements: JS minification failed for ' . $handle . ': ' . $e->getMessage() );
            }
        }

        // Normal script loading
        if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
            wp_register_script( $handle, plugins_url( 'js/image-accordion.js', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-image-accordion';
    }

    public function get_title() {
        return esc_html__( 'Easy Image Accordion', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'image', 'accordion' ];
    }

    protected function register_controls() {
      $this->start_controls_section(
          'accordion_images',
          [
               'label' => esc_html__( 'Images', 'easy-elements' ),
               'tab' => Controls_Manager::TAB_CONTENT
          ]
      );

      $repeater = new Repeater();

      $repeater->add_control(
        'is_active',
        [
            'label' => esc_html__( 'Active', 'easy-elements' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Yes', 'easy-elements' ),
            'label_off' => esc_html__( 'No', 'easy-elements' ),
            'return_value' => 'yes',
            'default' => '',
        ]
      );
      $repeater->add_control(
          'image',
          [
              'label' => esc_html__( 'Image', 'easy-elements' ),
              'type' => Controls_Manager::MEDIA,
              'default' => [
                  'url' => Utils::get_placeholder_image_src(),
              ],
          ]
      );
      $repeater->add_responsive_control(
          'image_width',
          [
               'label' => esc_html__( 'Default Width (%)', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::NUMBER,
               'min' => 5,
               'max' => 100,
               'default' => 25,
          ]
     );

     $repeater->add_responsive_control(
          'image_border_radius',
          [
               'label' => esc_html__( 'Border Radius', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', '%', 'em' ],
               'selectors' => [
                   '{{WRAPPER}} .easyel-image-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],

          ]
     );

     $repeater->add_control(
          'title_on_off',[
              'label' => esc_html__( 'Show Content?', 'easy-elements' ),
              'type' => \Elementor\Controls_Manager::SWITCHER,
              'label_on' => esc_html__( 'Show', 'easy-elements' ),
              'label_off' => esc_html__( 'Hide', 'easy-elements' ),
              'return_value' => 'yes',
              'default' => '',
          ]
     );

     $repeater->add_control(
          'image_title',
          [
              'label' => esc_html__( 'Title', 'easy-elements' ),
              'type' => Controls_Manager::TEXT,
              'default' => esc_html__( 'Image', 'easy-elements' ),
              'label_block' => true,
              'condition' => [
                  'title_on_off' => 'yes'
              ],
          ]
     );

     $repeater->add_control(
          'description',
          [
              'label' => esc_html__( 'Description', 'easy-elements' ),
              'type' => Controls_Manager::TEXTAREA,
              'default' => esc_html__( 'Description', 'easy-elements' ),
              'label_block' => true,
              'condition' => [
                  'title_on_off' => 'yes'
              ],
          ]
     );

     $repeater->add_control(
        'image_link',
        [
            'label' => esc_html__( 'Link', 'easy-elements' ),
            'type' => Controls_Manager::URL,
            'label_block' => true,
            'condition' => [
                'title_on_off' => 'yes'
            ],
        ]
     );

     $this->add_control(
          'images',
          [
              'label' => esc_html__( 'Images', 'easy-elements' ),
              'type' => Controls_Manager::REPEATER,
              'fields' => $repeater->get_controls(),
              'default' => [
                  [
                      'image' => [
                          'url' => Utils::get_placeholder_image_src(),
                      ],
                  ],
              ],
              'title_field' => '{{{ image_title }}}',
          ]
     );


     $this->end_controls_section();

     $this->start_controls_section(
          'accordion_settings',
          [
               'label' => esc_html__( 'Settings', 'easy-elements' ),
               'tab' => Controls_Manager::TAB_STYLE,
          ]
     );

     $this->add_responsive_control(
       'item_gap',
       [
          'label' => esc_html__( 'Gap Between Items (px)', 'easy-elements' ),
          'type' => Controls_Manager::SLIDER,
          'size_units' => [ 'px' ],
          'range' => [
              'px' => [
                  'min' => 0,
                  'max' => 50,
              ],
          ],
          'default' => [
              'unit' => 'px',
              'size' => 10,
          ],
          'selectors' => [
              '{{WRAPPER}} .easyel-image-accordion' => 'gap: {{SIZE}}{{UNIT}};',
          ],
       ]
    );

     $this->add_responsive_control(
          'image_height',
          [
               'label' => esc_html__( 'Image Height (px)', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::SLIDER,
               'size_units' => [ 'px' ],
               'range' => [
                   'px' => [
                       'min' => 0,
                       'max' => 1000,
                   ],
               ],
               'default' => [
                   'unit' => 'px',
                   'size' => 300,
               ],
               'selectors' => [
                   '{{WRAPPER}} .easyel-image-item img' => 'height: {{SIZE}}{{UNIT}};',
               ],
          ]
     );

     $this->add_control(
          'meta_bg',[
               'label' => esc_html__( 'Image Meta Background', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .easyel_image_meta' => 'background: {{VALUE}};',
               ],
          ]
     );

     $this->add_group_control(
          \Elementor\Group_Control_Border::get_type(),
          [
               'name' => 'image_border',
               'label' => esc_html__( 'Image Border', 'easy-elements' ),
               'selector' => '{{WRAPPER}} .easyel-image-item .easyel_image_meta',
          ]
     );

     $this->add_control(
          'image_border_radius',[
               'label' => esc_html__( 'Image Border Radius', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', '%', 'em' ],
               'selectors' => [
                   '{{WRAPPER}} .easyel-image-item .easyel_image_meta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
          ]
     );

     $this->add_control(
          'title_options',
          [
               'label' => esc_html__( 'Title Options', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::HEADING,
               'separator' => 'before',
          ]
     );

     $this->add_control(
          'title_color',
          [
               'label' => esc_html__( 'Color', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .easyel-image-title' => 'color: {{VALUE}};',
               ],
          ]
     );
     $this->add_group_control(
          Group_Control_Typography::get_type(),
          [
               'name' => 'title_typography',
               'label' => esc_html__( 'Typography', 'easy-elements' ),
               'selector' => '{{WRAPPER}} .easyel-image-title',
          ]
     );

     $this->add_responsive_control(
          'title_padding',
          [
               'label' => esc_html__( 'Padding', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', '%', 'em' ],
               'selectors' => [
                   '{{WRAPPER}} .easyel-image-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
          ]
     );

     $this->add_control(
          'description_options',
          [
               'label' => esc_html__( 'Description Options', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::HEADING,
               'separator' => 'before',
          ]
     );
     $this->add_control(
          'desc_color',
          [
               'label' => esc_html__( 'Color', 'easy-elements' ),
               'type' => \Elementor\Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .easyel-image-description' => 'color: {{VALUE}};',
               ],
          ]
     );
     $this->add_group_control(
          Group_Control_Typography::get_type(),
          [
               'name' => 'desc_typography',
               'label' => esc_html__( 'Typography', 'easy-elements' ),
               'selector' => '{{WRAPPER}} .easyel-image-description',
          ]
     );
     $this->end_controls_section();
     
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['images'] ) ) {
            return;
        }

        ?>
        <div class="easyel-image-accordion" data-count="<?php echo count($settings['images']); ?>">
            <?php foreach ( $settings['images'] as $index => $item ) : 
                $title       = ! empty( $item['image_title'] ) ? $item['image_title'] : '';
                $description = ! empty( $item['description'] ) ? $item['description'] : '';
                $link        = ! empty( $item['image_link']['url'] ) ? $item['image_link']['url'] : '';
                $image_url   = ! empty( $item['image']['url'] ) ? $item['image']['url'] : '';
                $is_active   = ! empty( $item['is_active'] ) && $item['is_active'] === 'yes';                
                $active_class = $is_active ? ' active' : '';
            ?>
            <?php if(!empty($link )): ?>
            <a href="<?php echo esc_url( $link ); ?>">
            <?php endif; ?>
                <div class="easyel-image-item<?php echo esc_attr( $active_class ); ?>">
                    <?php if ( $image_url ) : ?>
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                    <?php endif; ?>
                <?php if ( ! empty( $item['title_on_off'] ) && $item['title_on_off'] === 'yes' ) : ?>
                    <div class="easyel_image_meta">
                        <?php if ( $title ) : ?>                            
                            <div class="easyel-image-title"><?php echo esc_html( $title ); ?></div>                            
                        <?php endif; ?>
                        <?php if ( $description ) : ?>
                            <div class="easyel-image-description"><?php echo esc_html( $description ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                </div>
            <?php if(!empty($link )): ?>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }

} ?>