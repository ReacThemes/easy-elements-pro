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

if ( ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) ) {
    define( 'EASY_ELEMENTS_PRO_ACTIVE', true );
}


// Include Custom Fonts functionality if available
$files_to_include = [
    'inc/custom-fonts.php',
    'inc/hook.php',
    'inc/easy-elements-cursor.php',
    'inc/theme-builder/builder-functions.php',
];

foreach ( $files_to_include as $file ) {
    $path = EASYELEMENTS_PRO_PATH . $file;
    if ( file_exists( $path ) ) {
        require_once $path;
    }
}


// PHP: enqueue scripts for both frontend and editor

function enqueue_easyel_gsap_scripts() {

    if ( ! did_action( 'elementor/loaded' ) ) return;

    $checked = get_option('easyel_enable_js_animation', 0);
    if ( $checked != 1 ) return;

    $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

    $scripts = [
        'gsap' => "assets/js/gsap{$suffix}.js",
        'scrolltrigger' => "assets/js/ScrollTrigger{$suffix}.js",
        'splittext' => "assets/js/SplitText{$suffix}.js",
        'eel-easyel-animation' => "assets/js/easyel-animation.js",
    ];

    foreach($scripts as $handle => $path){
        $deps = [];
        if($handle === 'scrolltrigger' || $handle === 'splittext') $deps = ['gsap'];
        if($handle === 'eel-easyel-animation') $deps = ['gsap','scrolltrigger','splittext'];

        wp_enqueue_script(
            $handle,
            plugins_url($path, __FILE__),
            $deps,
            '1.0.0',
            true
        );
    }

    // Fixed defer
    add_filter('script_loader_tag', function($tag, $handle){
        $defer_scripts = ['gsap','scrolltrigger','splittext','eel-easyel-animation'];
        if(in_array($handle, $defer_scripts)){
            return str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }, 10, 2);
}

add_action( 'wp_enqueue_scripts', 'enqueue_easyel_gsap_scripts', 99 );
add_action( 'elementor/editor/after_enqueue_scripts', 'enqueue_easyel_gsap_scripts', 99 );


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
            'clients_logo_slider'   => [ 'class' => '\Easyel_Clients_Logo_Slider__Widget',  'file' => EASYELEMENTS_PRO_PATH . '/widgets/clients-logo-slider/logo.php' ],
            'image_carousel'        => [ 'class' => '\Easyel_Image_Carousel__Widget',       'file' => EASYELEMENTS_PRO_PATH . '/widgets/image-carousel/carousel.php' ],
            'tab_advance'           => [ 'class' => '\Easyel_Advance_Tab_Widget',           'file' => EASYELEMENTS_PRO_PATH . '/widgets/tab-advance/tab.php' ],
            'testimonials_slider'   => [ 'class' => '\Easyel_Testimonials_Slider__Widget',  'file' => EASYELEMENTS_PRO_PATH . '/widgets/testimonials-slider/testimonials.php' ],
            'process_grid'          => [ 'class' => '\Easyel_Process_Widget',               'file' => EASYELEMENTS_PRO_PATH . '/widgets/process-grid/process-grid.php' ],
            'process_slider'        => [ 'class' => '\Easyel_Process_Slider_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/process-slider/process-slider.php' ],
            'team_slider'           => [ 'class' => '\Easyel_Team_Slider__Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/team-slider/team-slider.php' ],
            'icon_box_slider'       => [ 'class' => '\Easyel_Icon_Box_Slider__Widget',      'file' => EASYELEMENTS_PRO_PATH . '/widgets/icon-box-slider/icon-box-slider.php' ],
            'post_slider'           => [ 'class' => '\Easyel_Blog_Slider__Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/blog-slider/blog-slider.php' ],
            'process_list'           => [ 'class' => '\Easyel_Process_list_Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/process-list/process-list.php' ],
            'marquee_logo'           => [ 'class' => '\Easyel_Marquee_Logo__Widget',         'file' => EASYELEMENTS_PRO_PATH . '/widgets/marquee-logo/marquee.php' ],
            'archive_post'           => [ 'class' => '\Easyel_Archive_Post__Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/archive-post/archive-post.php' ],
            'excerpt'                => [ 'class' => '\Easyel_Excerpt_Widget',               'file' => EASYELEMENTS_PRO_PATH . '/widgets/excerpt/excerpt.php' ],
            'post_title'            => [ 'class' => '\Easyel_Post_Title_Widget',             'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-title/post-title.php' ],
            'post_content'           => [ 'class' => '\Easyel_Post_Content_Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-content/post-content.php' ],
            'featured_image'          => [ 'class' => '\Easyel_Featured_Image_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/featured-image/featured-image.php' ],
            'related_post'            => [ 'class' => '\Easyel_Related_Post_Widget',           'file' => EASYELEMENTS_PRO_PATH . '/widgets/related-post/related.php' ],
            'post_meta'               => [ 'class' => '\Easyel_Post_Meta_Widget',             'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-meta/post-meta.php' ],
            'post_pagination'         => [ 'class' => '\Easyel_Post_Pagination_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-pagination/post-pagination.php' ],
            'post_comments'           => [ 'class' => '\Easyel_Post_Comments_Widget',          'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-comments/post-comments.php' ],
            'easy_slider'             => [ 'class' => '\Easyel_Easy_Slider_Widget',            'file' => EASYELEMENTS_PRO_PATH . '/widgets/easy-slider/easy-slider.php' ],
            'image_accordion'         => [ 'class' => '\Easyel_Image_Accordion_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/image-accordion/image-accordion.php' ],
            'featured_project'         => [ 'class' => '\Easyel_Featured_Project_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/feature-project/feature-project.php' ],
            'advance_button'         => [ 'class' => '\Easyel_Advance_Button_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/advance-button/button.php' ],
            'hr_image_scroll'         => [ 'class' => '\Easyel_Hr_Image_Scroll_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/horizontal-image-scroll/horizontal-image-scroll.php' ],
            'post_tags'         => [ 'class' => '\Easyel_Post_Tags_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-tag/post-tag.php' ],
            'post_author'         => [ 'class' => '\Easyel_Post_Author_Info_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/post-author/post-author.php' ],
            'timeline_slider'         => [ 'class' => '\Easyel_Timeline_Slider_Widget',        'file' => EASYELEMENTS_PRO_PATH . '/widgets/timeline-slider/timeline.php' ],
        ];

        foreach ( $pro_widgets as $widget ) {
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