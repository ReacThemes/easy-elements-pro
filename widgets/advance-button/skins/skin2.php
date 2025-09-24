<?php defined( 'ABSPATH' ) || die(); ?>

<a href="<?php echo esc_url($link); ?>" <?php echo $is_external . $nofollow; ?> 
   class="eel-advance-button eel-btn--effect-tw" 
   data-text="<?php echo esc_attr($settings['button_text']); ?>">
    <?php 
    $new_text = $settings['button_text'] ?? 'Sample';
    $letters = str_split($new_text);
    foreach ($letters as $letter) {
       if ($letter === ' ') {
           echo '<span>&nbsp;</span>';
       } else {
           echo '<span>' . esc_html($letter) . '</span>';
       }
    }
    ?>
</a>

