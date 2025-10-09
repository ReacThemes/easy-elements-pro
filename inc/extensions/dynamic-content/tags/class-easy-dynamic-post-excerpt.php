<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_Excerpt extends Tag {

    public function get_name() {
        return 'easy-post-excerpt-dynamic';
    }

    public function get_title() {
        return __( 'Post Excerpt', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-post-tags';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    public function is_settings_required() {
		return true;
	}

    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        easy_general_settings_post( $this );

        // Word limit
        $this->add_control(
            'easy_excerpt_len',
            [
                'label' => esc_html__('Excerpt Length', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 1,
                'max' => 1200,
            ]
        );

        // New controls
        $this->add_control(
            'easy_excerpt_source',
            [
                'label' => __('Excerpt Source', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'excerpt' => 'Post Excerpt',
                    'content' => 'Post Content',
                ],
                'default' => 'excerpt',
            ]
        );

        $this->add_control(
            'easy_preserve_linebreaks',
            [
                'label' => __('Preserve Line Breaks', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Yes',
                'label_off' => 'No',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'easy_custom_class',
            [
                'label' => __('Custom CSS Class', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => 'custom-css-class',
            ]
        );

        $this->add_control(
            'easy_read_more_text',
            [
                'label' => __('Read More', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '…',
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    /**
     * Render output
     */
    public function render() {
        $settings = $this->get_settings_for_display();


        if ( isset($settings['easy_selected_post_type']) && $settings['easy_selected_post_type'] === 'selected' && !empty($settings['dynamic_post']) ) {
            $post_id = is_array($settings['dynamic_post']) ? $settings['dynamic_post'][0] : $settings['dynamic_post'];
        } else {
            $post_id = get_the_ID();
        }

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $post_id = $post_id ?: get_post()->ID;
        }

        if ($settings['easy_excerpt_source'] === 'content') {
            $excerpt = get_post_field('post_content', $post_id);
        } else {
            $excerpt = get_post($post_id) ? get_the_excerpt($post_id) : '';
        }

        if (empty($excerpt)) {
            $excerpt = !empty($settings['easydc_fallback']) ? $settings['easydc_fallback'] : '';
        }

        if (empty($settings['easy_preserve_linebreaks']) || $settings['easy_preserve_linebreaks'] === 'no') {
            $excerpt = wp_strip_all_tags($excerpt);
        }

        if (!empty($settings['easy_excerpt_len']) && $settings['easy_excerpt_len'] > 0) {
            $words = preg_split('/\s+/', $excerpt);
            if (count($words) > $settings['easy_excerpt_len']) {
                $excerpt = implode(' ', array_slice($words, 0, $settings['easy_excerpt_len'])) . (!empty($settings['easy_read_more_text']) ? $settings['easy_read_more_text'] : '…');
            }
        }

        $before = !empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = !empty($settings['easydc_after']) ? $settings['easydc_after'] : '';
        $class  = !empty($settings['easy_custom_class']) ? ' class="' . esc_attr($settings['easy_custom_class']) . '"' : '';

        echo '<span' . $class . '>' . wp_kses_post($before . $excerpt . $after) . '</span>';
    }

    public function get_value_for_editor() {
        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();
        if (!empty($settings['dynamic_post'])) {
            $post_id = is_array($settings['dynamic_post']) ? $settings['dynamic_post'][0] : $settings['dynamic_post'];
        }

        $excerpt = get_post($post_id) ? get_the_excerpt($post_id) : '';
        if (empty($excerpt)) {
            $excerpt = !empty($settings['easydc_fallback']) ? $settings['easydc_fallback'] : '';
        }

        return $excerpt;
    }

}
