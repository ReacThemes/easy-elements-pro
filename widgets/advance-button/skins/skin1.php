<?php defined( 'ABSPATH' ) || die(); ?>
<a href="<?php echo esc_url($link); ?>" <?php echo wp_kses_post($is_external); ?> class="eel-advance-button eel-default">
    <span class="eel-advance-btn-wrap"> 
        <?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'before'): ?>
            <span class="eel-button-icon-before-advance">
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </span>
        <?php endif; ?>
    
        <?php echo esc_html( $settings['button_text'] ); ?> 

        <?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'after'): ?>
            <span class="eel-button-icon-after-advance">
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </span>
        <?php endif; ?>
    </span>
</a>