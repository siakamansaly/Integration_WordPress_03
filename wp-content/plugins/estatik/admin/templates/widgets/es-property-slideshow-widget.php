<?php

/**
 * Search widget.
 *
 * @var array $instance
 * @var array $args
 * @var array $fields
 * @var Es_Property_Slideshow_Widget $this
 */

echo $args['before_widget']; ?>

	<div class="es-widget-wrapper es-map-property-layout-<?php echo $instance['layout']; ?>">

		<?php if ( ! empty( $instance['title'] ) ) : ?>
			<?php echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; ?>
		<?php endif; ?>

		<?php do_action( 'es_before_property_slideshow_widget' ); ?>

		<?php $slideshow = new Es_Property_Slideshow_Shortcode();
		echo $slideshow->build( $instance ); ?>

		<?php do_action( 'es_after_property_slideshow_widget' ); ?>
	</div>
<?php echo $args['after_widget'];
