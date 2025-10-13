<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Page_Title extends Tag {

    public function get_name() {
        return 'easy-site-page-title-dynamic';
    }

    public function get_title() {
        return __( 'Page Title', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-sitetitle-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    public function is_settings_required() {
        return true;
    }

    protected function register_controls() {
        $this->add_control(
            'easy_context_include',
            [
                'label'        => __( 'Include Context', 'easy-elements-pro' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements-pro' ),
                'label_off'    => __( 'No', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'easy_show_home_title',
            [
                'label'        => __( 'Show Home Title', 'easy-elements-pro' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements-pro' ),
                'label_off'    => __( 'No', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render(): void {
        $settings = $this->get_settings_for_display();
        $title = $this->get_title_for_display($settings);

        $before = ! empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = ! empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        $title = easy_apply_word_limit( $title, $settings );

        echo wp_kses_post($before . $title . $after);
    }


    /**
     * Elementor editor preview
     */
    public function get_value_for_editor() {
        $settings = $this->get_settings_for_display();
        return $this->get_title_for_display($settings);
    }

    protected function get_title_for_display($settings) {
        $include_context = ! empty( $settings['easy_context_include'] ) && $settings['easy_context_include'] === 'yes';
        $show_home_title = ! empty( $settings['easy_show_home_title'] ) && $settings['easy_show_home_title'] === 'yes';

        $queried_object = get_post() ?: null;

        $title = '';

        if ( $queried_object && isset( $queried_object->ID ) ) {
            $post_title = get_the_title($queried_object->ID);
            $prefix = '';

            if ( $include_context ) {
                if ( $queried_object->post_type === 'page' && ! is_front_page() ) {
                    $prefix = 'Page: ';
                } elseif ( $queried_object->post_type === 'post' ) {
                    $prefix = 'Post: ';
                } else {
                    $prefix = 'Item: ';
                }
            }

            $title = $prefix . $post_title;
        } elseif ( is_home() || is_front_page() ) {
            $title = $show_home_title ? get_bloginfo('name') : '';
            if ( $include_context && $title ) {
                $title = 'Home: ' . $title;
            }
        } else {
            $title = get_the_title() ?: ($settings['easydc_fallback'] ?? '');
        }

        return $title;
    }
}
