<div class="es-property-fields">

    <?php do_action( 'es_single_share' ); ?>

    <ul>
        <?php if ( $fields = Es_Property_Single_Page::get_single_fields_data() ) : ?>
            <?php foreach ( $fields as $field ) : ?>
                <?php if ( ! empty( $field[ key( $field ) ] && ! is_wp_error( $field[ key( $field ) ] ) ) || ( isset( $field[ key( $field ) ] ) && ! is_wp_error( $field[ key( $field ) ] )&& strlen( $field[ key( $field ) ] ) ) ) : ?>
                    <?php $label = key( $field );
                    if ( is_array( $field[ key( $field ) ] ) ) {
                        foreach ( $field[ key( $field ) ] as $index => $f_label ) $field[ key( $field ) ][ $index ] = __( $f_label, 'es-plugin' );
                    } ?>
                    <li><strong><?php _e( $label, 'es-plugin' ); ?><span class="es-field-delimiter">:</span> </strong>
                        <?php $value = is_array( $field[ key( $field ) ] ) ? implode( ', ', $field[ key( $field ) ] ) : $field[ key( $field ) ]; ?>
	                    <?php _e( $value, 'es-plugin' ) ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
