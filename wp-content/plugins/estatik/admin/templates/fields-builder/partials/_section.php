<?php

/**
 * @var array $field
 * @var string $field_key
 */

?>

<li data-machine-name="<?php echo $key; ?>">
	<?php if ( isset( $section['sortable'] ) && $section['sortable'] === false ) : ?><?php else : ?>
		<a href="#" class="js-es__available-tooltipster--drag"><i class="fa fa-arrows" aria-hidden="true"></i></a>
	<?php endif; ?>
	<?php echo $section['label']; ?>
	<?php if ( ! empty( $section['fbuilder'] ) ) : ?>
		<a href="<?php echo Es_FBuilder_Helper::get_section_delete_link( $section['id'] ); ?>" class="es-manage-field__link"
		   title="<?php _e( 'Remove section', 'es-plugin' ); ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></i></a>

		<a href="<?php echo Es_FBuilder_Helper::get_section_edit_link( $section['id'] ); ?>" class="es-manage-field__link"
		   title="<?php _e( 'Edit Section', 'es-plugin' ); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
	<?php endif; ?>
</li>
