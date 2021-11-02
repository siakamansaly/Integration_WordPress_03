<?php do_action( 'es_settings_before_google_services_tab' ); ?>

<?php echo Es_Html_Helper::render_settings_field( __( 'Google map API key', 'es-plugin' ), 'es_settings[google_api_key]', 'text', array(
    'value' => $es_settings->google_api_key,
) );

if ( $data = $es_settings::get_setting_values( 'recaptcha_version' ) ) : $name = 'recaptcha_version'; $label = __( 'Recaptcha API Version', 'es-plugin' ) ?>
    <?php include( 'fields/radio-list.php' ); ?>
<?php endif;

echo Es_Html_Helper::render_settings_field( __( 'Google Recaptcha SiteKey', 'es-plugin' ), 'es_settings[recaptcha_site_key]', 'text', array(
	'value' => $es_settings->recaptcha_site_key,
) );

echo Es_Html_Helper::render_settings_field( __( 'Google Recaptcha SecretKey', 'es-plugin' ), 'es_settings[recaptcha_secret_key]', 'text', array(
	'value' => $es_settings->recaptcha_secret_key,
) ); ?>

<?php do_action( 'es_settings_after_google_services_tab' ); ?>
