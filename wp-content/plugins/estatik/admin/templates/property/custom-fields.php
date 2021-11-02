<?php

/** @var Es_Property $property */ ?>

<?php if ( ! empty( $property ) && $custom_data = $property->get_custom_data() ): ?>
    <?php foreach ( $custom_data as $item ): ?>
        <div class="es-field es-field-custom">
            <div class="es-field__label"><?php echo key( $item ); ?></div>
                <div class="es-field__content">
                    <input type="text" name="es_custom_value[]" value="<?php echo $item[ key( $item ) ]; ?>"/>
                    <input type="hidden" name="es_custom_key[]" value="<?php echo key( $item ); ?>"/>
                    <a href="#" class="js-es-remove-custom"><span class="es-sprite es-sprite-close"></span></a>
                </div>
            </div>

    <?php endforeach; ?>
<?php endif; ?>

<div class="es-property-custom-wrap">
    <input type="text" placeholder="<?php _e( 'text/number', 'es-plugin' ); ?>" name="es-custom-field">
    <a href="#" class="es-button-add-item es-button-add-custom js-es-add-custom"><?php _e( 'Add new field', 'es-plugin' ); ?></a>
</div>
