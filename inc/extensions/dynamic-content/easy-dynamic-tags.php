<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Manager;

class Easy_Dynamic_Tags {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
       
        add_action('elementor/dynamic_tags/register', [$this, 'easy_dynamic_tag_register_group'], 1);
        add_action('elementor/dynamic_tags/register', [$this, 'easy_dynamic_tag_register']);
    }

    public function easy_dynamic_tag_register_group( Manager $dynamic_tags_manager ) {
        $dynamic_tags_manager->register_group(
            'easy-post-tags',
            [
                'title' => esc_html__('Easy EA - Post', 'easy-elements-pro')
            ]
           
        );
        $dynamic_tags_manager->register_group(
            'easy-author-infotag',
            [
                'title' => esc_html__('Easy EA - User', 'easy-elements-pro')
            ]
        );

        $dynamic_tags_manager->register_group(
            'easy-media-tag',
            [
                'title' => esc_html__('Easy EA - Media', 'easy-elements-pro')
            ]
        );
    }

    function easy_dynamic_tag_groups() {

        //  Post Related Tags
        $post_group = [
            'easy-post-title'        => 'Easy_Post_Title_Tag',
            'easy-post-date'         => 'Easy_Dynamic_Post_Date',
            'easy-post-id'           => 'Easy_Dynamic_Post_ID',
            'easy-post-time'         => 'Easy_Dynamic_Post_Time',
            'easy-custom-field'      => 'Easy_Dynamic_Post_CustomField',
            'easy-post-terms'        => 'Easy_Dynamic_Post_Terms',
            'easy-post-excerpt'      => 'Easy_Dynamic_Post_Excerpt',
        ];

        //  Archive Related Tags
        $archive_group = [
            'easy-archive-title'        => 'Easy_Dynamic_Archive_Title',
            'easy-archive-description'  => 'Easy_Dynamic_Archive_Description',
            'easy-archive-meta'         => 'Easy_Dynamic_Archive_Meta',
        ];

        //  Site Related Tags
        $site_group = [
            'easy-page-title'        => 'Easy_Dynamic_Page_Title',
            'easy-site-tagline'      => 'Easy_Dynamic_Site_Tagline',
            'easy-site-title'        => 'Easy_Dynamic_Site_Title',
            'easy-current-datetime'  => 'Easy_Dynamic_Current_DateTime',
            'easy-request-parameter' => 'Easy_Dynamic_Request_Parameter',
            'easy-shortcode'         => 'Easy_Dynamic_Shortcode',
            'easy-user-info'         => 'Easy_Dynamic_User_Info',
        ];

        // Media Related Tags
        $media_group = [
            'easy-media-featureimg' => 'Easy_Dynamic_Featured_Image_Data',
        ];

        //  Author Related Tags
        $author_group = [
            'easy-author-info' => 'Easy_Dynamic_Author_Info',
            'easy-author-meta' => 'Easy_Dynamic_Author_Meta',
        ];

        // Comment Related Tags
        $comment_group = [
            'easy-comments'        => 'Easy_Dynamic_Comments',
        ];

        // WooCommerce Related Tags
        $woocommerce_group = [
            'easy-product-price'        => 'Easy_Dynamic_Product_Price',
            'easy-product-rating'       => 'Easy_Dynamic_Product_Rating',
            'easy-product-sale'         => 'Easy_Dynamic_Product_Sale',
            'easy-product-content'      => 'Easy_Dynamic_Product_Content',
            'easy-product-short-desc'   => 'Easy_Dynamic_Product_Short_Description',
            'easy-product-sku'          => 'Easy_Dynamic_Product_SKU',
            'easy-product-stock'        => 'Easy_Dynamic_Product_Stock',
            'easy-product-terms'        => 'Easy_Dynamic_Product_Terms',
            'easy-product-title'        => 'Easy_Dynamic_Product_Title',
        ];

        return [
            'post'         => $post_group,
            'archive'      => $archive_group,
            'site'         => $site_group,
            'media'        => $media_group,
            'author'       => $author_group,
            'comments'     => $comment_group,
            'woocommerce'  => $woocommerce_group,
        ];
    }

    public function easy_dynamic_tag_register( Manager $dynamic_tags_manager ) {

        require_once EASYELEMENTS_PRO_PATH . 'inc/extensions/dynamic-content/dynamic-tag-helper.php';
        require_once EASYELEMENTS_PRO_PATH . 'inc/extensions/dynamic-content/tag-common-function.php';
        if ( class_exists( '\Elementor\Core\DynamicTags\Tag' ) ) {

            foreach ( $this->easy_dynamic_tag_groups() as $group_key => $group_tags ) {
                foreach ( $group_tags as $tag_id => $class_name ) {

                    $file_path = EASYELEMENTS_PRO_PATH . 'inc/extensions/dynamic-content/tags/class-' . strtolower(str_replace('_', '-', $class_name)) . '.php';

                    if ( file_exists( $file_path ) ) {
                         
                         
                        require_once $file_path;

                        if ( class_exists( $class_name ) ) {
                            $dynamic_tags_manager->register_tag( $class_name );
                        }
                    } 
                }
            }
        }
    }
}

Easy_Dynamic_Tags::get_instance();
