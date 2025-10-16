<?php
static $instance = 0;
$instance++;
$unique_id = 'swiper-' . $instance;
$settings = $this->get_settings_for_display();
$data_loop = $settings['loop'] === 'yes' ? 'true' : 'false';
$data_autoplay = ($settings['autoplay'] === 'yes' || $settings['autoplay'] === true) ? 'true' : 'false';
$data_navigation = ($settings['navigation'] === 'yes' || $settings['navigation'] === true) ? 'true' : 'false';

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
    data-mousewheel="%s"',
    esc_attr($settings['loop']) === 'yes' ? 'true' : 'false',
    esc_attr($data_autoplay),
    esc_attr($settings['speed']),
    $settings['centered_slides'] === 'yes' ? 'true' : 'false',
    esc_attr($settings['autoplay_delay']),
    $settings['free_mode'] === 'yes' ? 'true' : 'false',
    esc_attr($settings['slides_per_view']),
    esc_attr($settings['space_between']),
    esc_attr($settings['slides_per_view_tablet']),
    esc_attr($settings['slides_per_view_mobile']),
    esc_attr($settings['pagination']),
    esc_attr($data_navigation),
    esc_attr($settings['effect']),
    esc_attr($settings['mousewheel']) === 'yes' ? 'true' : 'false'
);