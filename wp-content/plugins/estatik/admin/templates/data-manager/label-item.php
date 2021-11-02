<?php

/**
 * @var stdClass $taxonomy
 * @var WP_Term[] $terms
 */

?>

<div class="es-data-manager-item item-<?php echo $this->_taxonomy->name; ?>">
	<form method="post" data-remove-action="es_ajax_data_manager_remove_term">
		<h3><?php _e( $this->_taxonomy->label, 'es-plugin' ); ?></h3>
		<ul class="es-dm-labels__list">
			<?php if ( $terms = $this->getItems() ) : ?>
				<?php foreach ( $terms as $term ) : $term_color = get_term_meta( $term->term_id, 'es_color', true ) ?>
					<li><label><?php echo __( $term->name, 'es-plugin' ); ?></label>
                        <input value="<?php echo $term_color; ?>" data-id="<?php echo $term->term_id; ?>" type="color" class="js-color-item" data-action="es_ajax_data_manager_label_color" name="es_label_color[<?php echo $term->term_id; ?>]"/>

						<?php if ( $term->slug != 'featured' ) : ?>
							<a href="#" class="es-item-remove js-item-remove"
							   data-id="<?php echo $term->term_id; ?>"
							   data-action="es_ajax_data_manager_remove_term">
                                <i class="fa fa-trash"></i>
                            </a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>

		<span class="es-data-manager-item-msg"></span>

		<div class="es-data-manager-item-nav">
			<label><input type="text" name="item_name" required placeholder="<?php _e( 'text/number', 'es-plugin' ); ?>"/></label>
			<a href="" class="es-button-add-item es-data-manager-submit"><?php _e( 'Add new item', 'es-plugin' ); ?></a>
		</div>

		<input type="hidden" name="taxonomy" value="<?php echo $this->_taxonomy->name; ?>"/>
		<?php wp_nonce_field( 'es_add_data_manager_label', 'es_add_data_manager_label' ); ?>
		<input type="hidden" name="action" value="es_ajax_data_manager_add_label"/>
	</form>
</div>
