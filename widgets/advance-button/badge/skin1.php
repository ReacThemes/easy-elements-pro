<?php defined( 'ABSPATH' ) || die(); ?>
<a href="<?php echo esc_url($link); ?>" <?php echo wp_kses_post($is_external); ?> class="eel-advance-button eel-advance-button-badge">
    <span>
        <?php echo esc_html( $settings['button_text'] ); ?> 
        <span class="eel-badge"><?php echo esc_html( $settings['button_badge_text'] ); ?> </span>
    </span>
</a>