<?php

/**
 * @file content-archive.php
 * Archive single property template.
 *
 * @var Es_Property $es_property
 * @var Es_Settings_Container $es_settings
 */

global $es_settings;
$es_property = es_get_property( get_the_ID() );
$area = es_the_formatted_area( '', '', false);
$bedrooms = es_get_the_formatted_bedrooms();
$bathrooms = es_get_the_formatted_bathrooms(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'properties' ); ?>>
    <div class="es-property-inner">
        <div class="es-property-thumbnail">
            <div class="es-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php if ( ! empty( $es_property->gallery ) ) : ?>
                        <?php es_the_post_thumbnail( 'es-image-size-archive' ); ?>
                    <?php elseif ( $image = es_get_default_thumbnail( 'es-image-size-archive' ) ) : ?>
                        <?php echo $image; ?>
                    <?php else: ?>
                        <div class="es-thumbnail-none">
                            <?php if ( ! $es_property->get_labels_list() ) : ?>
                                <?php _e( 'No image', 'es-plugin' ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $es_settings->show_labels ) : ?>
                        <ul class="es-property-label-wrap">
                            <?php foreach ( $es_property->get_labels_list() as $label ) : $value = $es_property->{$label->slug}; ?>
                                <?php if ( ! empty( $value ) ) : ?>
                                    <li class="es-property-label es-property-label-<?php echo $label->slug; ?>"
                                        style="color:<?php echo es_get_the_label_color( $label->term_id ); ?>"><?php _e( $label->name, 'es-plugin' ) ; ?></li><br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if ( ! empty( $es_property->gallery ) && is_array( $es_property->gallery ) ) : ?>
                        <div class="es-thumbnail-bottom"><?php echo count( $es_property->gallery ); ?></div>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <div class="es-property-info">

            <div class="es-row-view">
                <h2>
                    <a href="<?php the_permalink(); ?>"><?php es_the_title(); ?></a>
                    <?php es_the_formatted_price(); ?>
                </h2>
            </div>

            <div class="es-col-view">
                <h2>
                    <a class="es-property-link" href="<?php the_permalink(); ?>"><?php es_the_title(); ?></a>
	                <?php do_action( 'es_wishlist_add_button', get_the_ID() ); ?>
                </h2>
	            <?php es_the_formatted_price(); ?>
            </div>

	        <?php do_action( 'es_property_before_content' ); ?>

            <div class="es-property-content">
                <div class="es-property-excerpt">
		            <?php the_excerpt() ?>
                </div>
                <div class="es-property-wishlist">
		            <?php do_action( 'es_wishlist_add_button', get_the_ID() ); ?>
                </div>
            </div>

            <?php do_action( 'es_property_after_content' ); ?>

            <div class="es-bottom-info">
                <?php do_action( 'es_property_fields_icons', get_the_ID() ); ?>

                <div class="es-details-wrap">
                    <div class="es-details-flex">
                        <?php if ( ! empty( $es_property->latitude ) && ! empty( $es_settings->google_api_key ) ) : ?>
                            <div class="es-map-link-wrap">
                                <a href="#" class="es-map-view-link es-hover-show" data-longitude="<?php echo $es_property->longitude; ?>"
                                   data-latitude="<?php echo $es_property->latitude; ?>"><?php _e( 'View on map', 'es-plugin' ); ?></a>
                            </div>
                        <?php endif; ?>
                        <span class="es-read-wrap">
                            <a href="<?php the_permalink(); ?>" class="es-button es-button-orange es-hover-show es-read"><?php _e( 'Details', 'es-plugin' ); ?></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
