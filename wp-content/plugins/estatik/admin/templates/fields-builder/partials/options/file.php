<?php

// Field rets support input.
echo Es_Html_Helper::render_settings_field(__( 'Mime types', 'es-plugin' ), 'fbuilder[options][accept]', 'text', array(
	'value' => Es_FBuilder_Helper::get_options_value( $instance, 'min', 'image/*,application/pdf' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Show as thumbnail', 'es-plugin' ), 'fbuilder[show_thumbnail]', 'checkbox', array(
	'class' => 'es-switch-input',
	'value' => 1,
	'checked' => true == (bool) Es_FBuilder_Helper::get_settings_value( $instance, 'show_thumbnail' ),
) );
