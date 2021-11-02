<?php global $es_settings;
$es_property = es_get_property( get_the_ID() ); ?>
<style>.es-gallery br{display: none;}</style>
<div class="es-gallery">
	<?php do_action( 'es_property_gallery_before_inner', $es_property ); ?>
	<?php if ( $gallery = $es_property->gallery ) : ?>
        <div class="es-gallery-inner">

	        <?php if ( $es_settings->show_labels && $es_settings->show_labels_on_single_page ) : ?>
                <ul class="es-property-label-wrap">
			        <?php foreach ( $es_property->get_labels_list() as $label ) : $value = $es_property->{$label->slug}; ?>
				        <?php if ( ! empty( $value ) ) : ?>
                            <li class="es-property-label es-property-label-<?php echo $label->slug; ?>"
                                style="color:<?php echo es_get_the_label_color( $label->term_id ); ?>"><?php _e( $label->name, 'es-plugin' ) ; ?></li><br>
				        <?php endif; ?>
			        <?php endforeach; ?>
                </ul>
	        <?php endif; ?>

            <div class="es-gallery-image">
				<?php foreach ( $gallery as $value ) : ?>
                    <div>
                        <a href="<?php echo wp_get_attachment_image_url( $value, 'full' ); ?>">
							<?php echo wp_get_attachment_image( $value, 'es-image-size-archive' ); ?>
                        </a>
                    </div>
				<?php endforeach; ?>
            </div>

            <div class="es-gallery-image-pager-wrap">
                <a href="#" class="es-single-gallery-arrow es-single-gallery-slick-prev">1</a>
                <div class="es-gallery-image-pager">
					<?php foreach ( $gallery as $value ) : ?>
                        <div><?php echo wp_get_attachment_image( $value, 'thumbnail' ); ?></div>
					<?php endforeach; ?>
                </div>
                <a href="#" class="es-single-gallery-arrow es-single-gallery-slick-next">2</a>
            </div>
        </div>
	<?php elseif ( $image = es_get_default_thumbnail( 'es-image-size-archive' ) ): ?>
		<?php echo $image; ?>
	<?php endif; ?>
</div>
