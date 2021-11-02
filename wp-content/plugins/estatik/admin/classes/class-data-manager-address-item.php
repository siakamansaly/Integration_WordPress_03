<?php

/**
 * Class Es_Data_Manager_Address_Item.
 */
class Es_Data_Manager_Address_Item {

	/**
	 * @var string
	 */
	protected $_type;

	/**
	 * @var int
	 */
	public $limit = 10;

	/**
	 * @var array settings array.
	 */
	public $_options;

	/**
	 * Es_Data_Manager_Item constructor.
	 *
	 * @param $type
	 * @param array $options
	 */
	public function __construct( $type, $options = array() )
	{
		$this->_options = $options;
		$this->_type = $type;
	}

	/**
	 * Render address item.
	 *
	 * @return void
	 */
	public function render() {
		es_load_template( 'data-manager/address-item.php', 'admin', null, array(
			'object' => $this,
		) );
	}

	/**
	 * Remove address component.
	 *
	 * @return void
	 */
	public static function ajax_remove_item() {
		if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) && current_user_can( 'es_delete_dm_item' ) && ! empty( $_POST['id'] ) ) {
			ES_Address_Components::delete_component( intval( $_POST['id'] ) );

			$response = array( 'message' => __( 'Item is successfully deleted.', 'es-plugin' ), 'status' => 'success' );
		} else {
			$response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
		}

		wp_die( json_encode( $response ) );
	}

	/**
	 * Return address items.
	 *
	 * @return array
	 */
	public function getItems() {

		$items = array();
		$result = array();

		if ( $this->_type ) {
			if ( is_array( $this->_type ) ) {
				foreach ( $this->_type as $type ) {
					$items = array_merge( $items, ES_Address_Components::get_component_list( $type ) );
				}
			} else {
				$items = ES_Address_Components::get_component_list( $this->_type );
			}
		}

		if ( $items ) {
			foreach ( $items as $item ) {
				$result[ $item->id ] = $item->long_name ? $item->long_name : $item->short_name;
			}

			$result = array_filter( $result );
		}

		return apply_filters( 'es_dm_address_get_items', $result, $this );
	}
}
add_action( 'wp_ajax_es_dm_remove_address', array( 'Es_Data_Manager_Address_Item', 'ajax_remove_item' ) );
