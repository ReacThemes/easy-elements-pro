<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Hr_Image_Scroll_Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-hr-image-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/hr-image.css';

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/hr-image.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    public function get_name() {
        return 'eel-hr-image-scroll';
    }

    public function get_title() {
        return esc_html__( 'Image Horizontal ScrollTrigger', 'easy-elements-pro' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_pro' ];
    }

    public function get_keywords() {
        return [ 'image', 'scroll' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_images',
            [
               'label' => esc_html__( 'Image Settings', 'easy-elements-pro' ),
               'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Logo', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'easy_image',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ image.url }}}',
                'default' => array_fill(0, 4, [
                    'image' => ['url' => Utils::get_placeholder_image_src()],
                ]),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', 
                'default' => 'full',
            ]
        );

        $this->add_control(
            'image_size_width',
            [
                'label' => esc_html__('Image Size', 'easy-elements'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1400 ],
                    'em' => [ 'min' => 0, 'max' => 30 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-per-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'fetchpriority',
            [
                'label' => __('Image Fetch Priority', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''      => __('Default', 'easy-elements'),
                    'high'  => __('High', 'easy-elements'),
                    'low'   => __('Low', 'easy-elements'),
                ],
                'default' => 'low',
            ]
        );

        $this->add_control(
            'image_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .per-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .per-item, {{WRAPPER}} .eel-image-scroller' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="eel-image-scroller">
            <section class="eel-scroll-image">
                <?php foreach ( $settings['easy_image'] as $item ) :
                    $image_id = $item['image']['id'] ?? '';
                    $image_size = $settings['image_size'] ?? 'full';
                    $image_data = $image_id ? wp_get_attachment_image_src($image_id, $image_size) : '';
                    $alt   = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                    $title = $image_id ? get_the_title($image_id) : '';
                    $link  = $item['link']['url'] ?? '';
                    $target   = ! empty( $item['link']['is_external'] ) ? '_blank' : '';
                    $nofollow = ! empty( $item['link']['nofollow'] ) ? 'nofollow' : '';
                    $fetchpriority = $settings['fetchpriority'] ?? '';
                ?>
                    <?php if ( $image_data ) : ?>
                        <div class="per-item">
                            <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url($link); ?>"
                                target="<?php echo esc_attr($target); ?>"
                                rel="<?php echo esc_attr($nofollow); ?>">
                            <?php endif; ?>

                            <img class="eel-per-img"
                                src="<?php echo esc_url($image_data[0]); ?>"
                                width="<?php echo esc_attr($image_data[1]); ?>"
                                height="<?php echo esc_attr($image_data[2]); ?>"
                                alt="<?php echo esc_attr($alt); ?>"
                                title="<?php echo esc_attr($title); ?>"
                                loading="lazy"
                                decoding="async"
                                fetchpriority="<?php echo esc_attr($fetchpriority); ?>">

                            <?php if ( $link ) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </section>
        </div>
        <?php
    }

} 