<?php defined( 'ABSPATH' ) || die(); ?>
<a href="<?php echo esc_url($link); ?>" <?php echo wp_kses_post($is_external); ?> class="eel-advance-button eel-btn-letter-effect-tw" data-text="<?php echo esc_attr($settings['button_text']); ?>">
    <span><?php echo esc_html( $settings['button_text'] ); ?> </span>
</a>