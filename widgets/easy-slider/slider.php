<?php
static $instance = 0;
$instance++;
$unique_id = 'swiper-' . $instance;
$settings = $this->get_settings_for_display();

// Set default values and handle null cases
$data_loop = isset($settings['loop']) && $settings['loop'] === 'yes' ? 'true' : 'false';
$data_autoplay = (isset($settings['autoplay']) && ($settings['autoplay'] === 'yes' || $settings['autoplay'] === true)) ? 'true' : 'false';
$data_navigation = (isset($settings['navigation']) && ($settings['navigation'] === 'yes' || $settings['navigation'] === true)) ? 'true' : 'false';

// Handle slider control values with defaults
$speed = isset($settings['speed']) ? $settings['speed'] : 900;
$centered_slides = isset($settings['centered_slides']) && $settings['centered_slides'] === 'yes' ? 'true' : 'false';
$autoplay_delay = isset($settings['autoplay_delay']) ? $settings['autoplay_delay'] : 3000;
$free_mode = isset($settings['free_mode']) && $settings['free_mode'] === 'yes' ? 'true' : 'false';
$slides_per_view = isset($settings['slides_per_view']) ? $settings['slides_per_view'] : 1;
$space_between = isset($settings['space_between']) ? $settings['space_between'] : 0;
$slides_per_view_tablet = isset($settings['slides_per_view_tablet']) ? $settings['slides_per_view_tablet'] : 1;
$slides_per_view_mobile = isset($settings['slides_per_view_mobile']) ? $settings['slides_per_view_mobile'] : 1;
$pagination = isset($settings['pagination']) ? $settings['pagination'] : 'none';
$effect = isset($settings['effect']) ? $settings['effect'] : 'slide';
$cube_effect_shadow = isset($settings['cube_effect_shadow']) && $settings['cube_effect_shadow'] === 'yes' ? 'true' : 'false';

// Handle coverflow settings with proper null checks
$coverflow_rotate = 50; // default value
if (isset($settings['coverflow_rotate']) && is_array($settings['coverflow_rotate']) && isset($settings['coverflow_rotate']['size'])) {
    $coverflow_rotate = $settings['coverflow_rotate']['size'];
}

$coverflow_stretch = 0; // default value
if (isset($settings['coverflow_stretch']) && is_array($settings['coverflow_stretch']) && isset($settings['coverflow_stretch']['size'])) {
    $coverflow_stretch = $settings['coverflow_stretch']['size'];
}

$slider_data_attributes = sprintf(
    'data-loop="%s" 
    data-autoplay="%s" 
    data-speed="%s" 
    data-centered-slides="%s" 
    data-autoplay-delay="%s" 
    data-free-mode="%s" 
    data-slides-per-view="%s" 
    data-space-between="%s" 
    data-slides-per-view-tablet="%s" 
    data-slides-per-view-mobile="%s" 
    data-pagination="%s" 
    data-navigation="%s"
    data-effect="%s"
    data-cube-shadow="%s"
    data-coverflow-rotate="%s"
    data-coverflow-stretch="%s"',
    $data_loop,
    $data_autoplay,
    esc_attr($speed),
    $centered_slides,
    esc_attr($autoplay_delay),
    $free_mode,
    esc_attr($slides_per_view),
    esc_attr($space_between),
    esc_attr($slides_per_view_tablet),
    esc_attr($slides_per_view_mobile),
    esc_attr($pagination),
    $data_navigation,
    esc_attr($effect),
    $cube_effect_shadow,
    esc_attr($coverflow_rotate),
    esc_attr($coverflow_stretch)
);