<?php do_action( 'es_settings_before_account_tab' );

echo Es_Html_Helper::render_settings_field( __( 'Enable Buyers', 'es-plugin' ), 'es_settings[buyers_enabled]', 'checkbox', array(
	'checked' => (bool) $es_settings->buyers_enabled ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Registration page', 'es-plugin' ), 'es_settings[registration_page_id]', 'list', array(
	'value' => $es_settings->registration_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Login page', 'es-plugin' ), 'es_settings[login_page_id]', 'list', array(
	'value' => $es_settings->login_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Reset password page', 'es-plugin' ), 'es_settings[reset_password_page_id]', 'list', array(
	'value' => $es_settings->reset_password_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Profile page', 'es-plugin' ), 'es_settings[user_profile_page_id]', 'list', array(
    'value' => $es_settings->user_profile_page_id,
    'values' => $list_pages,
) );

do_action( 'es_settings_after_account_tab' );

echo Es_Html_Helper::render_settings_field( __( 'Enable facebook auth', 'es-plugin' ), 'es_settings[enable_facebook_auth]', 'checkbox', array(
    'checked' => (bool) $es_settings->enable_facebook_auth ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
    'data-toggle-container' => '#es-facebook-container'
) ); ?>

<div id="es-facebook-container">
    <?php echo Es_Html_Helper::render_settings_field( __( 'Facebook app id', 'es-plugin' ), 'es_settings[facebook_app_id]', 'text', array(
        'value' => $es_settings->facebook_app_id,
    ) );

    echo Es_Html_Helper::render_settings_field( __( 'Facebook app secret', 'es-plugin' ), 'es_settings[facebook_app_secret]', 'text', array(
        'value' => $es_settings->facebook_app_secret,
    ) ); ?>
</div>

<?php echo Es_Html_Helper::render_settings_field( __( 'Enable google auth', 'es-plugin' ), 'es_settings[enable_google_auth]', 'checkbox', array(
    'checked' => (bool) $es_settings->enable_google_auth ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
    'data-toggle-container' => '#es-google-container'
) ); ?>

<div id="es-google-container">
    <?php echo Es_Html_Helper::render_settings_field( __( 'Google client key', 'es-plugin' ), 'es_settings[google_client_key]', 'text', array(
        'value' => $es_settings->google_client_key,
    ) );

    echo Es_Html_Helper::render_settings_field( __( 'Google client secret', 'es-plugin' ), 'es_settings[google_client_secret]', 'text', array(
        'value' => $es_settings->google_client_secret,
    ) ); ?>
</div>
