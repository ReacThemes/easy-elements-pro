<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Archive_Url extends Tag {

    public function get_name() {
        return 'easy-author-archive-url-dynamic';
    }

    public function get_title() {
        return __( 'Archive URL ', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-archive-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }

    public function is_settings_required() {
		return false;
	}

    /**
     * Register controls (popup settings)
     */
    protected function register_controls() {
        $this->add_control(
            'easy_author_archive_url',
            [
                'label' => __( 'Author Url', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'author-url',
                'options' => [
                    'author-url'  => __( 'Author Archive Url', 'easy-elements-pro' ),
                    'author-website-url'  => __( 'Website Url', 'easy-elements-pro' ),
                ],
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();
        $url = home_url(); // Default fallback

        $type = $settings['easy_author_archive_url'] ?? 'author-url';
       
        $author_id = get_queried_object_id();

        if ( $type === 'author-website-url' ) {
            $author_website = get_the_author_meta( 'user_url', $author_id );
            $url = ! empty( $author_website ) ? $author_website : home_url();
        } elseif (is_author()) {
            $url = get_author_posts_url(get_queried_object_id());
        
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            $term_link = get_term_link( $term );
            if ( ! is_wp_error( $term_link ) ) {
                $url = $term_link;
            }

        } elseif ( is_year() ) {
            $url = get_year_link( get_query_var( 'year' ) );

        } elseif ( is_month() ) {
            $url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );

        } elseif ( is_day() ) {
            $url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );

        } elseif ( is_post_type_archive() ) {
            $post_type = get_query_var( 'post_type' );
            $url = get_post_type_archive_link( $post_type );
        }
        else {
            $author_nicename = get_the_author_meta( 'user_nicename', $author_id );
            if ( empty( $author_nicename ) ) {
                $author_nicename = 'admin';
            }
            $url = home_url( '/author/' . $author_nicename . '/' );
        }

        if (is_wp_error($url)) {
            $url = '';
        }


        return esc_url( $url );
    }

    public function render() {
        echo esc_url( $this->get_value() );
    }

}