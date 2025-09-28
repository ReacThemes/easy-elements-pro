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
        add_filter( 'easyel_singulars_data', [ $this, 'easyel_builder_singular_data_func' ] );
        add_filter('easyel_archives_data', [ $this, 'easyel_builder_archive_data_func'] );
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

    public function easyel_builder_singular_data_func( $singulars ) {

        // ---- Core (Pro) ----
        $singulars[] = [
            'value' => 'front_page',
            'label' => __('Front Page','easy-elements'),
            'group' => null
        ];

        // ---- Posts ----
        $post_items = [
            ['value'=>'post','label'=>__('Posts','easy-elements')],
            ['value'=>'in_category','label'=>__('In Category','easy-elements')],
            ['value'=>'in_category_children','label'=>__('In Category Children','easy-elements')],
            ['value'=>'in_post_tag','label'=>__('In Tag','easy-elements')],
            ['value'=>'post_by_author','label'=>__('Posts By Author','easy-elements')],
        ];
        foreach($post_items as $item){
            $item['group'] = 'Posts';
            $singulars[] = $item;
        }

        // ---- Pages ----
        $page_items = [
            ['value'=>'page','label'=>__('Pages','easy-elements')],
            ['value'=>'page_by_author','label'=>__('Pages By Author','easy-elements')],
        ];
        foreach($page_items as $item){
            $item['group'] = 'Page';
            $singulars[] = $item;
        }

        // ---- Others ----
        $others = [
            ['value'=>'child_of','label'=>__('Direct Child Of','easy-elements')],
            ['value'=>'any_child_of','label'=>__('Any Child Of','easy-elements')],
            ['value'=>'by_author','label'=>__('By Author','easy-elements')],
            ['value'=>'not_found404','label'=>__('404 Page','easy-elements')],
        ];
        foreach( $others as $item ){
            $item['group'] = null;
            $singulars[] = $item;
        }

        // ---- WooCommerce ----
        if ( class_exists('WooCommerce') ) {
            $singulars[] = [
                'value' => 'product',
                'label' => __('Products', 'easy-elements'),
                'group' => 'Products'
            ];
            $singulars[] = [
                'value' => 'product_by_author',
                'label' => __('Products By Author', 'easy-elements'),
                'group' => 'Products'
            ];
        }

        return $singulars;
    }


    public function easyel_builder_archive_data_func( $archives  ) {

        // Ensure Pro groups exist
        if (!isset($archives['posts_archive'])) {
            $archives['posts_archive'] = [];
        }
        if (!isset($archives['products_archive'])) {
            $archives['products_archive'] = [];
        }
        if (!isset($archives['custom'])) {
            $archives['custom'] = [];
        }

        // ---- Core (Pro) ----
        $archives['core'][] = [
            'value' => 'author',
            'label' => __('Author Archive','easy-elements'),
            'group' => 'Core'
        ];
        $archives['core'][] = [
            'value' => 'search',
            'label' => __('Search Results','easy-elements'),
            'group' => 'Core'
        ];
        $archives['core'][] = [
            'value' => 'date',
            'label' => __('Date Archive','easy-elements'),
            'pro'   => true,
            'group' => 'Core'
        ];

        // ---- Posts Archive (Pro) ----
        $archives['posts_archive'][] = [
            'value'=>'post_archive',
            'label'=>__('Posts archive','easy-elements'),
            'group'=>'Posts'
        ];
        $archives['posts_archive'][] = [
            'value'=>'category',
            'label'=>__('Categories','easy-elements'),
            'group'=>'Posts'
        ];
        $archives['posts_archive'][] = [
            'value'=>'child_of_category',
            'label'=>__('Direct child Category of','easy-elements'),
            'group'=>'Posts'
        ];
        $archives['posts_archive'][] = [
            'value'=>'any_child_of_category',
            'label'=>__('Any child Category of','easy-elements'),
            'group'=>'Posts'
        ];

        $tags = get_terms(['taxonomy'=>'post_tag','hide_empty'=>false]);
        if ( ! empty($tags) ) {
            $archives['posts_archive'][] = [
                'value'=>'post_tag',
                'label'=>__('Tags','easy-elements'),
                'group'=> 'Posts'
            ];
        }

        // ---- WooCommerce (Pro) ----
        if ( class_exists('WooCommerce') ) {
            $archives['products_archive'][] = [
                'value' => 'all_product_archive',
                'label' => __('All Product Archives', 'easy-elements'),
                'group' => 'Products'
            ];

            $woo_taxonomies = ['shop_page', 'product_search', 'product_brand', 'product_cat', 'product_tag'];
            foreach ($woo_taxonomies as $tax) {
                if ($tax === 'shop_page') {
                    $archives['products_archive'][] = [
                        'value' => $tax,
                        'label' => __('Shop Page', 'easy-elements'),
                        'group' => 'Products'
                    ];
                } elseif ($tax === 'product_search') {
                    $archives['products_archive'][] = [
                        'value' => $tax,
                        'label' => __('Search Results', 'easy-elements'),
                        'group' => 'Products'
                    ];
                } else {
                    $taxonomy_obj = get_taxonomy($tax);
                    if ($taxonomy_obj) {
                        $archives['products_archive'][] = [
                            'value' => $tax,
                            'label' => "Product " . $taxonomy_obj->labels->singular_name,
                            'group' => 'Products'
                        ];
                    }
                }
            }
        }

        // ---- Custom Taxonomies/Post Types (Pro) ----
        $args = [
            'public'   => true,
            '_builtin' => true,
        ];
        $post_types = get_post_types($args, 'objects');
        unset($post_types['attachment']);

        $args['_builtin'] = false;
        $custom_post_types = get_post_types($args, 'objects');

        $post_types = apply_filters('easyel_location_rule_post_types', array_merge( $post_types, $custom_post_types ) );
        $taxonomies = get_taxonomies(['public'=>true], 'objects');

        if ( ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {
                if ( 'post_format' === $taxonomy->name ) {
                    continue;
                }
                foreach ( $post_types as $post_type ) {
                    if ( in_array($post_type->name, $taxonomy->object_type, true) ) {
                        $archives['custom'][] = [
                            'value' => $taxonomy->name,
                            'label' => sprintf(
                                __('%1$s (%2$s)', 'easy-elements'),
                                $taxonomy->labels->singular_name,
                                $post_type->labels->singular_name
                            ),
                            'group' => 'Custom'
                        ];
                    }
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