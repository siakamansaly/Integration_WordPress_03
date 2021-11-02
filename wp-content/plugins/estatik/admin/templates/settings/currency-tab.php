<?php
/**
 * @var Es_Settings_Container $es_settings
 */
?>

<?php echo Es_Html_Helper::render_settings_field( __( 'Default currency', 'es-plugin' ), 'es_settings[currency]', 'list', array(
    'value' => $es_settings->currency,
    'values' => $es_settings::get_setting_values( 'currency' ),
    'placeholder' => __( 'Choose currency', 'es-plugin' ),
) ); ?>

<?php echo Es_Html_Helper::render_settings_field( __( 'Price format', 'es-plugin' ), 'es_settings[price_format]', 'list', array(
    'value' => $es_settings->price_format,
    'values' => $es_settings::get_setting_values( 'price_format' ),
) ); ?>

<?php if ( $data = $es_settings::get_setting_values( 'currency_position' ) ) : $name = 'currency_position'; $label = __( 'Currency sign place', 'es-plugin' ); ?>
    <?php include( 'fields/radio-list.php' ); ?>
<?php endif;
