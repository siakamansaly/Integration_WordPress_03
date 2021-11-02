<?php

/**
 * Class Es_Settings_Page
 */
class Es_Settings_Page
{
    /**
     * Render settings page content.
     *
     * @return void
     */
    public static function render() {
        es_load_template( 'settings/settings.php', 'admin', 'es_settings_template_path' );
    }

    /**
     * Return tabs of the settings page.
     *
     * @return array
     */
    public static function get_tabs() {
        return apply_filters( 'es_settings_get_tabs', array(
            'general' => array(
                'label' => __( 'General', 'es-plugin' ),
                'template' => es_locate_template( 'settings/general-tab.php', 'admin' ),
            ),
            'google-services' => array(
	            'label' => __( 'Google APIs', 'es-plugin' ),
                'template' => es_locate_template( 'settings/google-services.php', 'admin' ),
            ),
            'addresses' => array(
                'label' => __( 'Address fields', 'es-plugin' ),
                'template' => es_locate_template( 'settings/addresses-tab.php', 'admin' ),
            ),
            'layouts' => array(
                'label' => __( 'Layouts', 'es-plugin' ),
                'template' => es_locate_template( 'settings/layouts-tab.php', 'admin' ),
            ),
            'currency' => array(
                'label' => __( 'Currency', 'es-plugin' ),
                'template' => es_locate_template( 'settings/currency-tab.php', 'admin' ),
            ),
            'emails' => array(
	            'label' => __( 'Emails', 'es-plugin' ),
                'template' => es_locate_template( 'settings/email-tab.php', 'admin' ),
            ),
            'sharing' => array(
                'label' => __( 'Sharing', 'es-plugin' ),
                'template' => es_locate_template( 'settings/sharing-tab.php', 'admin' ),
            ),
            'color' => array(
                'label' => __( 'Color', 'es-plugin' ),
                'template' => es_locate_template( 'settings/color-tab.php', 'admin' ),
            ),
            'account' => array(
	            'label' => __( 'Account', 'es-plugin' ),
                'template' => es_locate_template( 'settings/account-tab.php', 'admin' ),
            ),
            'seo' => array(
	            'label' => __( 'SEO', 'es-plugin' ),
                'template' => es_locate_template( 'settings/seo-tab.php', 'admin' ),
            ),
        ) );
    }

    /**
     * Save settings action.
     *
     * @return void
     */
    public static function save()
    {
    	$nonce_name = 'es_save_settings';
	    $nonce = sanitize_key( filter_input( INPUT_POST, $nonce_name ) );

        if ( $nonce && wp_verify_nonce( $nonce, $nonce_name ) && ! empty( $_POST['es_settings'] ) ) {

            /** @var Es_Settings_Container $es_settings */
            global $es_settings;

            // Filtering and preparing data for save.
            $data = apply_filters( 'es_before_save_settings_data', $_POST['es_settings'] );

            // Before save action.
            do_action( 'es_before_settings_save', $data );

            $es_settings->save( $data );

            // After save action.
            do_action( 'es_after_settings_save', $data );
        }
    }
}
