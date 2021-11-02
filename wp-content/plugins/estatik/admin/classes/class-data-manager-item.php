<?php

/**
 * Class Es_Data_Manager_Option_Item
 */
class Es_Data_Manager_Item
{
    /**
     * @var string Option name with all data.
     */
    protected $_option_storage_name;
    /**
     * @var string current data value option name.
     */
    protected $_current_option_name;
    /**
     * @var array settings array.
     */
    protected $_options;
    /**
     * @var string Data manager item template path.
     */
    protected $_template_path = 'data-manager/item.php';

    /**
     * Es_Data_Manager_Item constructor.
     *
     * @param $option_storage_name
     * @param $current_option_name
     * @param $options
     */
    public function __construct( $option_storage_name, $current_option_name, $options = array() ) {
        $this->_option_storage_name = sanitize_key( $option_storage_name );
        $this->_current_option_name = sanitize_key( $current_option_name );
        $this->_options = $options;
    }

    /**
     * Return template path of data manager item.
     *
     * @return mixed
     */
    protected function get_template_path() {
        $path = es_locate_template( $this->_template_path, 'admin' );
        return apply_filters( 'es_data_manager_item_get_template_path', $path, get_called_class() );
    }

    /**
     * Return list of data manager item data list.
     *
     * @return mixed
     */
    public function getItems()
    {
        $items = Es_Settings_Container::get_setting_values( $this->_current_option_name );
        return apply_filters( 'es_data_manager_get_items', $items, $this );
    }

    /**
     * @return array
     */
    public function get_options() {

        return $this->_options;
    }

    /**
     * Return selected option value.
     *
     * @return mixed
     */
    public function get_current_item()
    {
        return get_option( $this->_current_option_name );
    }

    /**
     * Render data manager item.
     *
     * @return void
     */
    public function render() {
        include $this->get_template_path();
    }

    /**
     * Save option data.
     *
     * @return void
     */
    public static function save()
    {
        // Nonce field name.
        $nonce = 'es_add_data_manager_option';

        // If ajax request is valid.
        if ( check_ajax_referer( $nonce, $nonce ) && current_user_can( 'es_save_dm_item' ) ) {
            // Get available values of the item.

	        $current_option_name = sanitize_key( $_POST['current_option_name'] );
            $values = Es_Settings_Container::get_setting_values( $current_option_name );

            // If items exists.
            if ( in_array( sanitize_text_field( $_POST['item_name'] ), $values ) ) {
                $response = array( 'message' => __( 'Item already exists.', 'es-plugin' ), 'status' => 'warning' );

            // Add new item to the option.
            } else {
				$option_storage_name = filter_input( INPUT_POST, 'option_storage_name' );
				$item_name = filter_input( INPUT_POST, 'item_name' );

	            $option_storage_name = sanitize_key( $option_storage_name );
	            $item_name = sanitize_text_field( $item_name );

                $values = get_option( $option_storage_name, array() );

                $key = sanitize_text_field( $_POST['item_name'] );
                $values[ $key ] = $item_name;

                update_option( $option_storage_name, $values );

                $response = array(
                    'message' => __( 'Item is successfully created.', 'es-plugin' ),
                    'status' => 'success',
                    'item' => array_search( $item_name, $values )
                );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }

    /**
     * Remove item from data list.
     *
     * @return void
     */
    public static function remove()
    {
        // If valid ajax request.
        if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) && current_user_can( 'es_delete_dm_item' ) ) {
            // Get available values.
            $values = Es_Settings_Container::get_setting_values( sanitize_key( $_POST['container'] ) );

            // Remove item using ID and storage.
            if ( ! empty( $values ) ) {
                $values = get_option( sanitize_key( $_POST['storage'] ), array() );
                $key = sanitize_text_field( $_POST['id'] );
                unset( $values[ $key ] );
                update_option( sanitize_key( $_POST['storage'] ), $values );

                $response = array( 'message' => __( 'Item is successfully deleted.', 'es-plugin' ), 'status' => 'success' );
            } else {
                $response = array( 'message' => __( 'Nothing for delete.', 'es-plugin' ), 'status' => 'warning' );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }

    /**
     * Check option as default.
     *
     * @return void.
     */
    public function check()
    {
        // If valid ajax request.
        if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) && current_user_can( 'es_save_dm_item' ) && ! empty( $_POST['id'] ) ) {

            /** @var Es_Settings_Container $es_settings */
            global $es_settings;
            $es_settings->saveOne( sanitize_text_field( $_POST['container'] ), sanitize_text_field( $_POST['id'] ) );

            $response = array( 'message' => __( 'Item has been selected.', 'es-plugin' ), 'status' => 'success' );
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }
}
