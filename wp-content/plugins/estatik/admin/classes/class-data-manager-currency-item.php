<?php

/**
 * Class Es_Data_Manager_Currency_Item.
 */
class Es_Data_Manager_Currency_Item extends Es_Data_Manager_Item
{
    /**
     * @var string Data manager item template path.
     */
    protected $_template_path = 'data-manager/currency-item.php';

    /**
     * @return void
     */
    public static function save()
    {
        $nonce = 'es_add_data_manager_currency';

        // If ajax request is valid.
        if ( check_ajax_referer( $nonce, $nonce ) && current_user_can( 'es_save_dm_item' ) ) {
            // Get available values of the item.

	        $current_option_name = sanitize_key( $_POST['current_option_name'] );
	        $currency_label = sanitize_text_field( $_POST['currency_label'] );
	        $currency_code = sanitize_text_field( $_POST['currency_code'] );
            $values = Es_Settings_Container::get_setting_values( $current_option_name );

            // If items exists.
            if ( ! empty( $values[ $currency_code ] ) ) {
                $response = array( 'message' => __( 'Currency already exists.', 'es-plugin' ), 'status' => 'warning' );

                // Add new item to the option.
            } else if ( $currency_code && $currency_label ) {
                $values = get_option( sanitize_key( $_POST['option_storage_name'] ), array() );
                $values[ $currency_code ] = $currency_label;

                $values = array_filter( $values );
                $sanitized_values = array();

                if ( $values ) {
                	 foreach ( $values as $key => $value ) {
                	 	 $key = sanitize_text_field( $key );
		                 $sanitized_values[ $key ] = sanitize_text_field( $value );
	                 }
                }

                update_option( sanitize_key( $_POST['option_storage_name'] ), $sanitized_values );

                $response = array(
                    'message' => __( 'Item is successfully created.', 'es-plugin' ),
                    'status' => 'success',
                    'item' => array_search( $currency_label, $values )
                );
            } else {
                $response = array( 'message' => __( 'Please, fill the currency code and label.', 'es-plugin' ), 'status' => 'error' );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }
}
