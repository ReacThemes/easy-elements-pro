<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

add_action( 'elementor/element/eel-heading/content_section/before_section_end', function( $element, $args ) {

    if ( ! class_exists( 'Easy_Elements_Pro' ) ) {
        return;
    }

    $element->add_control(
        'animation_type',
        [
            'label'   => esc_html__( 'Animation Type', 'easy-elements-pro' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default'     => 'Default',
                'split-text'     => 'Split Text (Letter)',
                'split-words'    => 'Split Words',
                'split-lines'    => 'Split Line Bottom',
                'split-lines-left'  => 'Split Line Left',
                'split-lines-up'  => 'Split Line Up',
                'typewriter'     => 'Typewriter',
                'text-bounce'    => 'Text Bounce',                
            ],
        ]
    );

    $element->add_control(
        'eel_parallax_title',
        [
            'label'        => __('Enable Parallax', 'easy-elements-pro'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'easy-elements-pro'),
            'label_off'    => __('No'),
            'return_value' => 'yes',
            'default'      => '',
        ]
    );

    // Direction: Left → Right / Right → Left
    $element->add_control(
        'eel_parallax_direction',
        [
            'label'     => __('Direction', 'easy-elements-pro'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => [
                'left'  => __('Right to Left', 'easy-elements-pro'),
                'right' => __('Left to Right', 'easy-elements-pro'),
            ],
            'default'   => 'left',
            'condition' => [
                'eel_parallax_title' => 'yes',
            ],
        ]
    );

    // Movement percent
    $element->add_control(
        'eel_parallax_percent',
        [
            'label'     => __('Movement Percent', 'easy-elements-pro'),
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range'     => [
                '%' => [
                    'min' => 0,
                    'max' => 200,
                    'step'=> 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 36,
            ],
            'condition' => [
                'eel_parallax_title' => 'yes',
            ],
        ]
    );
    

}, 10, 2 );

// Section Sticky Option
add_action('elementor/element/container/section_layout/after_section_end', function($element, $args) {

    if ( ! class_exists( 'Easy_Elements_Pro' ) ) {
        return;
    }

    // Sticky Section
    $element->start_controls_section(
        'eel_sticky_section',
        [
            'label' => __('Sticky Options', 'easy-elements-pro'),
            'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
    );

    // Sticky Enable
    $element->add_control(
        'eel_sticky',
        [
            'label'        => __('Enable Sticky', 'easy-elements-pro'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'easy-elements-pro'),
            'label_off'    => __('No'),
            'return_value' => 'yes',
            'default'      => '',
        ]
    );

    // Sticky Top Position
    $element->add_control(
        'eel_sticky_top',
        [
            'label' => __('Sticky Top Position', 'easy-elements-pro'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 150,
            ],
            'condition' => [
                'eel_sticky' => 'yes',
            ],
        ]
    );

    $element->end_controls_section();

}, 10, 2);


// Section Parallax Option
add_action('elementor/element/container/section_layout/after_section_end', function($element, $args) {

    if ( ! class_exists( 'Easy_Elements_Pro' ) ) {
        return;
    }

    // Parallax Section
    $element->start_controls_section(
        'eel_parallax_section',
        [
            'label' => __('Parallax Options', 'easy-elements-pro'),
            'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
    );

    // Parallax Enable
    $element->add_control(
        'eel_parallax',
        [
            'label'        => __('Enable Parallax', 'easy-elements-pro'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'easy-elements-pro'),
            'label_off'    => __('No'),
            'return_value' => 'yes',
            'default'      => '',
        ]
    );

    // Parallax Background Size
    $element->add_control(
        'eel_parallax_size',
        [
            'label' => __('Background Size', 'easy-elements-pro'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 100,
                    'max' => 300,
                ],
                '%' => [
                    'min' => 100,
                    'max' => 300,
                ],
            ],
            'size_units' => ['%', 'px'],
            'default' => [
                'unit' => '%',
                'size' => 160,
            ],
            'condition' => [
                'eel_parallax' => 'yes',
            ],
        ]
    );

    $element->end_controls_section();

}, 10, 2);


// Image Scrolling Option
add_action('elementor/element/image/section_image/before_section_end', function($element, $args) {
    if ( ! class_exists( 'Easy_Elements_Pro' ) ) {
        return;
    }
    $element->add_control(
        'enable_img_scrolling',
        [
            'label' => esc_html__('Enable Image Scrolling', 'easy-elements'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'easy-elements'),
            'label_off' => esc_html__('No', 'easy-elements'),
            'return_value' => 'yes',
            'default' => 'no',
        ]
    );
    $element->add_responsive_control(
        'scrolling_vertical_posi',
        [
            'label' => esc_html__('Scrolling Vertical Position', 'easy-elements'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '%', 'px' ],
            'range'      => [
                'px' => [ 'min' => -1000, 'max' => 1000 ],
                '%'  => [ 'min' => -100, 'max' => 100 ],
            ],
            'default'    => [
                'unit' => '%',
                'size' => 20,
            ],
            'condition'  => [
                'enable_img_scrolling' => 'yes',
            ],
        ]
    );

    $element->add_control(
        'enable_flip_scrolling',
        [
            'label' => esc_html__('Enable Flip Scrolling', 'easy-elements'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'easy-elements'),
            'label_off' => esc_html__('No', 'easy-elements'),
            'return_value' => 'yes',
            'default' => 'no',
        ]
    );

    $element->add_control(
        'flip_rotate_x',
        [
            'label' => __('Flip Rotate X (deg)', 'easy-elements'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'deg' => ['min'=>0,'max'=>90],
            ],
            'default' => ['unit'=>'deg','size'=>30],
            'condition' => ['enable_flip_scrolling'=>'yes'],
        ]
    );

},10, 2);


add_action('elementor/element/container/section_layout/after_section_end', function($element, $args) {
    $element->start_controls_section(
        'eel_scroll_zoom_section',
        [
            'label' => __('Scroll Zoom Effect', 'easy-elements-pro'),
            'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
    );

    $element->add_control(
        'eel_scroll_zoom_type',
        [
            'label'   => __('Zoom Type', 'easy-elements-pro'),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''         => __('None', 'easy-elements-pro'),
                'in'       => __('Zoom In (Big → Small)', 'easy-elements-pro'),
                'out'      => __('Zoom Out (Small → Big)', 'easy-elements-pro'),
            ],
            'default' => '',
        ]
    );

    $element->add_control(
        'eel_scroll_zoom_width',
        [
            'label'       => __('Target Width (%)', 'easy-elements-pro'),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'default'     => 90,
            'min'         => 50,
            'max'         => 100,
            'step'        => 1,
            'condition'   => [
                'eel_scroll_zoom_type!' => '',
            ],
        ]
    );

    $element->add_control(
        'eel_scroll_zoom_start',
        [
            'label'       => __('Scroll Start (e.g. top 80%)', 'easy-elements-pro'),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'top 80%',
            'condition'   => [
                'eel_scroll_zoom_type!' => '',
            ],
        ]
    );

    $element->add_control(
        'eel_scroll_zoom_end',
        [
            'label'       => __('Scroll End (e.g. top 30%)', 'easy-elements-pro'),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'top 30%',
            'condition'   => [
                'eel_scroll_zoom_type!' => '',
            ],
        ]
    );

    $element->end_controls_section();
}, 10, 2);



// Rendering 
add_action('elementor/frontend/before_render', function( $element ) {
    $settings = $element->get_settings_for_display();
    if ( 'container' === $element->get_name() ) {
        if ( ! empty( $settings['eel_sticky'] ) && $settings['eel_sticky'] === 'yes' ) {
            
            $top = $element->get_settings('eel_sticky_top')['size'] ?? 150;
            $unit = $element->get_settings('eel_sticky_top')['unit'] ?? 'px';
            $element->add_render_attribute('_wrapper', 'class', 'eel-sticky-section');
            $element->add_render_attribute('_wrapper', 'style', 'position: sticky; top: ' . $top . $unit . ';');
        }

        if ( ! empty( $settings['eel_parallax'] ) && $settings['eel_parallax'] === 'yes' ) {
            $size = ! empty( $settings['eel_parallax_size']['size'] ) ? $settings['eel_parallax_size']['size'] . '%' : '160%';
            $element->add_render_attribute( '_wrapper', 'class', 'eel-parallax-bg' );
            $element->add_render_attribute( '_wrapper', 'data-parallax-size', esc_attr( $size ) );
        }
    }

    if ( 'eel-heading' === $element->get_name() ) {
        if ( ! empty( $settings['eel_parallax_title'] ) && $settings['eel_parallax_title'] === 'yes' ) {
            $element->add_render_attribute( '_wrapper', 'class', 'eel-hr-scroll-text' );
            $element->add_render_attribute( '_wrapper', 'data-parallax-direction', $settings['eel_parallax_direction'] );
            $element->add_render_attribute( '_wrapper', 'data-parallax-percent', $settings['eel_parallax_percent']['size'] );
        }
    }

    // Image Scrolling Option Rendering
    if ( 'image' === $element->get_name() ) {
        $settings = $element->get_settings_for_display();
        if ( ! empty( $settings['enable_img_scrolling'] ) && $settings['enable_img_scrolling'] === 'yes' ) {
            $scroll_val = ! empty( $settings['scrolling_vertical_posi']['size'] )
                ? $settings['scrolling_vertical_posi']['size'] . $settings['scrolling_vertical_posi']['unit']
                : '20%';
                
            $element->add_render_attribute( '_wrapper', 'class', 'eel-vertical-scroll-img');
            $element->add_render_attribute( '_wrapper', 'data-scroll-y', $scroll_val );
        }

        if ( ! empty($settings['enable_flip_scrolling']) && $settings['enable_flip_scrolling'] === 'yes' ) {
            $rotateX = ! empty($settings['flip_rotate_x']['size']) ? $settings['flip_rotate_x']['size'] : 30;
            $element->add_render_attribute('_wrapper', 'class', 'eel-flip-img-scroll', true);
            $element->add_render_attribute('_wrapper', 'data-flip-rotate-x', esc_attr($rotateX), true);
        }
    }

    if ( ! empty( $settings['eel_scroll_zoom_type'] ) ) {
        $zoom_type  = $settings['eel_scroll_zoom_type'];
        $zoom_width = ! empty( $settings['eel_scroll_zoom_width'] ) ? intval( $settings['eel_scroll_zoom_width'] ) : 90;
        $zoom_start = ! empty( $settings['eel_scroll_zoom_start'] ) ? $settings['eel_scroll_zoom_start'] : 'top 80%';
        $zoom_end   = ! empty( $settings['eel_scroll_zoom_end'] ) ? $settings['eel_scroll_zoom_end'] : 'top 30%';

        if ( $zoom_type === 'in' ) {
            $element->add_render_attribute( '_wrapper', 'class', 'eel-hr-scroll-container-small' );
        } elseif ( $zoom_type === 'out' ) {
            $element->add_render_attribute( '_wrapper', 'class', 'eel-hr-scroll-container' );
        }

        $element->add_render_attribute( '_wrapper', [
            'data-zoom-type'  => esc_attr( $zoom_type ),
            'data-zoom-width' => esc_attr( $zoom_width ),
            'data-zoom-start' => esc_attr( $zoom_start ),
            'data-zoom-end'   => esc_attr( $zoom_end ),
        ] );
    }
}, 10);


// Mega Menu Code Here
function easyel__custom_menu_fields($menu_id) {
    if ( ! class_exists('Elementor\Plugin') ) {
        return;
    }
    $menu_item_elementor_template = get_post_meta($menu_id, 'easyel__menu_item_elementor_template', true);
    $menu_item_elementor_template_link = get_post_meta($menu_id, 'easyel__menu_item_elementor_template_link', true);
    ?>
    <p class="field-elementor-template description description-wide">
        <label for="edit-easyel--menu-item-elementor-template-<?php echo esc_attr($menu_id); ?>">
            <?php esc_html_e('Mega Menu Template', 'easy-elements-pro'); ?><br />
            <select id="edit-easyel--menu-item-elementor-template-<?php echo esc_attr($menu_id); ?>" class="widefat code edit-menu-item-custom" name="easyel--menu-item-elementor-template[<?php echo esc_attr($menu_id); ?>]">
                <option value="" <?php selected('', $menu_item_elementor_template); ?>><?php esc_html_e('Select Menu Template', 'easy-elements-pro'); ?></option>
                <?php
                // Retrieve Elementor templates and add them to the dropdown
                $elementor_templates = get_posts(array('post_type' => 'elementor_library', 'posts_per_page' => -1));

                foreach ($elementor_templates as $template) {
                    echo '<option value="' . esc_attr($template->ID) . '" ' . selected($menu_item_elementor_template, $template->ID, false) . '>' . esc_html($template->post_title) . '</option>';
                }
                ?>
            </select>
        </label>
    </p>
    <?php
    if ($menu_item_elementor_template) {
        $edit_template_link = \Elementor\Plugin::$instance->documents->get($menu_item_elementor_template)->get_edit_url();
        ?>
        <p class="field-elementor-edit-link description description-wide">
            <label for="edit-easyel--menu-item-elementor-edit-link-<?php echo esc_attr($menu_id); ?>">
                <a href="<?php echo esc_url($edit_template_link); ?>" target="_blank"><?php esc_html_e('Please Mega Menu Content Here', 'easy-elements-pro'); ?></a>
            </label>
        </p>
        <?php
    }
}
add_action('wp_nav_menu_item_custom_fields', 'easyel__custom_menu_fields');

function save_custom_menu_fields($menu_id, $menu_item_db_id) {
    if (isset($_REQUEST['easyel--menu-item-elementor-template'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, 'easyel__menu_item_elementor_template', sanitize_text_field($_REQUEST['easyel--menu-item-elementor-template'][$menu_item_db_id]));
    }

    if (isset($_REQUEST['easyel--menu-item-elementor-template-link'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, 'easyel__menu_item_elementor_template_link', esc_url($_REQUEST['easyel--menu-item-elementor-template-link'][$menu_item_db_id]));
    }
}
add_action('wp_update_nav_menu_item', 'save_custom_menu_fields', 10, 3);

function display_elementor_template($item_output, $item, $depth, $args) {
    $elementor_template_id = get_post_meta($item->ID, 'easyel__menu_item_elementor_template', true);
    if (! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        if (!empty($elementor_template_id) && class_exists('Elementor\Plugin')) {
            // Debugging: Output Elementor template ID for each menu item
            error_log('Elementor Template ID for Menu Item ' . $item->ID . ': ' . $elementor_template_id);

            // Output the Elementor template content based on the menu item's template ID
            $template_content = Elementor\Plugin::instance()->frontend->get_builder_content($elementor_template_id);
            if ($template_content) {
                // Append the template content after the menu item
                $item_output .= '<ul class="easyel--elementor-template-mega-menu easyel-mega--current sub-menu">' . $template_content . '</ul>';
            } else {
                // Debugging: Output an error message if template content retrieval fails
                $item_output .= '<ul class="elementor-template-content-error">Error retrieving Elementor template content.</ul>';
            }
        }
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'display_elementor_template', 10, 4);


// Jaralax Hook Herer

use Elementor\Controls_Manager;
use Elementor\Element_Base;

class Easyel_custom_class {

    public static function init() {
        // inject_section_class
       add_action('elementor/element/image/section_style_image/after_section_end', [__CLASS__, 'Easyel_inject_section_class'], 1, 2);     
    }       

    public static function Easyel_inject_section_class( $element, $args ) {
        $element->start_controls_section(
            'rt_jarallax_section',
            [
                'label' => esc_html__( 'Enable Jarallax', 'easy-elements-pro' ),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'enable_jarallax',
            [
                'label' => esc_html__( 'Enable Jarallax', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements-pro' ),
                'label_off' => esc_html__( 'No', 'easy-elements-pro' ),
                'return_value' => 'has-jarallax-img',
                'prefix_class' => '',
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .jarallax' => 'width: {{SIZE}}{{UNIT}} !important;',                                 
                    '{{WRAPPER}} .elementor-widget-container img' => 'width: {{SIZE}}{{UNIT}};',                                 
                ],                  
            ]
        ); 

        $element->add_responsive_control(
            'jarallax_image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .jarallax' => 'height: {{SIZE}}{{UNIT}} !important;',                                   
                    '{{WRAPPER}} .elementor-widget-container img' => 'height: {{SIZE}}{{UNIT}};',                                   
                ],                  
            ]
        ); 

        $element->add_control(
            'object_fit_cover',
            [
                'label'   => esc_html__('Object Fit', 'easy-elements-pro'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'rtimg-contain',
                'options' => [
                    'rtimg-contain' => esc_html__('Contain', 'easy-elements-pro'),                    
                    'rtimg-cover' => esc_html__('Cover', 'easy-elements-pro'),                   
                ],
                'prefix_class' => '',
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .jarallax-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .jarallax-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; !important;',
                ],                  
            ]
        ); 

        $element->end_controls_section();
    }
    }

Easyel_custom_class::init();