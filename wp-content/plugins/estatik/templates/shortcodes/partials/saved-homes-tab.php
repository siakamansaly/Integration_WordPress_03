<?php

/**
 * @var $properties WP_Query
 * @var $instance Es_Profile_Shortcode
 */
?>

<h2 class="es-profile__tab-title"><?php _e( 'Saved Homes', 'es-plugin' ); ?></h2>
<p class="es-profile__subtitle"><?php _e( 'Your saved homes can be found here. You can view details or delete your favourite listings.', 'es-plugin' ); ?></p>

<?php if ( $properties->have_posts() ) : ?>

	<?php do_action( "es_shortcode_before_" . $instance->get_shortcode_name() . "_loop" ); ?>

	<div class="es-wrap <?php echo get_option( 'template' ); ?>">
		<?php if ( ! empty( $atts['show_filter'] ) ) : ?>
			<?php do_action( 'es_archive_sorting_dropdown' ); ?>
		<?php endif; ?>
		<div class="es-listing es-layout-<?php echo $atts['layout']; ?>" data-layout="<?php echo $atts['layout']; ?>">
			<?php while ( $properties->have_posts() ) : $properties->the_post(); ?>
				<?php es_load_template( 'content-archive.php' ); ?>
			<?php endwhile; ?>
		</div>

		<?php echo es_the_pagination( $properties, array(
			'type' => 'list',
		) ); ?>
	</div>
	<?php do_action( "es_shortcode_after_" . $instance->get_shortcode_name() . "_loop" ); ?>
	<?php wp_reset_postdata(); ?>
<?php else: ?>
	<?php _e( 'Nothing to display', 'es-plugin' ); ?>
<?php endif;
