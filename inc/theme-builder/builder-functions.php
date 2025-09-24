<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // direct access disallow
}

class EasyEL_Free_Pro_Unlock {

    /**
     * Hold the singleton instance
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        // Register hooks
        add_filter( 'easyel_singulars_data', [ $this, 'easyel_builder_unlock_pro_items' ] );
        add_filter('easyel_archives_data', [ $this, 'easyel_unlock_pro_archives' ] );
    }

    /**
     * Singleton instance
     */
    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Unlock pro items and remove label [Pro]
     */
    public function easyel_builder_unlock_pro_items( $singulars ) {
        foreach ( $singulars as &$item ) {
            if ( isset( $item['pro'] ) && $item['pro'] === true ) {
                $item['pro'] = false;
                $item['label'] = str_replace( ' [Pro]', '', $item['label'] );
            }
        }
        return $singulars;
    }

    /**
     * Unlock all Pro items in archives
     *
     * @param array $archives
     * @return array
     */
    function easyel_unlock_pro_archives($archives) {
        foreach ($archives as $group => &$items) {
            foreach ($items as &$item) {
                if (isset($item['pro']) && $item['pro'] === true) {
                    $item['pro'] = false;
                    // Optional: [Pro]  remove
                    $item['label'] = str_replace(' [Pro]', '', $item['label']);
                }
            }
        }
        return $archives;
    }

    public function check_conditions_pro( $type, $conditions ) {

        $include_match = false;

        // --------- INCLUDE CHECK ---------
        foreach ( $conditions as $cond ) {
            
            if ( ($cond['include'] ?? 'include') !== 'include' ) continue;

           
            $main = $cond['main'] ?? '';
            $sub = $cond['sub'] ?? '';

            if( empty( $sub ) ) {
                $sub = $main;
            }

            $id  = $cond['id'] ?? 0;
            $match = false;

            // Archive
            if ( $type === 'archive' && ( is_archive() || is_search() || is_home() || is_date() || is_author() ) ) {

                if ( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
                    switch ($sub) {
                        case 'all_product_archive':
                            $match = is_shop() || 
                                            (is_search() && get_query_var('post_type') === 'product') ||
                                            is_tax('product_cat') ||
                                            is_tax('product_tag') ||
                                            is_tax('product_brand');
                            break;
                        case 'shop_page':
                            $match = is_shop();
                            break;

                        case 'product_search':
                            $match = is_search() && get_query_var('post_type') === 'product';
                            break;

                        case 'product_cat':
                        case 'product_tag':
                        case 'product_brand':
                            $match = is_tax($sub);
                            break;
                    }
                } else {
                    switch ( $sub ) {
                    
                        case 'entire-site': $match = true; break;
                        case 'index': $match = is_home() || is_archive() || is_search() || is_date() || is_author(); break;
                        case 'author': $match = is_author(); break;
                        case 'search': $match = is_search(); break;
                        case 'date': $match = is_date(); break;
                        case 'post_archive': $match = is_post_type_archive('post'); break;
                        case 'category': $match = is_category(); break;
                        case 'child_of_category':
                            if ( is_category() && $id ) $match = cat_is_ancestor_of($id, get_queried_object_id());
                            break;
                        case 'any_child_of_category':
                            if ( is_category() && $id ) {
                                $current_cat = get_queried_object();
                                $ancestors = get_ancestors($current_cat->term_id, 'category');
                                $match = in_array($id, $ancestors, true);
                            }
                            break;
                        case 'post_tag': $match = is_tag(); break;
                        default:
                            if ( taxonomy_exists($sub) && is_tax($sub) ) $match = true;
                            elseif ( post_type_exists($sub) && is_post_type_archive($sub) ) $match = true;
                            break;
                    }
                }
               
            }

            // Single
            if ( $type === 'single' && ( is_singular() || is_front_page() || is_404() ) ) {

                if ( class_exists('WooCommerce') && (
                    is_singular('product') || 
                    is_cart() || 
                    is_checkout() || 
                    is_account_page()
                ) ) {
                    // WooCommerce product specific cases
                    switch ($sub) {
                        case 'product':
                            $match = true;
                            break;
                        case 'product_by_author':
                            $match = get_post_field('post_author', get_the_ID()) == $id;
                            break;
                        default:
                            $match = false; 
                            break;
                    }
                } else {
                    // Normal WordPress single/page/post rules
                    switch ($sub) {
                        case 'entire-site':  
                            $match = true; 
                            break;

                        case 'all': 
                            $match = is_singular() || is_front_page() || is_404();
                            break;

                        case 'front_page': 
                            $match = is_front_page(); 
                            break;

                        case 'post': 
                            $match = is_singular('post'); 
                            break;

                        case 'in_category': 
                            $match = is_single() && has_category($id); 
                            break;

                        case 'in_category_children':
                            if ( is_single() ) {
                                $cats = get_the_category(get_the_ID());
                                if ($cats) {
                                    foreach ($cats as $cat) {
                                        if ( cat_is_ancestor_of($id, $cat->term_id) ) {
                                            $match = true; 
                                            break;
                                        }
                                    }
                                }
                            }
                            break;

                        case 'in_post_tag': 
                            $match = is_single() && has_tag($id); 
                            break;

                        case 'post_by_author': 
                            $match = is_singular('post') && get_post_field('post_author', get_the_ID()) == $id; 
                            break;

                        case 'page': 
                            $match = is_page(); 
                            break;

                        case 'page_by_author': 
                            $match = is_page() && get_post_field('post_author', get_the_ID()) == $id; 
                            break;

                        case 'child_of': 
                            $match = is_page() && wp_get_post_parent_id(get_the_ID()) == $id; 
                            break;

                        case 'any_child_of':
                            if ( is_page() ) {
                                $ancestors = get_post_ancestors(get_the_ID());
                                $match = in_array($id, $ancestors);
                            }
                            break;

                        case 'by_author': 
                            $match = is_singular() && get_post_field('post_author', get_the_ID()) == $id; 
                            break;

                        case 'not_found404': 
                            $match = is_404(); 
                            break;

                        default:
                            $match = false;
                            break;
                    }
                }
            }

            if ($match) {
                $include_match = true;
                break;
            }
        }

        if ( ! $include_match ) return false;

        // --------- EXCLUDE CHECK ---------
        foreach ( $conditions as $cond ) {
            if ( ($cond['include'] ?? 'include') !== 'exclude' ) continue;

            $main = $cond['main'] ?? '';

            $sub = $cond['sub'] ?? $main;

            if( empty( $sub ) ) {
                $sub = $main;
            }

            $id  = $cond['id'] ?? 0;
            $exclude_match = false;

            // Archive
            if ( $type === 'archive' && ( is_archive() || is_search() || is_home() || is_date() || is_author() ) ) {

                if ( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
                    switch ($sub) {
                        case 'all_product_archive':
                            $exclude_match = is_shop() || 
                                            (is_search() && get_query_var('post_type') === 'product') ||
                                            is_tax('product_cat') ||
                                            is_tax('product_tag') ||
                                            is_tax('product_brand');
                            break;
                        case 'shop_page':
                            $exclude_match = is_shop();
                            break;

                        case 'product_search':
                           $exclude_match = is_search() && get_query_var('post_type') === 'product';
                            break;

                        case 'product_cat':
                        case 'product_tag':
                        case 'product_brand':
                           $exclude_match = is_tax($sub);
                            break;
                    }
                } else {
                    switch ($sub) {
                        case 'entire-site': $match = true; break;
                        case 'index': $exclude_match = is_home() || is_archive() || is_search() || is_date() || is_author(); break;
                        case 'author': $exclude_match = is_author(); break;
                        case 'search': $exclude_match = is_search(); break;
                        case 'date': $exclude_match = is_date(); break;
                        case 'post_archive': $exclude_match = is_post_type_archive('post'); break;
                        case 'category': $exclude_match = is_category(); break;
                        case 'child_of_category':
                            if ( is_category() && $id ) $exclude_match = cat_is_ancestor_of($id, get_queried_object_id());
                            break;
                        case 'any_child_of_category':
                            if ( is_category() && $id ) {
                                $current_cat = get_queried_object();
                                $ancestors = get_ancestors($current_cat->term_id, 'category');
                                $exclude_match = in_array($id, $ancestors, true);
                            }
                            break;
                        case 'post_tag': $exclude_match = is_tag(); break;
                        default:
                            if ( taxonomy_exists($sub) && is_tax($sub) ) $exclude_match = true;
                            elseif ( post_type_exists($sub) && is_post_type_archive($sub) ) $exclude_match = true;
                            break;
                    }
                }
               
            }

            // Single
           if ( $type === 'single' && ( is_singular() || is_front_page() || is_404() ) ) {

                // WooCommerce product exclude rules skip
                if ( class_exists('WooCommerce') && (
                    is_singular('product') || 
                    is_cart() || 
                    is_checkout() || 
                    is_account_page()
                ) ) {
                    switch ($sub) {
                        case 'product':
                            $exclude_match = true;
                            break;

                        case 'product_by_author':
                            $exclude_match = get_post_field('post_author', get_the_ID()) == $id;
                            break;

                        default:
                            $exclude_match = false;
                            break;
                    }
                } else {
                    // Normal WordPress exclude rules
                    switch ($sub) {
                        case 'entire-site': 
                            if ( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
                                $exclude_match = false;
                            } else {
                                $exclude_match = true;
                            }
                            break;

                        case 'all': 
                            $exclude_match = true; 
                            break;

                        case 'front_page': 
                            $exclude_match = is_front_page(); 
                            break;

                        case 'post': 
                            $exclude_match = is_singular('post'); 
                            break;

                        case 'in_category': 
                            $exclude_match = is_single() && has_category($id); 
                            break;

                        case 'in_category_children':
                            if ( is_single() ) {
                                $cats = get_the_category(get_the_ID());
                                if ($cats) {
                                    foreach ($cats as $cat) {
                                        if ( cat_is_ancestor_of($id, $cat->term_id) ) {
                                            $exclude_match = true; 
                                            break;
                                        }
                                    }
                                }
                            }
                            break;

                        case 'in_post_tag': 
                            $exclude_match = is_single() && has_tag($id); 
                            break;

                        case 'post_by_author': 
                            $exclude_match = is_singular('post') && get_post_field('post_author', get_the_ID()) == $id; 
                            break;

                        case 'page': 
                            $exclude_match = is_page(); 
                            break;

                        case 'page_by_author': 
                            $exclude_match = is_page() && get_post_field('post_author', get_the_ID()) == $id; 
                            break;

                        case 'child_of': 
                            $exclude_match = is_page() && wp_get_post_parent_id(get_the_ID()) == $id; 
                            break;

                        case 'any_child_of':
                            if ( is_page() ) {
                                $ancestors = get_post_ancestors(get_the_ID());
                                $exclude_match = in_array($id, $ancestors);
                            }
                            break;

                        case 'by_author': 
                            $exclude_match = is_singular() && get_post_field('post_author', get_the_ID()) == $id; 
                            break;

                        case 'not_found404': 
                            $exclude_match = is_404(); 
                            break;

                        default:
                            $exclude_match = false;
                            break;
                    }
                }
            }

            if ( $exclude_match ) return false;
        }

        return true;
    }

}

// Bootstrap
EasyEL_Free_Pro_Unlock::instance();
