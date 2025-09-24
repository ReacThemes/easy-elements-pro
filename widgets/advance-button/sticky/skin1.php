<?php defined( 'ABSPATH' ) || die(); ?>

<a href="<?php echo esc_url($link); ?>" <?php echo $is_external . $nofollow; ?> data-sticky="<?php echo esc_attr($sticky); ?>" class="eel-advance-button eel-sticky-w eel-style-1">
    <div class="eel-advance-button-text eel-sticky-t">     
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
    </div>
</a>