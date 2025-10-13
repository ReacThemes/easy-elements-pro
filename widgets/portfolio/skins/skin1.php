<div class="eel-portfolio-wrap-pro">
	<div class="eel-portfolio-item-pro pro-skin1">
		<div class="eel-portfolio-image">
			<?php foreach ( $portfolio_items as $index => $item ) :
				$image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'thumbnail', $settings );
				if ( ! $image_url ) {
					$image_url = $item['image']['url'];
				}
				
				// Hover uses main image
				$hover_image_url = $image_url;
				
				// Resolve link: prefer custom link on item, otherwise dynamic permalink
				$link_url = '';
				$link_is_external = false;
				$link_nofollow = false;
				if ( ! empty( $item['custom_link']['url'] ) ) {
					$link_url = $item['custom_link']['url'];
					$link_is_external = ! empty( $item['custom_link']['is_external'] );
					$link_nofollow = ! empty( $item['custom_link']['nofollow'] );
				} elseif ( isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) {
					$link_url = $item['permalink'];
				}
				$has_link = ! empty( $link_url );
			?>
			<?php if ( $has_link ) : ?>
				<a href="<?php echo esc_url( $link_url ); ?>" class="eel-portfolio-link"<?php echo $link_is_external ? ' target="_blank"' : ''; ?><?php echo $link_nofollow ? ' rel="nofollow"' : ''; ?>>
			<?php endif; ?>
				<img 
					src="<?php echo esc_url( $image_url ); ?>" 
					alt="<?php echo esc_attr( $item['title'] ); ?>"
					data-hover-src="<?php echo esc_url( $hover_image_url ); ?>"
					data-index="<?php echo esc_attr( $index ); ?>"
					class="eel-portfolio-img <?php echo $index === 0 ? 'active' : ''; ?>"
				>
			<?php if ( $has_link ) : ?>
				</a>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="eel-portfolio-content-pro">
			<?php foreach ( $portfolio_items as $index => $item ) :
				$image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'thumbnail', $settings );
				if ( ! $image_url ) {
					$image_url = $item['image']['url'];
				}
				
				// Resolve link: prefer custom link on item, otherwise dynamic permalink
				$link_url = '';
				$link_is_external = false;
				$link_nofollow = false;
				if ( ! empty( $item['custom_link']['url'] ) ) {
					$link_url = $item['custom_link']['url'];
					$link_is_external = ! empty( $item['custom_link']['is_external'] );
					$link_nofollow = ! empty( $item['custom_link']['nofollow'] );
				} elseif ( isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) {
					$link_url = $item['permalink'];
				}
				$has_link = ! empty( $link_url );
			?>
			<div class="eel-portfolio-info-pro" data-index="<?php echo esc_attr( $index ); ?>">
			<?php if ( $has_link ) : ?>
				<a href="<?php echo esc_url( $link_url ); ?>" class="eel-portfolio-title-link"<?php echo $link_is_external ? ' target="_blank"' : ''; ?><?php echo $link_nofollow ? ' rel="nofollow"' : ''; ?>>
			<?php endif; ?>
				
				<?php if ( ! empty( $item['category'] ) ) : ?>
					<span class="eel-portfolio-category-pro">
						<?php echo wp_kses_post( $item['category'] ); ?>
					</span>
				<?php endif; ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="eel-portfolio-title-pro">
					<?php 						
						echo wp_kses_post( $item['title'] ); 
						if ( 'yes' === $settings['icon_show'] ) {
							if ( ! empty( $item['selected_icon']['value'] ) ) {
								\Elementor\Icons_Manager::render_icon(
									$item['selected_icon'],
									[
										'aria-hidden' => 'true',
										'class'       => 'port-arro-pro',
									]
								);
							} else{
								echo '<i class="unicon-arrow-up-right"></i>';
							}
						}
					?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>					
			<?php if ( $has_link ) : ?>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $item['description'] ) ) : ?>
				<p class="eel-portfolio-desc"><?php echo wp_kses_post( $item['description'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $item['location'] ) ) : ?>
				<span class="eel-portfolio-location-pro">
					<i class="unicon-location"></i> <?php echo wp_kses_post( $item['location'] ); ?>
				</span>
			<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>