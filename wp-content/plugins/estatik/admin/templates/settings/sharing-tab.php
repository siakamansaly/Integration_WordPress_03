<?php

echo Es_Html_Helper::render_settings_field( '<i class="fa fa-twitter" aria-hidden="true"></i>' . __( 'Twitter', 'es-plugin' ), 'es_settings[share_twitter]', 'checkbox', array(
    'checked' => (bool) $es_settings->share_twitter ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( '<i class="fa fa-facebook" aria-hidden="true"></i>' . __( 'Facebook', 'es-plugin' ), 'es_settings[share_facebook]', 'checkbox', array(
    'checked' => (bool) $es_settings->share_facebook ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( '<i class="fa fa-google-plus" aria-hidden="true"></i>' . __( 'Google+', 'es-plugin' ), 'es_settings[share_google_plus]', 'checkbox', array(
    'checked' => (bool) $es_settings->share_google_plus ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( '<i class="fa fa-linkedin" aria-hidden="true"></i>' . __( 'LinkedIn', 'es-plugin' ), 'es_settings[share_linkedin]', 'checkbox', array(
    'checked' => (bool) $es_settings->share_linkedin ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>' . __( 'PDF flyer', 'es-plugin' ), 'es_settings[use_pdf]', 'checkbox', array(
    'checked' => (bool) $es_settings->use_pdf ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
    'disabled' => 'disabled',
) ); ?>

<p><?php echo sprintf( wp_kses( __( 'PDF Feature is available in <a href="%s" target="_blank">Estatik Pro</a> version only.', 'es-plugin' ), array(
        'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://estatik.net/product/estatik-professional/' ) ); ?></p>