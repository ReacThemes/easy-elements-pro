<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Comments extends Tag {

    public function get_name() {
        return 'easy-comments-number-dynamic';
    }

    public function get_title() {
        return __( 'Comments Number ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-comments-tag';
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

        $this->add_control(
            'easy_no_comments_text',
            [
                'label'       => __( 'No Comments Text', 'easy-elements-pro' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'No Comments', 'easy-elements-pro' ),
                'placeholder' => __( 'Enter text for no comments', 'easy-elements-pro' ),
                'label_block' => true,
                'ai'    => false,
            ]
        );

        $this->add_control(
            'easy_single_comment_text',
            [
                'label'       => __( 'Single Comment Text', 'easy-elements-pro' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( '1 Comment', 'easy-elements-pro' ),
                'placeholder' => __( 'Enter text for single comment', 'easy-elements-pro' ),
                'label_block' => true,
                'ai'    => false,
            ]
        );

        $this->add_control(
            'easy_multiple_comments_text',
            [
                'label'       => __( 'Multiple Comments Text', 'easy-elements-pro' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( '{number} Comments', 'easy-elements-pro' ),
                'placeholder' => __( 'Use {number} placeholder  for multiple comments text', 'easy-elements-pro' ),
                'label_block' => true,
                'ai'    => false,
            ]
        );

        $this->add_control(
            'easy_comments_link_enable',
            [
                'label'        => __( 'Enable Comments Link', 'easy-elements-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
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

    /**
     * Render output
     */
    public function render() {

        $settings = $this->get_settings_for_display();
        $post_id  = Easy_Dynamic_Tag_Helper::get_post_id( $settings );

        if ( ! $post_id ) {
            return;
        }

        $comments_count = get_comments_number( $post_id );

        $no_comments_text      = ! empty( $settings['easy_no_comments_text'] ) ? $settings['easy_no_comments_text'] : __( 'No Comments', 'easy-elements-pro' );
        $single_comment_text   = ! empty( $settings['easy_single_comment_text'] ) ? $settings['easy_single_comment_text'] : __( '1 Comment', 'easy-elements-pro' );
        $multiple_comments_text = ! empty( $settings['easy_multiple_comments_text'] ) ? $settings['easy_multiple_comments_text'] : __( '{number} Comments', 'easy-elements-pro' );

        if ( $comments_count == 0 ) {
            $text = $no_comments_text;
        } elseif ( $comments_count == 1 ) {
            $text = $single_comment_text;
        } else {
            $text = str_replace( '{number}', $comments_count, $multiple_comments_text );
        }

        $enable_link = isset( $settings['easy_comments_link_enable'] ) && $settings['easy_comments_link_enable'] === 'yes';

        if ( $enable_link ) {
            $link = get_comments_link( $post_id );
            $output = '<a href="' . esc_url( $link ) . '" class="easy-comments-link">' . esc_html( $text ) . '</a>';
        } else {
            $output = '<span class="easy-comments-text">' . esc_html( $text ) . '</span>';
        }

        echo wp_kses_post( $output );
    }

}