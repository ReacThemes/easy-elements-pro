<?php defined( 'ABSPATH' ) || die(); ?>
<a href="<?php echo esc_url($link); ?>" <?php echo wp_kses_post($is_external); ?> class="eel-advance-button eel-advance-button-magnetic" data-duration ="<?php echo esc_attr($settings['magnetic_btn_ani_duration'] )?>">
    <span class="eel-advance-magnetic-btn"> 
        <?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'before'): ?>
            <span class="eel-button-icon-before-advance">
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </span>
        <?php endif; ?>
        <span class="eel-magnetic-btn-overly"></span>
        <span class="eel-magnetic-btn-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
        <?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'after'): ?>
            <span class="eel-button-icon-after-advance">
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </span>
        <?php endif; ?>
    </span> 
</a>