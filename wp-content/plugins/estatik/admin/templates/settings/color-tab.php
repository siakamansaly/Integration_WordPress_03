<?php

/**
 * @var Es_Settings_Container $es_settings
 */

echo Es_Html_Helper::render_settings_field( __( 'Main color', 'es-plugin' ), 'es_settings[main_color]', 'text', array(
    'value' => $es_settings->main_color,
    'data-tooltipster-content' => __( 'Select color for Details and Search buttons', 'es-plugin' ),
    'class' => 'js-es-color-picker',
    'data-default-color' => $es_settings->get_default_value( 'main_color' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Secondary color', 'es-plugin' ), 'es_settings[secondary_color]', 'text', array(
    'value' => $es_settings->secondary_color,
    'data-tooltipster-content' => __( 'Select color for widgets background and other grey elements', 'es-plugin' ),
    'class' => 'js-es-color-picker',
    'data-default-color' => $es_settings->get_default_value( 'secondary_color' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Reset button color', 'es-plugin' ), 'es_settings[reset_button_color]', 'text', array(
    'value' => $es_settings->reset_button_color,
    'data-tooltipster-content' => __( 'Select color for reset button of search widget', 'es-plugin' ),
    'class' => 'js-es-color-picker',
    'data-default-color' => $es_settings->get_default_value( 'reset_button_color' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Frame color', 'es-plugin' ), 'es_settings[frame_color]', 'text', array(
    'value' => $es_settings->frame_color,
    'data-tooltipster-content' => __( 'Change color for listing box border appearing when hovering it', 'es-plugin' ),
    'class' => 'js-es-color-picker',
    'data-default-color' => $es_settings->get_default_value( 'frame_color' ),
) );
