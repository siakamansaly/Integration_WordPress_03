<?php

/**
 * Class Es_Data_Manager_Page
 */
class Es_Data_Manager_Page extends Es_Object
{
    /**
     * Register actions for data manager page.
     *
     * @return void
     */
    public function actions()
    {
        add_action( 'wp_ajax_es_ajax_data_manager_add_term', array( 'Es_Data_Manager_Term_Item', 'save' ) );
        add_action( 'wp_ajax_es_ajax_data_manager_add_option', array( 'Es_Data_Manager_Item', 'save' ) );
	    add_action( 'wp_ajax_es_ajax_data_manager_add_label', array( 'Es_Data_Manager_Label_Item', 'save' ) );
	    add_action( 'wp_ajax_es_ajax_data_manager_label_color', array( 'Es_Data_Manager_Label_Item', 'change_color' ) );
        add_action( 'wp_ajax_es_ajax_data_manager_remove_term', array( 'Es_Data_Manager_Term_Item', 'remove' ) );
        add_action( 'wp_ajax_es_ajax_data_manager_remove_option', array( 'Es_Data_Manager_Item', 'remove' ) );
        add_action( 'wp_ajax_es_ajax_data_manager_check_option', array( 'Es_Data_Manager_Item', 'check' ) );

        add_action( 'wp_ajax_es_ajax_data_manager_add_currency', array( 'Es_Data_Manager_Currency_Item', 'save' ) );
        add_action( 'wp_ajax_es_ajax_data_manager_remove_currency', array( 'Es_Data_Manager_Currency_Item', 'remove' ) );
        add_action( 'wp_ajax_es_ajax_data_manager_check_currency', array( 'Es_Data_Manager_Currency_Item', 'check' ) );
    }

    /**
     * Render data manager page content.add
     *
     * @return void
     */
    public static function render() {
        es_load_template( 'data-manager/data-manager.php', 'admin', 'es_data_manager_page_template' );
    }

    /**
     * Return tabs of the data manager page.
     *
     * @return array
     */
    public static function get_tabs()
    {
        return apply_filters( 'es_data_manager_get_tabs', array(
            'properties-details' => array(
                'label' => __( 'Properties details', 'es-plugin' ),
                'template' => es_locate_template( 'data-manager/properties-details-tab.php', 'admin' ),
            ),
            'features' => array(
                'label' => __( 'Features', 'es-plugin' ),
                'template' => es_locate_template( 'data-manager/features-tab.php', 'admin' ),
            ),
            'labels' => array(
	            'label' => __( 'Labels', 'es-plugin' ),
                'template' => es_locate_template( 'data-manager/labels-tab.php', 'admin' ),
            ),
            'currencies' => array(
                'label' => __( 'Currencies', 'es-plugin' ),
                'template' => es_locate_template( 'data-manager/currencies-tab.php', 'admin' ),
            ),
            'dimensions' => array(
	            'label' => __( 'Dimensions', 'es-plugin' ),
                'template' => es_locate_template( 'data-manager/dimensions-tab.php', 'admin' ),
            ),
            'addresses' => array(
	            'label' => __( 'Addresses', 'es-plugin' ),
                'template' => es_locate_template( 'data-manager/addresses-tab.php', 'admin' ),
            ),
        ) );
    }
}
