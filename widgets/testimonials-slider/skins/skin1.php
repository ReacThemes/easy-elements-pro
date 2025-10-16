<div class="ee--tstml-wrap-skin-style-two">
    <div class="eel-author-wrap">
		<?php
        if ( $settings['show_image'] === 'yes' && $image_data ) : ?>
        <div class="eel-picture">            
            <img
           	src="<?php echo esc_url( $image_data[0] ); ?>"
           	width="<?php echo esc_attr( $image_data[1] ); ?>"
           	height="<?php echo esc_attr( $image_data[2] ); ?>"
           	alt="<?php echo esc_attr( $alt ); ?>"
           	title="<?php echo esc_attr( $title ); ?>"
           	loading="lazy"
           	decoding="async" fetchpriority="low">	        
        </div>
        <?php endif; $image_data = ($image_data) ? '' : 'eel-not-add-image' ; ?>

        <div class="eel-content-wrap <?php echo esc_attr($image_data); ?>">          
            <?php if ( ! empty( $item['description'] ) ) : ?>
                <div class="eel-description"><?php echo wp_kses_post( $item['description'] ); ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $item['quote_icon']['value'] ) ) : ?>
                <div class="eel-quote" aria-hidden="true">
                    <?php \Elementor\Icons_Manager::render_icon( $item['quote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
            <?php endif; ?>
            <div class="author-wrap">
	            <?php if ( ! empty( $item['name'] ) ) : ?>
	                <div class="eel-name">
	                	<?php echo esc_html( $item['name'] ); ?>
	                	<?php if ( ! empty( $item['designation'] ) ) : ?>
	                	    <em class="eel-designation"><?php echo esc_html( $item['designation'] ); ?></em>
	                	<?php endif; ?>	                		
	                </div>
	            <?php endif; ?>            

				<?php if ( ! empty( $item['rating'] ) && $settings['show_rating'] === 'yes' ) :  ?>
	                <div class="eel-rating" aria-label="Rating: <?php echo intval( $item['rating'] ); ?> out of 5">
	                    <?php
	                    $rating = intval( $item['rating'] );
	                    for ( $i = 1; $i <= 5; $i++ ) {
	                        echo '<span class="star' . ( $i <= $rating ? ' filled' : '' ) . '">' . ( $i <= $rating ? '★' : '☆' ) . '</span>';
	                    }
	                    ?>
	                </div>
	            <?php endif; ?>
	        </div>
        </div>
    </div>
</div>
