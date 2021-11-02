<div class="es-data-manager-item" id="<?php echo $object->_options['id']; ?>">
	<form method="post">
		<?php if ( ! empty( $object->_options['label'] ) ): ?>
			<h3><?php echo $object->_options['label']; ?></h3>
		<?php endif; ?>

		<div class="es-dm-search">
			<input type="text" class="js-search-input" data-search-selector="#<?php echo $object->_options['id']; ?> li" placeholder="<?php _e( 'Search', 'es-plugin' ); ?>">
		</div>

		<ul>
			<?php if ( $items = $object->getItems() ) : ?>
				<?php foreach ( $items as $key => $label ) : ?>
                    <li><b><?php echo $label; ?></b> (ID: <?php echo $key; ?>)
						<a href="#" data-action="es_dm_remove_address" class="es-item-remove js-item-remove" data-id="<?php echo esc_attr( $key ); ?>"><span class="es-sprite es-sprite-close"></span></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>

		<span class="es-data-manager-item-msg"></span>
	</form>
</div>
