<?php

echo Es_Html_Helper::render_settings_field( __( 'Show search range', 'es-plugin' ), 'fbuilder[search_range_mode]', 'checkbox', array(
	'class' => 'es-switch-input',
	'value' => 1,
	'checked' => true == (bool) Es_FBuilder_Helper::get_settings_value( $instance, 'search_range_mode' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Field range mode', 'es-plugin' ), 'fbuilder[range_mode]', 'checkbox', array(
	'class' => 'es-switch-input',
	'value' => 1,
	'checked' => true == (bool) Es_FBuilder_Helper::get_settings_value( $instance, 'range_mode' ),
) );
