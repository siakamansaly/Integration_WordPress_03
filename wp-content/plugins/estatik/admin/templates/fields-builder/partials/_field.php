<?php

/**
 * @var array $field
 * @var string $field_key
 * @var bool $is_removed_field
 */

?>

<li class="<?php echo $is_removed_field ? 'es-field__removed' : ''; ?>">
    <a href="#" class="js-es__available-tooltipster--drag"><i class="fa fa-arrows" aria-hidden="true"></i></a>
    <?php echo $field['label']; ?>
    <?php if ( ! empty( $field['fbuilder'] ) ) : ?>
        <a href="<?php echo Es_FBuilder_Helper::get_field_delete_link( $field['id'] ); ?>" class="es-manage-field__link"
           title="<?php _e( 'Remove field', 'es-plugin' ); ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></i></a>

        <a href="<?php echo Es_FBuilder_Helper::get_field_edit_link( $field['id'] ); ?>" class="es-manage-field__link"
           title="<?php _e( 'Edit field', 'es-plugin' ); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
    <?php else: ?>
        <?php if ( $is_removed_field ) : ?>
            <a href="<?php echo Es_FBuilder_Helper::get_field_restore_link( $field_key ); ?>" class="es-manage-field__link"
               title="<?php _e( 'Restore field', 'es-plugin' ); ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i></i></a>
        <?php else: ?>
            <a href="<?php echo Es_FBuilder_Helper::get_field_delete_link( $field_key, true ); ?>" class="es-manage-field__link"
               title="<?php _e( 'Remove field', 'es-plugin' ); ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></i></a>
        <?php endif; ?>
    <?php endif; ?>
</li>
