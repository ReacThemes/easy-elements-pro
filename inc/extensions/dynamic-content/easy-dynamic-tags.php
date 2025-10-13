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

        add_action( 'wp_enqueue_scripts', [$this, 'easy_enqueue_product_price_css' ], );
        add_action( 'elementor/editor/after_enqueue_styles', [$this, 'easy_enqueue_product_price_css' ], );
    }

    function easy_enqueue_product_price_css() {
        $css_url = plugin_dir_url( __FILE__ ) . 'assets/css/easy-product-price.css';

        wp_enqueue_style(
            'easy-product-price-style',
            EASYELEMENTS_PRO_URL . 'inc/extensions/dynamic-content/assets/css/easy-product-price.css',
            array(),
            time()
        );
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

        $dynamic_tags_manager->register_group(
            'easy-comments-tag',
            [
                'title' => esc_html__('Easy EA - Comments', 'easy-elements-pro')
            ]
        );

        $dynamic_tags_manager->register_group(
            'easy-archive-tag',
            [
                'title' => esc_html__('Easy EA - Archive', 'easy-elements-pro')
            ]
        );

        $dynamic_tags_manager->register_group(
            'easy-sitetitle-tag',
            [
                'title' => esc_html__('Easy EA - Site', 'easy-elements-pro')
            ]
        );

        $dynamic_tags_manager->register_group(
            'easy-search-tag',
            [
                'title' => esc_html__('Easy EA - Search', 'easy-elements-pro')
            ]
        );

        $dynamic_tags_manager->register_group(
            'easy-terms-tag',
            [
                'title' => esc_html__('Easy EA - Terms', 'easy-elements-pro')
            ]
        );

        $dynamic_tags_manager->register_group(
            'easy-wooproduct-tag',
            [
                'title' => esc_html__('Easy EA - WooCommerce', 'easy-elements-pro')
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
            'easy-post-url'          => 'Easy_Dynamic_Post_Url',
            'easy-post-author-url'   => 'Easy_Dynamic_Post_Author_Url',
        ];

        //  Archive Related Tags
        $archive_group = [
            'easy-archive-title'        => 'Easy_Dynamic_Archive_Title',
            'easy-archive-description'  => 'Easy_Dynamic_Archive_Description',
            'easy-archive-meta'         => 'Easy_Dynamic_Archive_Meta',
            'easy-archive-url'          => 'Easy_Dynamic_Archive_Url',
        ];

        //  Site Related Tags
        $site_group = [
            'easy-page-title'        => 'Easy_Dynamic_Page_Title',
            'easy-page-siteurl'      => 'Easy_Dynamic_Site_Url',
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
            'easy-author-url' => 'Easy_Dynamic_Author_Url',
            'easy-login-logout-url' => 'Easy_Dynamic_Login_Logout_Url',
        ];

        // Comment Related Tags
        $comment_group = [
            'easy-comments'        => 'Easy_Dynamic_Comments',
            'easy-comments-url'    => 'Easy_Dynamic_Comments_Url',
        ];

        // search tag
        $search_group = [
            'easy-search-query'        => 'Easy_Search_Query',
            'easy-search-result'        => 'Easy_Search_Results',
        ];

        $terms_group = [
            'easy-terms-title'              => 'Easy_Terms_Title',
            'easy-terms-description'        => 'Easy_Terms_Description',
            'easy-terms-slug'               => 'Easy_Terms_Slug',
            'easy-terms-meta'               => 'Easy_Terms_Meta',
            'easy-terms-count'              => 'Easy_Terms_Count',
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
            'easy-product-shipping'     => 'Easy_Dynamic_Product_Shipping',
            'easy-product-type'         => 'Easy_Dynamic_Product_Type',
            'easy-add-to-cart'          => 'Easy_Dynamic_Product_Add_ToCart',
            'easy-product-url'          => 'Easy_Dynamic_Product_Url',
            'easy-product-shop-url'     => 'Easy_Dynamic_Product_Shop_Url',
            'easy-product-checkout-url'     => 'Easy_Dynamic_Product_Checkout_Url',
        ];

        return [
            'post'         => $post_group,
            'archive'      => $archive_group,
            'site'         => $site_group,
            'media'        => $media_group,
            'author'       => $author_group,
            'comments'     => $comment_group,
            'search'       => $search_group,
            'terms'        => $terms_group,
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
