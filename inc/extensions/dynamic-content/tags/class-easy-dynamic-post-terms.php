<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Post_Terms extends Tag {

    public function get_name() {
        return 'easy-post-terms-dynamic';
    }

    public function get_title() {
        return __( 'Post Terms ', 'easy-elements-pro' );
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
     * Helper: Get taxonomies for dropdown
     */
    private function get_taxonomies_for_dropdown() {
        $taxonomies = get_taxonomies( ['public' => true,  'show_ui'  => true ], 'objects' );
        $options = [];

        foreach ( $taxonomies as $taxonomy ) {
            $options[ $taxonomy->name ] = $taxonomy->label;
        }

        return $options;
    }

    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        easy_general_settings_post( $this );

        $this->add_control(
            'easy_taxonomy',
            [
                'label' => __( 'Taxonomy', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => $this->get_taxonomies_for_dropdown(),
            ]
        );

        // --- Show as link ---
        $this->add_control(
            'easy_show_link',
            [
                'label' => __( 'Show as Link', 'easy-elements-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'easy-elements-pro' ),
                'label_off' => __( 'No', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'easy_separator',
            [
                'label' => __( 'Separator', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => ', ',
                'description' => __( 'Separator for multiple terms', 'easy-elements-pro' ),
                'label_block' => true,
                'ai'    => false,
            ]
        );

        $this->add_control(
            'easy_terms_limit',
            [
                'label' => __( 'Limit', 'easy-elements-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Maximum number of terms to display. Leave empty for all.', 'easy-elements-pro' ),
                'min' => 1,
                'step' => 1,
                'default' => '',
            ]
        );

        // Offset terms
        $this->add_control(
            'easy_terms_offset',
            [
                'label' => __( 'Offset', 'easy-elements-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Skip first N terms.', 'easy-elements-pro' ),
                'min' => 0,
                'step' => 1,
                'default' => 0,
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        $post_id = Easy_Dynamic_Tag_Helper::get_post_id($settings);
        if (!$post_id) {
            return;
        }

        $taxonomy = !empty($settings['easy_taxonomy']) ? $settings['easy_taxonomy'] : 'category';

        // Get terms
        $terms = get_the_terms( $post_id, $taxonomy );
        if (empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }

        $offset = !empty($settings['easy_terms_offset']) ? absint($settings['easy_terms_offset']) : 0;
        $limit  = !empty($settings['easy_terms_limit']) ? absint($settings['easy_terms_limit']) : 0;

        if ($offset > 0) {
            $terms = array_slice($terms, $offset);
        }

        if ($limit > 0) {
            $terms = array_slice($terms, 0, $limit);
        }

        // Separator
        $separator = isset($settings['easy_separator']) ? $settings['easy_separator'] : ', ';

        // Show as link?
        $show_link = isset($settings['easy_show_link']) && $settings['easy_show_link'] === 'yes';

        $output = [];

        foreach ($terms as $term) {
            $name = esc_html($term->name);
            if ($show_link) {
                $link = get_term_link($term);
                if (!is_wp_error($link)) {
                    $name = sprintf('<a href="%s">%s</a>', esc_url($link), $name);
                }
            }
            $output[] = $name;
        }

        $before = !empty($settings['easydc_before']) ? $settings['easydc_before'] : '';
        $after  = !empty($settings['easydc_after']) ? $settings['easydc_after'] : '';

        echo wp_kses_post($before . implode($separator, $output) . $after);
    }

}