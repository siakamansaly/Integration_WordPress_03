<?php

/**
 * Interface Es_Wishlist_Interface.
 */
interface Es_Wishlist_Interface {

	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function add( $post_id );

	/**
	 * @param $post_id
	 *
	 * @return void
	 */
	public function remove( $post_id );

	/**
	 * @param array $args
	 *
	 * @return WP_Query
	 */
	public function get_query( array $args = array() );

	/**
	 * @return integer
	 */
	public function get_count();

	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function has( $post_id );

	/**
	 * Return array of entities ids.
	 *
	 * @return array
	 */
	public function get_data();
}
