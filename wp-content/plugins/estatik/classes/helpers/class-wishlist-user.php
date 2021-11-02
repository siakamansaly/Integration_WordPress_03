<?php

/**
 * Class Es_Wishlist_User.
 */
class Es_Wishlist_User implements Es_Wishlist_Interface {

	/**
	 * @var integer
	 */
	protected $user_id;

	/**
	 * Es_Wishlist_User constructor.
	 *
	 * @param $user_id
	 */
	public function __construct( $user_id ) {

		$this->user_id = $user_id;
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|false|int
	 */
	public function add( $post_id ) {

		if ( ! $this->has( $post_id ) ) {
			return add_user_meta( $this->user_id, 'es_wishlist_item', $post_id );
		}

		return true;
	}

	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function remove( $post_id ) {

		return delete_user_meta( $this->user_id, 'es_wishlist_item', $post_id );
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

		$data = get_user_meta( $this->user_id, 'es_wishlist_item' );

		return $data ? $data : array();
	}
}