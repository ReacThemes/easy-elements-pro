<?php defined( 'ABSPATH' ) || die(); ?>
<a href="<?php echo esc_url($link); ?>" <?php echo wp_kses_post($is_external); ?> class="eel-advance-combine-icon-btn">
    <div class="eel-advance-combine-btn-wrap"> 
        <?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'before'): ?>
            <span class="eel-button-icon-before-advance eel-combine-icon-btn-icon">
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true','class' => 'eel-combine-btn-normal-icon' ] ); ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true','class' => 'eel-combine-btn-hover-icon' ] ); ?>
            </span>
        <?php endif; ?>
        <div class="eel-combine-icon-btn-text">
            <span class="eel-combine-btn-normal-text"><?php echo esc_html( $settings['button_text'] ); ?> </span>
            <span class="eel-combine-btn-hover-text"><?php echo esc_html( $settings['button_text'] ); ?> </span>
        </div>
        <?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'after'): ?>
            <span class="eel-button-icon-after-advance eel-combine-icon-btn-icon">
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true','class' => 'eel-combine-btn-normal-icon' ] ); ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true','class' => 'eel-combine-btn-hover-icon' ] ); ?>
            </span>
        <?php endif; ?>
    </div>
</a>