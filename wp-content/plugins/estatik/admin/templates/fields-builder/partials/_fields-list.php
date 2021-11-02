<?php

/**
 * @var $entity string
 */

global $es_settings;

wp_cache_delete( 'property_fields', 'ES_PROPERTY_FIELDS' );

$removed_fields = $es_settings->property_removed_fields ? $es_settings->property_removed_fields : array();

if ( $sections = Es_FBuilder_Helper::get_sections( $entity, true, true ) ) : ; ?>
    <div>
		<?php foreach ( $sections as $key => $section ) : ?>

            <div data-machine-name="<?php echo $key; ?>">
                <h1><?php echo $section[ 'label' ]; ?></h1>
                <ul class="es-list es-list__styled es-list__sortable">
					<?php if ( $fields = Es_FBuilder_Helper::get_entity_fields( $entity, $key ) ) : ?>
						<?php foreach ( $fields as $field_key => $field ) :
							$is_removed_field = in_array( $field_key, $removed_fields ); ?>

							<?php if ( empty( $field['section'] ) || $field['section'] != $key ) continue; ?>
							<?php require ( Es_Fields_Builder_Page::get_template_path( 'partials/_field' ) ); ?>
						<?php endforeach; ?>
					<?php endif; ?>
                </ul>
            </div>
		<?php endforeach; ?>
    </div>
<?php endif;
