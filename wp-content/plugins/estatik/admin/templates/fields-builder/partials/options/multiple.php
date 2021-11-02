<?php

$values = ! empty( $instance['values'] ) ? $instance['values'] : array();
$iterator = new ArrayIterator( $values );

do{ ?>
    <div class="es-clone__wrap">
        <div class="es-field">
            <div class="es-field__label"></div>
            <div class="es-field__content">
                <a href="#" class="clone"><i class="fa fa-plus" aria-hidden="true"></i></a>
                <a href="#" class="drag js-es__available-tooltipster--drag"><i class="fa fa-arrows" aria-hidden="true"></i></a>
                <input placeholder="<?php _e( '-- Input value --', 'es-plugin' ); ?>"
                       type="text" name="fbuilder[values][]" value="<?php echo esc_attr( stripslashes( $values[ $iterator->key() ] ) ); ?>"/>
                <a href="#" class="delete"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
<?php $iterator->next(); } while( $iterator->valid() );

// Field rets support input.
echo Es_Html_Helper::render_settings_field(__( 'Multiple', 'es-plugin' ) . ' <i class="fa fa-info-circle js-es__available-tooltipster--multiple" aria-hidden="true"></i>', 'fbuilder[options][multiple]', 'checkbox', array(
	'class' => 'es-switch-input',
	'value' => 1,
	'checked' => true == (bool) Es_FBuilder_Helper::get_options_value( $instance, 'multiple' ),
) );
