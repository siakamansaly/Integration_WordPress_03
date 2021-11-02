<?php

/**
 * Class Es_Buyer.
 */
class Es_Buyer extends Es_User {

	/**
	 * Return entity prefix string.
	 *
	 * @return string
	 */
	public function get_entity_prefix() {
		return 'es_buyer_';
	}

	/**
	 * Return user role name.
	 *
	 * @return null
	 */
	public static function get_role_name() {

		return 'es_buyer';
	}

	/**
	 * @inheritdoc
	 */
	public function get_entity_name() {

		return 'buyer';
	}

	/**
	 * @return string
	 */
	public function get_base_field_name() {
		return 'es_buyer';
	}
}
