<?php

/**
 * Class Es_Wishlist_Cookie.
 */
class Es_Wishlist_Cookie implements Es_Wishlist_Interface {

	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function add( $post_id ) {

		if ( ! $this->has( $post_id ) ) {
			$data = $this->get_data();
			$data[] = $post_id;
			return setcookie( 'es_wishlist', serialize( $data ), time() + (31449600), '/' );
		}
	}

	/**
	 * @param $post_id
	 */
	public function remove( $post_id ) {

        $data = $this->get_data();
        $key = array_search( $post_id, $data );

        if ( $key !== false ) {
            unset( $data[ $key ] );

            setcookie( 'es_wishlist', serialize( $data ), time() + (31449600), '/' );
        }
	}

	/**
	 * @param array $args
	 *
	 * @return WP_Query
	 */
	public function get_query( array $args = array() ) {

		$args = wp_parse_args( $args, array(
			'post_type' => 'properties',
			'post_status' => 'publish',
		) );

		if ( $data = $this->get_data() ) {
			$args['post__in'] = $data;
			$args['orderby'] = 'post__in';
		} else {
			$args['post__in'] = array( -1 );
		}

		return new WP_Query( $args );
	}

	/**
	 * @return integer
	 */
	public function get_count() {

		return count( $this->get_data() );
	}

	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function has( $post_id ) {

		$data = $this->get_data();

		return in_array( $post_id, $data );
	}

	/**
	 * Return array of entities ids.
	 *
	 * @return array
	 */
	public function get_data() {

		return ! empty( $_COOKIE['es_wishlist'] ) ? unserialize( $_COOKIE['es_wishlist'] ) : array();
	}
}
