<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$checked = get_option('easyel_enable_cursor', 0);
    if ( $checked != 1 ) return;
/**
 * Add cursor divs in footer
 */
add_action('wp_footer', function() {
    echo '<div class="eel-cursor"></div><div class="eel-cursor2"></div>';
});

/**
 * Enqueue cursor CSS and JS safely
 */
add_action('wp_enqueue_scripts', function() {
    // Cursor CSS (small, okay for render)
    $css_file = plugin_dir_url(__FILE__) . 'css/cursor.css';
    if ( file_exists(plugin_dir_path(__FILE__) . 'css/cursor.css') ) {
        wp_enqueue_style( 'easy-elements-cursor', $css_file, [], '1.0' );
    }

});
?>