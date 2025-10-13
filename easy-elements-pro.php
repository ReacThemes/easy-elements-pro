<?php
/**
 * Plugin Name: Easy Elements Pro
 * Plugin URI:  https://easyelements.reactheme.com/
 * Description: Add-on plugin for Easy Elements – includes advanced and premium widgets.
 * Version:     1.0.0
 * Author:      Themewant
 * Author URI:  http://themewant.com/
 * Text Domain: easy-elements-pro
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constant so free plugin knows Pro is active
define( 'EASYELEMENTS_PRO_FILE', __FILE__ );

// Define constant for Pro Plugin path
if ( ! defined( 'EASYELEMENTS_PRO_PATH' ) ) {
    define( 'EASYELEMENTS_PRO_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'EASYELEMENTS_PRO_URL' ) ) {
    define( 'EASYELEMENTS_PRO_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) ) {
    define( 'EASY_ELEMENTS_PRO_ACTIVE', true );
}


/**
 * Enqueue Easy Elements Pro Scripts
 */
function easy_elements_pro_enqueue_scripts() {
    wp_enqueue_script(
        'easy-elements-pro-js',
        EASYELEMENTS_PRO_URL . 'assets/js/eel-popup.js',
        array('jquery', 'elementor-frontend'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'easy_elements_pro_enqueue_scripts');



add_action('elementor/init', function() {
    require_once EASYELEMENTS_PRO_PATH . 'inc/extensions/dynamic-content/easy-dynamic-select.php';
});


// Include Custom Fonts functionality if available
$files_to_include = [
    'inc/custom-fonts.php',
    'inc/hook.php',
    'inc/easy-elements-cursor.php',
    'inc/theme-builder/builder-functions.php',
    'inc/extensions/visibility-control/class-easy-visibility-module.php',
    'inc/extensions/dynamic-content/easy-dynamic-tags.php',
    'inc/extensions/dynamic-content/easy-dynamic-search-ajax.php',
];

foreach ( $files_to_include as $file ) {
    $path = EASYELEMENTS_PRO_PATH . $file;
    if ( file_exists( $path ) ) {
        require_once $path;
    }
}


// Hook only after main EasyElements plugin is loaded
add_action( 'plugins_loaded', 'easy_elements_pro_plugin_init', 20 );

function easy_elements_pro_plugin_init() {
    // Check if base class exists to confirm main plugin is active
    if ( ! class_exists( 'Easyel_Elements_Elementor_Extension' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__( 'Easy Elements Pro requires the Easy Elements plugin to be active.', 'easy-elements-pro' );
            echo '</p></div>';
        } );
        return;
    }

    // Hook into Elementor widgets registration
    add_action( 'elementor/widgets/register', function( $widgets_manager ) {
        $pro_widgets = [           
            'clients_logo_slider'   => [ 'class' => '\Easyel_Clients_Logo_Slider__Widget',  'file' => EASYELEMENTS_PRO_PATH . '/widgets/clients-logo-slider/logo.php','tab' => 'widget' ],
            'image_carousel'        => [ 'class' => '\Easyel_Image_Carousel__Widget',       'file' => EASYELEMENTS_PRO_PATH . '/widgets/image-carousel/carousel.php','tab' => 'widget' ],
            'tab_advance'           => [ 'class' => '\Easyel_Advance_Tab_Widget',           'file' => EASYELEMENTS_PRO_PATH . '/widgets/tab-advance/tab.php','tab' => 'widget' ],
            'testimonials_slider'   => [ 'class' => '\Easyel_Testimonials_Slider__Widget',  'file' => EASYELEMENTS_PRO_PATH . '/widgets/testimonials-slider/testimonials.php','tab' => 'widget' ],
            'process_grid'          => [ 'class' => '\Easyel_Process_Widget',               'file' => EASYELEMENTS_PRO_PATH . '/widgets/process-grid/process-grid.php','tab' => 'widget' ],
            'process_slider'        => [ 'class' => '\Easyel_Process_Slider_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/process-slider/process-slider.php','tab' => 'widget' ],
            'team_slider'           => [ 'class' => '\Easyel_Team_Slider__Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/team-slider/team-slider.php','tab' => 'widget' ],
            'icon_box_slider'       => [ 'class' => '\Easyel_Icon_Box_Slider__Widget',      'file' => EASYELEMENTS_PRO_PATH . '/widgets/icon-box-slider/icon-box-slider.php','tab' => 'widget' ],
            'post_slider'           => [ 'class' => '\Easyel_Blog_Slider__Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/blog-slider/blog-slider.php' ,'tab' => 'widget'],
            'process_list'           => [ 'class' => '\Easyel_Process_list_Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/process-list/process-list.php','tab' => 'widget' ],
            'marquee_logo'           => [ 'class' => '\Easyel_Marquee_Logo__Widget',         'file' => EASYELEMENTS_PRO_PATH . '/widgets/marquee-logo/marquee.php' ,'tab' => 'widget'],
            'archive_post'           => [ 'class' => '\Easyel_Archive_Post__Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/archive-post/archive-post.php','tab' => 'widget' ],
            'excerpt'                => [ 'class' => '\Easyel_Excerpt_Widget',               'file' => EASYELEMENTS_PRO_PATH . '/widgets/excerpt/excerpt.php','tab' => 'widget' ],
            'post_title'            => [ 'class' => '\Easyel_Post_Title_Widget',             'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-title/post-title.php','tab' => 'widget' ],
            'post_content'           => [ 'class' => '\Easyel_Post_Content_Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-content/post-content.php','tab' => 'widget' ],
            'featured_image'          => [ 'class' => '\Easyel_Featured_Image_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/featured-image/featured-image.php' ,'tab' => 'widget'],
            'related_post'            => [ 'class' => '\Easyel_Related_Post_Widget',           'file' => EASYELEMENTS_PRO_PATH . '/widgets/related-post/related.php','tab' => 'widget' ],
            'post_meta'               => [ 'class' => '\Easyel_Post_Meta_Widget',             'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-meta/post-meta.php','tab' => 'widget' ],
            'post_pagination'         => [ 'class' => '\Easyel_Post_Pagination_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-pagination/post-pagination.php','tab' => 'widget' ],
            'post_comments'           => [ 'class' => '\Easyel_Post_Comments_Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-comments/post-comments.php','tab' => 'widget' ],
            'easy_slider'             => [ 'class' => '\Easyel_Easy_Slider_Widget',            'file' => EASYELEMENTS_PRO_PATH . '/widgets/easy-slider/easy-slider.php' ,'tab' => 'widget'],
            'image_accordion'         => [ 'class' => '\Easyel_Image_Accordion_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/image-accordion/image-accordion.php','tab' => 'widget' ],
            'featured_project'         => [ 'class' => '\Easyel_Featured_Project_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/feature-project/feature-project.php','tab' => 'widget' ],
            'advance_button'         => [ 'class' => '\Easyel_Advance_Button_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/advance-button/button.php','tab' => 'widget' ],
            'hr_image_scroll'         => [ 'class' => '\Easyel_Hr_Image_Scroll_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/horizontal-image-scroll/horizontal-image-scroll.php','tab' => 'widget' ],
            'post_tags'         => [ 'class' => '\Easyel_Post_Tags_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-tag/post-tag.php','tab' => 'widget' ],
            'post_author'         => [ 'class' => '\Easyel_Post_Author_Info_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-author/post-author.php','tab' => 'widget' ],
            'timeline_slider'         => [ 'class' => '\Easyel_Timeline_Slider_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/timeline-slider/timeline.php','tab' => 'widget' ],
            'enable_image_hover_effect'         => [ 'class' => '\Easyel_image_hover_effect__Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/image-hover-effect/image-hover-effect.php','tab' => 'widget' ],
            'image_gallery_filter'         => [ 'class' => '\Easyel__Gallery_Pro_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/gallery-filter/gallery-filter.php','tab' => 'widget' ],
            'portfolio_pro'         => [ 'class' => '\Easyel_Portfolio_Pro_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/portfolio/portfolio.php','tab' => 'widget' ],
        ];

        $extensions_settings = get_option( 'easy_element_extensions', [] );

        foreach ( $pro_widgets as $key => $widget ) {

            $is_enabled = false;

            if ( $widget['tab'] === 'extensions' ) {
                $is_enabled = isset( $extensions_settings[$key] ) && $extensions_settings[$key] == 1;
            } else {
                $option_name = 'easy_element_' . $widget['tab'] . '_' . $key;
                $is_enabled = get_option( $option_name, '1' ) == '1';
            }

            if ( ! $is_enabled ) {
                continue;
            }

            if ( file_exists( $widget['file'] ) ) {
                require_once $widget['file'];
                if ( class_exists( $widget['class'] ) ) {
                    $widgets_manager->register( new $widget['class']() );
                }
            }
        }
    });
}

class Easy_Elements_Pro {
    public function __construct() {
        // Add pro-specific hooks or filters here if needed
        
    }
}
// Instantiate it globally if needed
new Easy_Elements_Pro();


// Global Video Popup Markup in Footer
function entro_global_video_popup_markup() {
    ?>
    <div class="entro-all-video-popup-wrap" id="global-video-popup">
        <div class="entro-all-video-popup-inner">
            <button class="entro-all-video-popup-close">&times;</button>
            <div class="entro-all-video-popup-iframe-wrapper"></div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'entro_global_video_popup_markup' );